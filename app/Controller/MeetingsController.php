<?php
App::uses('AppController', 'Controller');
class MeetingsController extends AppController {

	public $uses = array('Event', 'User', 'Affiliation', 'Meeting', 'MeetingFile');

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
			$this->request->data['Search']['event_type']	= 0;
			$this->request->data['Search']['keyword']		= '';
			$this->request->data['Search']['start']			= '';
			$this->request->data['Search']['end']			= '';
		}
		
		$conditions = array();
		$conditions[] = array('Meeting.is_delete' => 0);
		
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array(
				'OR' => array(
					0 => array('Meeting.event_number LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%'),
					1 => array('Meeting.title LIKE'			=> '%' . trim($this->request->data['Search']['keyword']) . '%')
				)
			);
		}
		
		// 開始日
		if ( isset($this->request->data['Search']['start']) && !empty($this->request->data['Search']['start']) )
		{
			$conditions[] = array('Meeting.start >= ?'		=> $this->request->data['Search']['start']);
		}
		
		// 終了日
		if ( isset($this->request->data['Search']['end']) && !empty($this->request->data['Search']['end']) )
		{
			$conditions[] = array('Meeting.end <= ?'		=> $this->request->data['Search']['end']);
		}
		
		// HPに表示
		if ( isset($this->request->data['Search']['is_display']) && !empty($this->request->data['Search']['is_display']) )
		{
			$conditions[] = array('Meeting.is_display'	=> $this->request->data['Search']['is_display']);
		}
		
		$this->modelClass = "Meeting";
		$this->paginate = array(
			'contain' => array(
			),
			'conditions' => $conditions,
		);
		$meetings = $this->paginate();
		$this->set('meetings', $meetings);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		//企画ステータスの一覧
		$event_status = Configure::read('App.event_status');
		$event_status[-1] = '------';
		ksort($event_status);
		$this->set('event_status', $event_status);
	}

	// 研究集会データの作成
	public function add ($event_id = 0)
	{
		$event = array();
		if ( !empty($event_id) )
		{
			$event = $this->Event->find('first', array(
				'contain' => array(
					'EventTheme' => array(
						'Theme'
					),
					'EventKeyword',
					'EventManager',
					'EventFile'
				),
				'conditions' => array(
					'Event.id' => $event_id
				)
			));
		}
		$this->set('event_id', $event_id);
		$this->set('event', $event);
		
		if ( $this->request->is('post') )
		{
			$rollback = false;
			$last_id = 0;
			$this->Meeting->begin();
			
			$this->request->data['Meeting']['event_id']			= $event_id;
			$this->request->data['Meeting']['admin_id']			= $this->Auth->user('id');
			$this->request->data['Meeting']['latest_admin_id']	= $this->Auth->user('id');
			
			if ( !$this->Meeting->save($this->request->data) )
			{
				$rollback = true;
			}
			else
			{
				$last_id = $this->Meeting->getLastInsertID();
			}
			
			if ( !$rollback )
			{
				if ( !empty($event['EventFile']))
				{
					foreach ( $event['EventFile'] as $key => $event_file )
					{
						// Eventのファイルを取得してMeetingへコピーする
						
						// 元ファイルのパス
						$original_path = WWW_ROOT . 'files' . DS . 'event_file' . DS . 'file' . DS . $event_file['id'] . DS;
						// 元ファイル名
						$original_filename = $event_file['file'];

						
						// file アップロードしたことにする
						$save = array();
						$save['MeetingFile']['meeting_id']	= $last_id;
						$save['MeetingFile']['file']		= $original_filename;
						$save['MeetingFile']['file_org']	= $event_file['file_org'];
						$this->MeetingFile->create();
						if ( !$this->MeetingFile->save($save) )
						{
							$rollback = true;
							break;
						}
						else
						{
							$last_meeting_file_id = $this->MeetingFile->getLastInsertID();
							
							// 新ファイルのパス
							$new_path = WWW_ROOT . 'files' . DS . 'meeting_file' . DS . 'file' . DS . $last_meeting_file_id . DS;
							
							// 新ファイル名（元と同じ）
							$new_filename = $original_filename;
							
							if ( !is_dir( WWW_ROOT . 'files' . DS . 'meeting_file' ) )
							{
								if ( !mkdir(WWW_ROOT . 'files' . DS . 'meeting_file', 0777) )
								{
									die('X01');
								}
								
								if ( !mkdir(WWW_ROOT . 'files' . DS . 'meeting_file' . DS . 'file', 0777) )
								{
									die('X02');
								}
							}
							
							if (!is_dir( $new_path ))
							{
								if ( !mkdir($new_path, 0777) )
								{
									die('X03');
								}
							}
							
							$original_file = file_get_contents( $original_path . $original_filename );
							
							if ( is_file(file_put_contents($new_path . DS . $new_filename, $original_file)) )
							{
								if ( !copy($new_path . DS . $new_filename, $original_file) )
								{
									die('X02');
								}
							}
							
							$save2 = array();
							$save2['MeetingFile']['id']			= $last_meeting_file_id;
							$save2['MeetingFile']['file_dir']	= $last_meeting_file_id;
							$this->MeetingFile->set($save2);
							if ( !$this->MeetingFile->save( $save2 ) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
			}
			
			if ( !$rollback )
			{
				$this->Meeting->commit();
				$this->Session->setFlash('データを更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// 失敗時
				$this->Meeting->rollback();
				$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
			}
		}
		else
		{
			// 初期値
			$this->request->data['Meeting']['qualification_apply'] = 0;
			$this->request->data['Meeting']['is_qualification_cost'] = 0;
			
			// Event.idが渡された場合の初期表示
			if ( !empty($event) )
			{
				$this->request->data['Meeting']['event_id'] = $event['Event']['id'];
				$this->request->data['Meeting']['type'] = $event['Event']['type'];
				$this->request->data['Meeting']['fiscal_year'] = $event['Event']['fiscal_year'];
				$this->request->data['Meeting']['event_number'] = $event['Event']['event_number'];
				$this->request->data['Meeting']['title'] = $event['Event']['title'];
				$this->request->data['Meeting']['field'] = $event['Event']['field'];
				$this->request->data['Meeting']['organization'] = $event['Event']['organization'];
				$this->request->data['Meeting']['start'] = $event['Event']['start'];
				$this->request->data['Meeting']['end'] = $event['Event']['end'];
				$this->request->data['Meeting']['place'] = $event['Event']['place'];
				$this->request->data['Meeting']['program'] = $event['Event']['program'];
				$this->request->data['Meeting']['purpose'] = $event['Event']['purpose'];
				$this->request->data['Meeting']['subject'] = $event['Event']['subject'];
				$this->request->data['Meeting']['approach'] = $event['Event']['approach'];
				$this->request->data['Meeting']['follow'] = $event['Event']['follow'];
				$this->request->data['Meeting']['support'] = $event['Event']['support'];
				$this->request->data['Meeting']['issue'] = $event['Event']['issue'];
				$this->request->data['Meeting']['new_subject'] = $event['Event']['new_subject'];
				$this->request->data['Meeting']['qualification'] = $event['Event']['qualification'];
				$this->request->data['Meeting']['qualification_apply'] = $event['Event']['qualification_apply'];
				$this->request->data['Meeting']['qualification_method'] = $event['Event']['qualification_method'];
				$this->request->data['Meeting']['is_qualification_cost'] = $event['Event']['is_qualification_cost'];
				$this->request->data['Meeting']['qualification_cost'] = $event['Event']['qualification_cost'];
				$this->request->data['MeetingFile'] = $event['EventFile'];
				
				$this->request->data['Meeting']['manager'] = '';
				if ( !empty($event['EventManager']) )
				{
					foreach ( $event['EventManager'] as $event_manager )
					{
						$this->request->data['Meeting']['manager'] .= '■' . $event_manager['lastname'] . ' ' . $event_manager['firstname'];
						$this->request->data['Meeting']['manager'] .= '（' . $event_manager['lastname_kana'] . ' ' . $event_manager['firstname_kana'] . "）";
						$this->request->data['Meeting']['manager'] .= $event_manager['organization'] . ' ' . $event_manager['department'] . ' ' . $event_manager['job_title'] . "\r\n";
						if ( !empty ($event_manager['url']) )
						{
							$this->request->data['Meeting']['manager'] .= $event_manager['url'] . "\r\n";
						}
					}
				}
			}
		}
		
		$this->set('options', Configure::read('App.event_type'));
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
	}

	// 研究集会データの編集
	public function edit ($id = null)
	{
		$this->Meeting->id = $id;
		if ( !$this->Meeting->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$meeting = $this->Meeting->find('first', array(
			'contain' => array(
				'Event' => array(
					'EventFile'
				),
				'MeetingFile'
			),
			'conditions' => array(
				'Meeting.id' => $id
			)
		));
		$this->set('meeting', $meeting);
		
		if ( $this->request->is('post') || $this->request->is('put') )
		{
			if ( isset($this->request->data['save']) )
			{
				$rollback = false;
				$this->MeetingFile->begin();
				
				$this->request->data['Meeting']['id']				= $meeting['Meeting']['id'];
				$this->request->data['Meeting']['latest_admin_id']	= $this->Auth->user('id');
				if ( !$this->Meeting->save($this->request->data['Meeting']) )
				{
					$rollback = true;
				}
				
				if ( !$rollback )
				{
					if ( !empty($this->request->data['MeetingFile']) )
					{
						foreach ( $this->request->data['MeetingFile'] as $meeting_file )
						{
							$this->MeetingFile->create();
							if ( !$this->MeetingFile->save($meeting_file) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->MeetingFile->commit();
					$this->Session->setFlash('データの更新しました。', 'Flash/success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					// 失敗時
					$this->MeetingFile->rollback();
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			else if (isset($this->request->data['add_meeting_file']))
			{
				$this->request->data['MeetingFile'][] = array(
					'id'		=> 0,
					'meeting_id'	=> $id,
					'name'		=> '',
					'file'		=> '',
					'file_org'	=> '',
				);
			}
		}
		else
		{
			$this->request->data = $meeting;
		}
		
		$this->set('options', Configure::read('App.event_type'));
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
	}

	// 添付ファイル削除
	public function file_delete($id = null)
	{
		if ( empty($id) )
		{
			$this->Session->setFlash('Invalid ID', 'Flash/error');
			$this->redirect(array('action' => 'report_list'));
		}
		
		$meeting_file = $this->MeetingFile->find('first', array(
			'contain' => array(
				'Meeting'
			),
			'conditions' => array(
				'MeetingFile.id' => $id
			)
		));
		
		if ( !$this->MeetingFile->delete($id) )
		{
			$this->Session->setFlash('添付ファイルの削除に失敗しました。管理者にお問合わせください。', 'Flash/error');
		}
		else
		{
			$this->Session->setFlash('添付ファイルを削除しました。', 'Flash/success');
		}
		$this->redirect(array('action' => 'edit', $meeting_file['Meeting']['id']));
	}

}
