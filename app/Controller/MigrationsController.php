<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class MigrationsController extends AppController {

	public $uses = array(
		'Migration',
		'MigrationChapter',
		'MigrationPage',
		'Researcher',
		'Event',
		'EventProgram',
		'Affiliation',
		'Venue',
		'ResearchCase'
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	// 一覧
	public function index()
	{
		// 検索するボタン押下時 もしくは ページネーションのリンククリック時
		if ($this->request->is('post') || !empty($this->request->params['named']))
		{
			if (empty($this->request->data['Search']) && !empty($this->request->params['named']))
			{
				foreach ($this->request->params['named'] as $k => $v)
				{
					$this->request->data['Search'][$k] = $v;
				}
			}
			
			// 検索条件をページネーションに引き継ぎ
			foreach ($this->request->data['Search'] as $k => $v)
			{
				$this->request->params['named'][$k] = $v;
			}
			
			
			
			
			
			
		}
		else
		{
			// デフォルト
			$this->request->data['Search']['keyword']			= '';
		}
		
		$conditions = array();
		$conditions += array('Migration.is_delete' => 0);
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array('Migration.title LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%');
		}
		
		$this->Migration->hasMany['MigrationChapter']['conditions'] = array('MigrationChapter.is_delete' => 0);
		$this->MigrationChapter->hasMany['MigratioPage']['conditions'] = array('MigratioPage.is_delete' => 0);
		$this->paginate = array(
			'contain' => array(
				'MigrationChapter' => array(
					'MigrationPage'
				)
			),
			'conditions' => $conditions,
			'order' => 'Migration.id DESC',
			'limit' => 20
		);
		
		$migrations = $this->paginate();
		$this->set('migrations', $migrations);
	}

	// 追加
	public function add( $id = null ) {
		if ( $this->request->is('post') )
		{
			// 一時保存ボタン押下時
			if ( isset($this->request->data['save']) )
			{
				
				$migration_id = $this->request->data['Migration']['id'];
				
				$rollback = false;
				$this->Migration->begin();
				
				if ( empty($this->request->data['Migration']['id']) )
				{
					unset($this->request->data['Migration']['id']);
				}
				
				// Migration 更新
				if ( !$this->Migration->save($this->request->data['Migration']) )
				{
					$rollback = true;
				}
				else
				{
					if ( empty($this->request->data['Migration']['id']) )
					{
						$migration_id = $this->Migration->getLastInsertID();
					}
				}
				
				if ( !$rollback )
				{
					foreach ( $this->request->data['MigrationChapter'] as $migration_chapter )
					{
						$migration_chapter_id = $migration_chapter['id'];
						
						$migration_chapter['migration_id'] = $migration_id;
						if ( empty($migration_chapter['id']) )
						{
							unset($migration_chapter['id']);
							$this->MigrationChapter->create();
							// MigrationChapter挿入
							if ( !$this->MigrationChapter->save($migration_chapter) )
							{
								// 失敗
								$rollback = true;
								break;
							}
							else
							{
								$migration_chapter_id = $this->MigrationChapter->getLastInsertID();
							}
						}
						else
						{
							$this->MigrationChapter->set($migration_chapter);
							// MigrationChapter更新
							if ( !$this->MigrationChapter->save($migration_chapter) )
							{
								// 失敗
								$rollback = true;
								break;
							}
						}
						
						if ( !$rollback )
						{
							
							foreach ( $migration_chapter['MigrationPage'] as $migration_page )
							{
								$migration_page['migration_chapter_id'] = $migration_chapter_id;
								
								//1:数学カタログ、2:研究者、3:研究集会、4:講演課題、5:研究機関、6:研究会場、7:研究事例
								
								$migration_page['migration_id']		= 0;
								$migration_page['researcher_id']	= 0;
								$migration_page['event_id']			= 0;
								$migration_page['event_program_id']	= 0;
								$migration_page['affiliation_id']	= 0;
								$migration_page['venue_id']			= 0;
								$migration_page['case_id']			= 0;
								if ( isset($migration_page['target_id']) )
								{
									if ( $migration_page['type'] == 1 )
									{
										$migration_page['migration_id'] = $migration_page['target_id'];
									}
									else if ( $migration_page['type'] == 2 )
									{
										$migration_page['researcher_id'] = $migration_page['target_id'];
									}
									else if ( $migration_page['type'] == 3 )
									{
										$migration_page['event_id'] = $migration_page['target_id'];
									}
									else if ( $migration_page['type'] == 4 )
									{
										$migration_page['event_program_id'] = $migration_page['target_id'];
									}
									else if ( $migration_page['type'] == 5 )
									{
										$migration_page['affiliation_id'] = $migration_page['target_id'];
									}
									else if ( $migration_page['type'] == 6 )
									{
										$migration_page['venue_id'] = $migration_page['target_id'];
									}
									else if ( $migration_page['type'] == 7 )
									{
										$migration_page['case_id'] = $migration_page['target_id'];
									}
								}
								// idが0であれば新規挿入
								if ( $migration_page['id'] == 0 )
								{
									unset($migration_page['id']);
									$this->MigrationPage->create();
									// MigrationPageを保存
									if ( !$this->MigrationPage->save($migration_page) )
									{
										// 保存失敗処理を止める
										$rollback = true;
										break;
									}
								}
								// idが0でなければ更新
								else
								{
									$this->MigrationPage->set($migration_page);
									// MigrationPageを保存
									if ( !$this->MigrationPage->save($migration_page) )
									{
										// 保存失敗処理を止める
										$rollback = true;
										break;
									}
								}
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->Migration->commit();
					
					$this->Session->setFlash('入力データを更新しました。', 'Flash/success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->Migration->rollback();
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			// 章を追加の場合
			else if ( isset($this->request->data['add-chapter']) )
			{
				$chapter_count	= count($this->request->data['MigrationChapter']) + 1;
				
				
				$this->request->data['MigrationChapter'][] = array(
					'id'			=> 0,
					'migration_id'	=> $this->request->data['Migration']['id'],
					'sort'		=> $chapter_count,
					'title'		=> '',
					'body'		=> '',
					'MigrationPage' => array(
						0 => array(
							'id'						=> 0,
							'migration_chapter_id'		=> '',
							'sort'						=> 1,
							'type'						=> '',
							'title'						=> '',
						)
					)
				);
			}
			else
			{
				//print_a_die($this->request->data);
				// 更新以外のボタンを押下時
				foreach ($this->request->data['MigrationChapter']  as $key1 => $migration_chapter )
				{
					
					// 項を追加の場合
					if ( isset($migration_chapter['add-page']) )
					{
						 $this->request->data['MigrationChapter'][$key1]['MigrationPage'][] = array(
							'id'						=> 0,
							'migration_chapter_id'		=> '',
							'sort'						=> count($this->request->data['MigrationChapter'][$key1]['MigrationPage']) + 1,
							'type'						=> '',
							'title'						=> '',
						);
					}
					// 講演を削除の場合
					if ( isset($migration_chapter['delete-program']) )
					{
						// DBに保存済み
						if ( !empty($migration_chapter['id']) && is_numeric($migration_chapter['id']) )
						{
							$migration_chapter['is_delete']  = 1;
							if ( $this->EventProgram->save($event_program) )
							{
								
							}
						}
						unset($this->request->data['EventProgram'][$key1][$key2]);
					}
					else
					{
						/*
						foreach ( $event_program['EventPerformer'] as $key3 => $event_performer )
						{
							// 講演者を削除の場合
							if ( isset($event_performer['delete-performer']) )
							{
								// DBに保存済み
								if ( !empty($event_performer['id']) && is_numeric($event_performer['id']) )
								{
									$event_performer['is_delete']  = 1;
									if ( $this->EventPerformer->save($event_performer) )
									{
										
									}
								}
								unset($this->request->data['EventProgram'][$key1][$key2]['EventPerformer'][$key3]);
							}
						}
						*/
					}
				}
			}
		}
		else
		{
			$migrations = array();
			if ( !empty($id) )
			{
				$migration = $this->Migration->find('first', array(
					'contain' => array(
						'MigrationChapter' => array(
							'MigrationPage'
						)
					),
					'conditions' => array(
						'Migration.id' => $id
					)
				));
				
				if ( !empty($migration) )
				{
					foreach( $migration['MigrationChapter'] as &$migration_chapter )
					{
						foreach( $migration_chapter['MigrationPage'] as &$migration_page )
						{
							if ( !empty($migration_page['migration_id']) )
							{
								$migration_page['target_id'] = $migration_page['migration_id'];
							}
							else if ( !empty($migration_page['researcher_id']) )
							{
								$migration_page['target_id'] = $migration_page['researcher_id'];
							}
							else if ( !empty($migration_page['event_id']) )
							{
								$migration_page['target_id'] = $migration_page['event_id'];
							}
							else if ( !empty($migration_page['event_program_id']) )
							{
								$migration_page['target_id'] = $migration_page['event_program_id'];
							}
							else if ( !empty($migration_page['affiliation_id']) )
							{
								$migration_page['target_id'] = $migration_page['affiliation_id'];
							}
							else if ( !empty($migration_page['venue_id']) )
							{
								$migration_page['target_id'] = $migration_page['venue_id'];
							}
							else if ( !empty($migration_page['case_id']) )
							{
								$migration_page['target_id'] = $migration_page['case_id'];
							}
						}
					}
				}
			}
			
			if ( empty($migration) )
			{
				$migration = array(
					'Migration' => array(
						'id'		=> 0,
						'title'		=> '',
						'body'		=> '',
						'sort'		=> 1,
					),
					'MigrationChapter' => array(
						0 => array(
							'id'				=> 0,
							'migration_id'		=> 0,
							'title'				=> '',
							'body'				=> '',
							'sort'				=> 1,
							'MigrationPage' => array(
								0 => array(
									'id'					=> 0,
									'migration_chapter_id'	=> 0,
									'title'					=> '',
									'type'					=> '',
									'body'					=> '',
									'target_id'				=> '',
									'sort'					=> 1,
								)
							)
						)
					)
				);
			}
			$this->request->data = $migration;
		}
		
		$this->set('databases', Configure::read('App.databases'));
		
		
		$options1 = $this->Migration->find('list', array('conditions' => array('Migration.is_display' => 1, 'Migration.is_delete' => 0)));
		
		$buff = $this->Researcher->find('all', array(
			'contain' => array(),
			'fields' => array(
				'id', 'name_ja'
			),
			'conditions' => array(
				'Researcher.is_display' => 1,
				'Researcher.is_delete' => 0
			),
			'order' => 'Researcher.name_ja ASC'
		));
		
		$ret = array();
		if ( !empty($buff) )
		{
			foreach ( $buff as $bf )
			{
				$ret[$bf['Researcher']['id']] = $bf['Researcher']['name_ja'];
			}
		}
		$options2 = $ret;
		$options3 = $this->Event->find('list', array('conditions' => array('Event.status' => 5, 'Event.is_delete' => 0)));
		$options4 = $this->EventProgram->find('list', array('conditions' => array('EventProgram.is_display' => 1, 'EventProgram.is_delete' => 0)));
		$options5 = $this->Affiliation->find('list', array('conditions' => array('Affiliation.is_display' => 1, 'Affiliation.is_delete' => 0)));
		$options6 = $this->Venue->find('list', array('conditions' => array('Venue.is_display' => 1, 'Venue.is_delete' => 0)));
		$options7 = $this->ResearchCase->find('list', array('conditions' => array('ResearchCase.is_display' => 1, 'ResearchCase.is_delete' => 0)));
		
		$this->set('options1', $options1);
		$this->set('options2', $options2);
		$this->set('options3', $options3);
		$this->set('options4', $options4);
		$this->set('options5', $options5);
		$this->set('options6', $options6);
		$this->set('options7', $options7);
	}

	// 数学カタログ
	public function get_migration()
	{
		//'Migration',
		$this->layout = false;
		$this->autoRender = false;
		
		$ret = $this->Migration->find('list', array('conditions' => array('Migration.is_display' => 1, 'Migration.is_delete' => 0)));
		
		echo json_encode($ret);
		die();
	}
	
	// 研究者情報
	public function get_researcher()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$buff = $this->Researcher->find('all', array(
			'contain' => array(),
			'fields' => array(
				'id', 'name_ja'
			),
			'conditions' => array(
				'Researcher.is_display' => 1,
				'Researcher.is_delete' => 0
			),
			'order' => 'Researcher.name_ja ASC'
		));
		
		$ret = array();
		if ( !empty($buff) )
		{
			foreach ( $buff as $bf )
			{
				$ret[$bf['Researcher']['id']] = $bf['Researcher']['name_ja'];
			}
		}
		
		echo json_encode($ret);
		die();
	}
	
	// 研究集会
	public function get_event()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$ret = $this->Event->find('list', array('conditions' => array('Event.status' => 5, 'Event.is_delete' => 0)));
		
		echo json_encode($ret);
		die();
	}
	
	// 講演課題
	public function get_event_program()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$ret = $this->EventProgram->find('list', array('conditions' => array('EventProgram.is_display' => 1, 'EventProgram.is_delete' => 0)));
		
		echo json_encode($ret);
		die();
	}
	
	// 研究組織
	public function get_affiliation()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$ret = $this->Affiliation->find('list', array('conditions' => array('Affiliation.is_display' => 1, 'Affiliation.is_delete' => 0)));
		
		echo json_encode($ret);
		die();
	}
	
	// 研究会場
	public function get_venue()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$ret = $this->Venue->find('list', array('conditions' => array('Venue.is_display' => 1, 'Venue.is_delete' => 0)));
		
		echo json_encode($ret);
		die();
	}
	
	// 研究事例
	public function get_case()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$ret = $this->ResearchCase->find('list', array('conditions' => array('ResearchCase.is_display' => 1, 'ResearchCase.is_delete' => 0)));
		
		echo json_encode($ret);
		die();
	}
	
}
