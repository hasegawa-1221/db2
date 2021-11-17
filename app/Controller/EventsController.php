<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class EventsController extends AppController {

	public $uses = array(
		'Event', 'EventTheme', 'Expense', 'EventManager', 'EventAffair',
		'Item', 'Theme', 'Prefecture', 'User', 'Affiliation', 'EventProgram', 'EventPerformer', 'EventKeyword', 'EventFile'
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
		}
		
		$conditions = array();
		$conditions += array('Event.is_delete' => 0);
		
		$this->Event->hasMany['EventManager']['conditions']	= array('EventManager.is_delete' => 0);
		$this->Event->hasMany['EventAffair']['conditions']	= array('EventAffair.is_delete' => 0);
		$this->Event->hasMany['Expense']['conditions']	= array('Expense.is_delete' => 0);
		$this->Event->hasMany['Expense']['order']		= array('Expense.type ASC');
		$this->paginate = array(
			'contain' => array(
				'EventTheme' => array(
					'Theme'
				),
				'EventKeyword',
				'EventManager',
				'EventAffair',
				'Expense' => array(
					'Item'
				),
				'AddUser',
				'LatestUser',
			),
			'conditions' => $conditions,
			'order' => 'Event.id DESC',
			'limit' => 20
		);
		$events = $this->paginate();
		if ( $events )
		{
			foreach ( $events as $key => $event )
			{
				$expenses = array();
				foreach ( $event['Expense'] as $expense )
				{
					$expenses[$expense['type']][] = $expense;
				}
				$events[$key]['Expense'] = $expenses;
			}
		}
		$this->set('events', $events);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		// 参加についてのドロップダウン
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		
		//企画ステータスの一覧
		$this->set('event_status', Configure::read('App.event_status'));
		
		//課目の一覧
		$this->set('items', $this->Item->find('list', array('conditions' => array('Item.is_delete' => 0))));
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// 企画の追加
	public function add($id = null) {
		
		if( $this->request->is('post') )
		{
			// 一つでもチェックされているかどうか
			$is_checked = false;
			foreach ( $this->request->data['EventTheme'] as $theme )
			{
				if ( $theme['id'] == 1 )
				{
					$is_checked = true;
					break;
				}
			}
			
			// チェックされていない場合のエラー文言
			if ( !$is_checked )
			{
				$this->EventTheme->validationErrors['id'][] = '該当する重点テーマをお選び下さい。';
			}
			
			// テーマが１つでもチェックされている
			if ( $is_checked )
			{
				// トランザクション開始
				$rollback = false;
				$this->Event->begin();
				
				$last_id = 0;
				
				// 現在の年度
				$fiscal_year					= $this->Event->get_fiscal_year();
				
				// 企画種別ごとのシリアル
				$event_serial					= $this->Event->get_next_serial($this->Event->get_fiscal_year(), $this->request->data['Event']['type']);
				
				// 企画番号
				$event_number					= $this->Event->get_event_number($this->request->data['Event']['type']);
				
				$this->request->data['Event']['fiscal_year']		= $fiscal_year;	
				$this->request->data['Event']['event_serial']		= $event_serial;
				$this->request->data['Event']['event_number']		= $event_number;
				$this->request->data['Event']['admin_id']			= $this->Auth->user('id');
				$this->request->data['Event']['latest_admin_id']	= $this->Auth->user('id');
				
				// イベントテーブル挿入
				$this->EventTheme->create();
				if ( !$this->Event->save($this->request->data) )
				{
					$rollback = true;
				}
				else
				{
					$last_id = $this->Event->getLastInsertID();
				}
				
				// EventThemeテーブル挿入用
				if ( !$rollback && $last_id > 0 )
				{
					foreach ( $this->request->data['EventTheme'] as $theme_id => $theme )
					{
						if( $theme['id'] == 1)
						{
							$save = array();
							$save['EventTheme']['event_id'] = $last_id;
							$save['EventTheme']['theme_id'] = $theme_id;
							$this->EventTheme->create();
							if ( !$this->EventTheme->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
					$this->Session->setFlash('企画を登録しました。', 'Flash/success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->Event->rollback();
					$this->Session->setFlash('企画の登録に失敗しました。', 'Flash/error');
				}
			}
			else
			{
				$this->Session->setFlash('入力内容に不備があります。', 'Flash/error');
			}
		}
		else
		{
		}
		
		//テーマの一覧
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
	}

	public function edit($id = null) {
		// データの存在確認
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// イベントデータ取得
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme'
			),
			'conditions' => array(
				'Event.id' => $id
			),
		));
		$this->set('event', $event);
		
		if ( $this->request->is('post') )
		{
			$this->request->data['Event']['id']					= $event['Event']['id'];
			$this->request->data['Event']['latest_admin_id']	= $this->Auth->user('id');
			if ($this->Event->save($this->request->data) )
			{
				$this->Session->setFlash('企画管理情報を更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('企画管理情報の更新に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $event;
		}
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		//企画ステータスの一覧
		$this->set('event_status', Configure::read('App.event_status'));
	}


	// 1ページ目 企画の概要編集
	public function edit1($id = null) {
		$this->Session->write('page', 1);
		
		// データの存在確認
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// イベントデータ取得
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme',
				'EventKeyword'
			),
			'conditions' => array(
				'Event.id' => $id
			),
		));
		$this->set('event', $event);
		
		// パスワードのバリデーションは外しておく
		unset($this->Event->validate['password']);
		
		if( $this->request->is('post') )
		{
			// 一つでもチェックされているかどうか
			$is_checked = false;
			foreach ( $this->request->data['EventTheme'] as $theme )
			{
				if ( $theme['id'] == 1 )
				{
					$is_checked = true;
					break;
				}
			}
			
			// チェックされていない場合のエラー文言
			if ( !$is_checked )
			{
				$this->EventTheme->validationErrors['id'][] = '該当する重点テーマをお選び下さい。';
			}
			
			if ( $is_checked )
			{
				// パスワードを更新するかどうか
				$this->request->data['Event']['password'] = trim($this->request->data['Event']['password']);
				if ( empty($this->request->data['Event']['password']) )
				{
					// 空であれば、更新用データから削除しておく
					unset($this->request->data['Event']['password']);
				}
				
				$rollback = false;
				// トランザクション開始
				$this->Event->begin();
				
				// イベントテーブル更新
				$this->request->data['Event']['id']					= $id;
				$this->request->data['Event']['latest_admin_id']	= $this->Auth->user('id');
				if ( !$this->Event->save($this->request->data) )
				{
					$rollback = true;
				}
				
				// DB上の選択したテーマ
				$db_theme_buff = array();
				foreach ( $event['EventTheme'] as $theme )
				{
					$db_theme_buff[] = $theme['theme_id'];
				}
				$db_theme_buff = implode(',', $db_theme_buff);
				
				// postされた選択したテーマ
				$post_theme_buff = array();
				foreach ( $this->request->data['EventTheme'] as $theme_id => $theme )
				{
					if ( $theme['id'] == 1 )
					{
						$post_theme_buff[] = $theme_id;
					}
				}
				$post_theme_buff = implode(',', $post_theme_buff);
				
				// テーマに差分があった場合
				if ( $db_theme_buff != $post_theme_buff )
				{
					// EventThemeテーブル挿入用
					$event_theme['EventTheme'] = $this->request->data['EventTheme'];
					if ( !$rollback )
					{
						// updateではなく全削除、全挿入する
						// 既存データから該当するデータを削除
						$delete = $this->EventTheme->deleteAll(
							array(
								'EventTheme.event_id' => $event['Event']['id']
							),
							false
						);
						
						// 削除出来なかった場合
						if( !$delete )
						{
							$rollback = true;
						}
						
						// イベントテーマテーブルに挿入
						if( !$rollback )
						{
							foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
							{
								if( $theme['id'] == 1)
								{
									$save = array();
									$save['EventTheme']['event_id'] = $event['Event']['id'];
									$save['EventTheme']['theme_id'] = $theme_id;
									$this->EventTheme->create();
									if ( !$this->EventTheme->save($save) )
									{
										$rollback = true;
										break;
									}
								}
							}
						}
					}
				}
				
				// EventKeywordの更新
				if ( !$rollback )
				{
					if ( !empty($this->request->data['EventKeyword']) )
					{
						foreach ( $this->request->data['EventKeyword'] as $event_keyword )
						{
							// キーワードが入力されている場合のみ挿入 or 更新
							if ( !empty($event_keyword['title']) )
							{
								$save = array();
								$save['EventKeyword']['event_id']	= $id;
								$save['EventKeyword']['title']		= trim($event_keyword['title']);
								if ( !empty($event_keyword['id']) )
								{
									// IDが存在すれば既存データと見なしupdate
									$save['EventKeyword']['id'] = $event_keyword['id'];
									$this->EventKeyword->set($save);
								}
								else
								{
									// IDが存在しなければ新規データと見なしinsert
									$this->EventKeyword->create();
								}
								
								if ( !$this->EventKeyword->save($save) )
								{
									// 保存失敗ロールバックフラグを立て、処理を止める
									$rollback = true;
									break;
								}
							}
							else
							{
								// キーワードが空、且つIDがる場合は削除
								if ( !empty($event_keyword['id']) )
								{
									if ( !$this->EventKeyword->delete($event_keyword['id']) )
									{
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
					$this->Event->commit();
					$this->Session->setFlash('企画の概要を更新しました。', 'Flash/success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->Event->rollback();
					$this->Session->setFlash('企画の概要の更新に失敗しました。', 'Flash/error');
				}
			}
			else
			{
				$this->Session->setFlash('入力内容に不備があります。', 'Flash/error');
			}
		}
		else
		{
			// 初期表示
			$data = array();
			$data['Event']['id']			= $event['Event']['id'];
			$data['Event']['type']			= $event['Event']['type'];
			$data['Event']['event_number']	= $event['Event']['event_number'];
			$data['Event']['username']		= $event['Event']['username'];
			$data['Event']['password'] 		= '';
			$data['Event']['title']			= $event['Event']['title'];
			$data['Event']['field']			= $event['Event']['field'];
			$data['Event']['organization']	= $event['Event']['organization'];
			$data['Event']['start']			= $event['Event']['start'];
			$data['Event']['end']			= $event['Event']['end'];
			$data['Event']['place']			= $event['Event']['place'];
			
			foreach ( $event['EventTheme'] as $theme )
			{
				$data['EventTheme'][$theme['theme_id']]['id'] = 1;
			}
			$data['EventKeyword'] = $event['EventKeyword'];
			$this->Session->write('Event.Edit1', $data);
			$this->request->data = $data;
		}
		
		//テーマの一覧
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
	}

	// 2ページ目 企画の詳細
	public function edit2($id = null) {
		$this->Session->write('page', 2);
		
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$event = $this->Event->find('first', array(
			'contain' => array(
			),
			'conditions' => array(
				'Event.id' => $id
			),
		));
		$this->set('event', $event);
		
		if( $this->request->is('post') )
		{
			$this->request->data['Event']['id']					= $event['Event']['id'];
			$this->request->data['Event']['latest_admin_id']	= $this->Auth->user('id');
			if ($this->Event->save($this->request->data) )
			{
				$this->Session->setFlash('企画の概要を更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('企画の概要の更新に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $event;
		}
		$options1 = array(
			'1' => '有',
			'0' => '無',
		);
		$this->set('options1', $options1);
	}

	// 3ページ目 経費
	public function edit3($id = null) {
		$this->Session->write('page', 3);
		
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// 削除フラグのたったExpenseは取得しない
		$this->Event->hasMany['Expense']['conditions'] = array('Expense.is_delete' => 0);
		$event = $this->Event->find('first', array(
			'contain' => array(
				'Expense'
			),
			'conditions' => array(
				'Event.id' => $id
			),
		));
		$this->set('event', $event);
		
		// 課目ドロップダウン用データ
		$items = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0, 'Item.parent_id <>' => 0)));
		$items[0] = '----';
		ksort($items);
		$this->set('items', $items);
		
		if( $this->request->is('post') )
		{
			$errors = array();
			if ( !empty($this->request->data['Expense']) )
			{
				foreach ( $this->request->data['Expense'] as $key1 => $expenses )
				{
					
					foreach ( $expenses as $key2 => $expense )
					{
						$this->Expense->set($expense);
						if ( !$this->Expense->validates() )
						{
							$errors[$key1][$key2] = $this->Expense->validationErrors;
						}
					}
				}
			}
			$this->Expense->validationErrors = $errors;
			
			if ( empty($errors) )
			{
				// DB上のデータ
				$event_expense_ids = array();
				foreach ( $event['Expense'] as $_expense )
				{
					$event_expense_ids[] = $_expense['id'];
				}
				
				//postされたデータ
				$post_expense_ids = array();
				if ( !empty($this->request->data['Expense']) )
				{
					foreach ( $this->request->data['Expense'] as $_expenses )
					{
						foreach ( $_expenses as $_expense )
						{
							$post_expense_ids[] = $_expense['id'];
						}
					}
				}
				
				// 削除されたデータのID、あとで削除フラグを立てる
				$diffs = array_diff($event_expense_ids, $post_expense_ids);
				
				$rollback = false;
				$this->Expense->begin();
				
				$updates = array();
				$inserts = array();
				if ( !empty($this->request->data['Expense']) )
				{
					foreach ( $this->request->data['Expense'] as $type => $expenses )
					{
						foreach ( $expenses as $expense )
						{
							if ( $type != 4 )
							{
								$expense['item_id'] = $type;
							}
							
							if ( $expense['id'] > 0 )
							{
								// 更新
								$expense['event_id']	= $event['Event']['id'];
								$expense['type']		= $type;
								$expense['latest_admin_id']		= $this->Auth->user('id');
								$updates[] = $expense;
							}
							else
							{
								// 新規作成
								unset($expense['id']);
								if ( $this->_is_input($expense) )
								{
									$expense['event_id']	= $event['Event']['id'];
									$expense['type']		= $type;
									$expense['latest_admin_id']		= $this->Auth->user('id');
									$inserts[] = $expense;
								}
							}
						}
					}
					
					if ( !empty($updates) )
					{
						foreach ( $updates as $update )
						{
							$update['latest_admin_id']		= $this->Auth->user('id');
							$this->Expense->set($update);
							if ( !$this->Expense->save($update) )
							{
								$rollback = true;
								break;
							}
						}
					}
					
					if ( !empty($inserts) )
					{
						foreach ( $inserts as $insert )
						{
							if ( $this->_is_input($insert) )
							{
								if ( empty($insert['request_price']) )
								{
									$insert['request_price'] = 0;
								}
								if ( empty($insert['accept_price']) )
								{
									$insert['accept_price'] = 0;
								}
								if ( empty($insert['ask_price']) )
								{
									$insert['ask_price'] = 0;
								}
								$insert['admin_id']				= $this->Auth->user('id');
								$insert['latest_admin_id']		= $this->Auth->user('id');
								$this->Expense->create();
								if ( !$this->Expense->save($insert) )
								{
									$rollback = true;
									break;
								}
							}
						}
					}
				}
				
				if ( !empty($diffs) )
				{
					// 差分を削除
					foreach ( $diffs as $diff )
					{
						$save = array();
						$save['Expense']['id']			= $diff;
						$save['Expense']['is_delete']	= 1;
						$save['Expense']['latest_admin_id']		= $this->Auth->user('id');
						$this->Expense->set($save);
						if ( !$this->Expense->save($save) )
						{
							$rollback = true;
							break;
						}
						
					}
				}
				
				if ( !$rollback )
				{
					$this->Expense->commit();
					$this->Session->setFlash('経費を更新しました。', 'Flash/success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->Expense->rollback();
					$this->Session->setFlash('経費の更新に失敗しました。', 'Flash/error');
				}
			}
		}
		else
		{
			$data = array();
			if ( !empty($event['Expense']) )
			{
				foreach ( $event['Expense'] as $expense )
				{
					$data['Expense'][$expense['type']][] = $expense;
				}
			}
			$this->request->data = $data;
		}
		$this->set('expense_status', Configure::read('App.expense_status'));
	}

	// 4ページ目 参加について
	public function edit4($id = null) {
		
		$this->Session->write('page', 4);
		
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$event = $this->Event->find('first', array(
			'contain' => array(
			),
			'conditions' => array(
				'Event.id' => $id
			),
		));
		$this->set('event', $event);
		
		if( $this->request->is('post') )
		{
			$this->request->data['Event']['id']					= $event['Event']['id'];
			$this->request->data['Event']['latest_admin_id']	= $this->Auth->user('id');
			if ( $this->Event->save($this->request->data) )
			{
				$this->Session->setFlash('参加についてを更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('参加についての更新に失敗しました。', 'Flash/error');
			}
			
		}
		else
		{
			$this->request->data = $event;
		}
		
		
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		$this->set('options3', array('1' => '有', '0' => '無'));
	}

	// 5ページ目 責任者
	public function edit5($id = null) {
		
		$this->Session->write('page', 5);
		
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$this->Event->hasMany['EventManager']['conditions'] = array('EventManager.is_delete' => 0);
		$this->Event->hasMany['EventAffair']['conditions'] = array('EventAffair.is_delete' => 0);
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventManager',
				'EventAffair',
			),
			'conditions' => array(
				'Event.id' => $id
			),
		));
		$this->set('event', $event);
		
		// バリデーションの設定を避難
		$event_manager_validate	= $this->EventManager->validate;
		$event_affair_validate	= $this->EventAffair->validate;
		
		// 途中保存時はバリデーションを外す
		$this->EventManager->validate = array();
		$this->EventAffair->validate = array();
		
		if( $this->request->is('post') )
		{
			if ( isset($this->request->data['update']) )
			{
				$rollback = false;
				$this->EventManager->begin();
				// EventManagerテーブルに挿入
				// 運営責任者
				if ( !$rollback )
				{
					if ( !empty($this->request->data['EventManager']) )
					{
						$this->EventManager->validate = array();
						foreach ( $this->request->data['EventManager'] as $manager )
						{
							$event_manager['EventManager'][] = $manager;
							
							$save = array();
							$save['EventManager'] = $manager;
							$save['EventManager']['event_id'] = $id;
							
							if ( empty($save['EventManager']['id']) )
							{
								$save['EventManager']['id'] = 0;
							}
							
							if ( $save['EventManager']['id'] > 0 )
							{
								$this->EventManager->set($save);
							}
							else
							{
								$this->EventManager->create();
							}
							if ( !$this->EventManager->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				// 事務担当者
				if ( !$rollback )
				{
					if ( !empty($this->request->data['EventAffair']) )
					{
						$this->EventAffair->validate = array();
						foreach ( $this->request->data['EventAffair'] as $manager )
						{
							$event_manager['EventAffair'][] = $manager;
							
							$save = array();
							$save['EventAffair'] = $manager;
							$save['EventAffair']['event_id'] = $id;
							
							if ( empty($save['EventAffair']['id']) )
							{
								$save['EventAffair']['id'] = 0;
							}
							
							if ( $save['EventAffair']['id'] > 0 )
							{
								$this->EventAffair->set($save);
							}
							else
							{
								$this->EventAffair->create();
							}
							if ( !$this->EventAffair->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->EventManager->commit();
					$this->Session->setFlash('責任者を更新しました。', 'Flash/success');
					$this->redirect(array('action' => 'edit5', $id));
				}
				else
				{
					$this->EventManager->rollback();
					$this->Session->setFlash('責任者の更新に失敗しました。', 'Flash/error');
				}
				
			}
			// 運営責任者を増やすボタン
			else if ( isset($this->request->data['manager']) )
			{
				if ( !empty($this->request->data['EventManager']) )
				{
					$next = count($this->request->data['EventManager']);
					
					$this->request->data['EventManager'][$next] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				else
				{
					// 通常ありえないが万が一、一つもない場合
					$this->request->data['EventManager'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
			}
			// 事務担当者を増やすボタン押下時
			else if ( isset($this->request->data['affair']) )
			{
				if ( !empty($this->request->data['EventAffair']) )
				{
					$next = count($this->request->data['EventAffair']);
					
					$this->request->data['EventAffair'][$next] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				else
				{
					// 通常ありえないが万が一、一つもない場合
					$this->request->data['EventAffair'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
			}
		}
		else
		{
			// 初期表示
			$data = $this->Event->find('first', array(
				'contain' => array(
					'EventManager',
					'EventAffair',
				),
				'conditions' => array(
					'Event.id' => $id,
				),
			));
			$this->request->data = $data;
		}
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// 7ページ目 応募完了画面
	public function edit_complete($id = null) {
		
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$data = $this->Session->read('Event');
		
		if ( !empty($data) )
		{
			$rollback = false;
			$this->Event->begin();
			
			// Eventテーブル挿入用
			$event['Event'] = $data['Edit1']['Event'];
			$event['Event'] += $data['Edit2']['Event'];
			$event['Event'] += $data['Edit4']['Event'];
			
			$event['Event']['id'] = $id;
			
			if ( !$this->Event->save($event) )
			{
				$rollback = true;
			}
			
			// EventThemeテーブル挿入用
			$event_theme['EventTheme'] = $data['Edit1']['EventTheme'];
			if ( !$rollback )
			{
				// updateではなく全削除、全挿入する
				// 既存データから該当するデータを削除
				if( !$this->EventTheme->deleteAll(array('EventTheme.event_id' => $event['Event']['id']), false) )
				{
					$rollback = true;
				}
				
				if( !$rollback )
				{
					foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
					{
						if( $theme['id'] == 1)
						{
							$save = array();
							$save['EventTheme']['event_id'] = $event['Event']['id'];
							$save['EventTheme']['theme_id'] = $theme_id;
							$this->EventTheme->create();
							if ( !$this->EventTheme->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
			}
			
			// Expenseテーブル挿入用
			$expense['Expense'] = $data['Edit3']['Expense'];
			if ( !$rollback )
			{
				// updateではなく全削除、全挿入する
				// 既存データから該当するデータを削除
				if( !$this->Expense->deleteAll(array('Expense.event_id' => $event['Event']['id']), false) )
				{
					$rollback = true;
				}
				
				foreach ( $expense['Expense'] as $type => $expenses )
				{
					foreach ( $expenses as $expense )
					{
						$expense['event_id']	= $event['Event']['id'];
						$expense['type']		= $type;
						$this->Expense->create();
						if ( !$this->Expense->save($expense) )
						{
							$rollback = true;
							break;
						}
					}
				}
			}
			
			// EventManagerテーブル挿入用
			// 運営責任者
			$data['Edit5']['EventManager']['type'] = 1;
			$event_manager['EventManager'][] = $data['Edit5']['EventManager'];
			
			// その他の運営責任者
			if ( !empty($data['Edit5']['EventSubManager']) )
			{
				foreach ( $data['Edit5']['EventSubManager'] as $sub_manager )
				{
					$sub_manager['type'] = 2; 
					$event_manager['EventManager'][] = $sub_manager;
				}
			}
			
			// 事務担当者
			$data['Edit5']['EventAffair']['type'] = 3; 
			$event_manager['EventManager'][] = $data['Edit5']['EventAffair'];
			
			// EventManagerテーブルに挿入
			if ( !$rollback )
			{
				if ( !empty($event_manager) )
				{
					foreach ( $event_manager['EventManager'] as $manager )
					{
						$manager['event_id'] = $event['Event']['id'];
						
						// ここではバリデーションしない
						$this->EventManager->validate = array();
						if ( empty($manager['id']) )
						{
							$this->EventManager->create();
						}
						if ( !$this->EventManager->save($manager) )
						{
							$rollback = true;
							break;
						}
					}
				}
			}
			
			if ( !$rollback )
			{
				// 成功時
				// ここでコミットする
				$this->Event->commit();
				
				// セッションから入力データを削除
				$this->Session->destroy('Event.Edit1');
				$this->Session->destroy('Event.Edit2');
				$this->Session->destroy('Event.Edit3');
				$this->Session->destroy('Event.Edit4');
				$this->Session->destroy('Event.Edit5');
				
				$this->Session->setFlash('データの更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// 失敗時
				$this->Event->rollback();
				$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				$this->redirect(array('action' => 'edit1'));
			}
		}
	}

	// 研究者用データ
	function researchers ()
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
			$this->request->data['Search']['affiliation']	= '';
			$this->request->data['Search']['lastname']		= '';
			$this->request->data['Search']['firstname']		= '';
		}
		
		$conditions = array();
		$conditions += array('Expense.is_delete' => 0);
		$conditions += array(
			'OR' => array(
				'0' => array('Expense.type' => 1),
				'1' => array('Expense.type' => 2),
			)
		);
		
		if ( isset($this->request->data['Search']['affiliation']) && !empty($this->request->data['Search']['affiliation']) )
		{
			$conditions += array('Expense.affiliation'	=> trim($this->request->data['Search']['affiliation']));
		}
		
		if ( isset($this->request->data['Search']['lastname']) && !empty($this->request->data['Search']['lastname']) )
		{
			$conditions[] = array('Expense.lastname'	=> trim($this->request->data['Search']['lastname']));
		}
		
		if ( isset($this->request->data['Search']['firstname']) && !empty($this->request->data['Search']['firstname']) )
		{
			$conditions[] = array('Expense.firstname'	=> trim($this->request->data['Search']['firstname']));
		}
		
		$this->modelClass = "Expense";
		$this->paginate = array(
			'contain' => array(
				'Event'
			),
			'conditions' => $conditions,
		);
		$expenses = $this->paginate();
		$this->set('expenses', $expenses);
	}

	// 研究集会用データ
	function meetings ()
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
			$this->request->data['Search']['status']		= 4;
		}
		
		$conditions = array();
		$conditions[] = array('Event.is_delete' => 0);
		
		// 種別
		if ( isset($this->request->data['Search']['event_type']) && !empty($this->request->data['Search']['event_type']) )
		{
			$conditions[] = array('Event.type'	=> $this->request->data['Search']['event_type']);
		}
		
		// ステータス
		if ( isset($this->request->data['Search']['status']) && $this->request->data['Search']['status'] != '-1' )
		{
			$conditions[] = array('Event.status'	=> $this->request->data['Search']['status']);
		}
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array(
				'OR' => array(
					0 => array('Event.event_number LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%'),
					1 => array('Event.title LIKE'			=> '%' . trim($this->request->data['Search']['keyword']) . '%')
				)
			);
		}
		
		// 開始日
		if ( isset($this->request->data['Search']['start']) && !empty($this->request->data['Search']['start']) )
		{
			$conditions[] = array('Event.start >= ?'		=> $this->request->data['Search']['start']);
		}
		
		// 終了日
		if ( isset($this->request->data['Search']['end']) && !empty($this->request->data['Search']['end']) )
		{
			$conditions[] = array('Event.end <= ?'		=> $this->request->data['Search']['end']);
		}
		
		$this->modelClass = "Event";
		$this->paginate = array(
			'contain' => array(
				'Expense'
			),
			'conditions' => $conditions,
		);
		$events = $this->paginate();
		$this->set('events', $events);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		//企画ステータスの一覧
		$event_status = Configure::read('App.event_status');
		$event_status[-1] = '------';
		ksort($event_status);
		$this->set('event_status', $event_status);
	}

	// 口演課題用データ
	function reports ()
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
			$this->request->data['Search']['keyword']		= '';
		}
		
		$conditions = array();
		$conditions[] = array('Event.is_delete' => 0);
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array(
				'OR' => array(
					0 => array('Event.event_number LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%'),
					1 => array('Event.program LIKE'			=> '%' . trim($this->request->data['Search']['keyword']) . '%')
				)
			);
		}
		
		
		$this->modelClass = "Event";
		$this->paginate = array(
			'contain' => array(
			),
			'conditions' => $conditions,
			'order' => 'Event.id ASC'
		);
		$events = $this->paginate();
		$this->set('events', $events);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		//企画ステータスの一覧
		$event_status = Configure::read('App.event_status');
		$event_status[-1] = '------';
		ksort($event_status);
		$this->set('event_status', $event_status);
	}

	// 研究機関用データ
	function organizations ()
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
			$this->request->data['Search']['keyword']		= '';
		}
		
		$conditions = array();
		$conditions[] = array('Event.is_delete' => 0);
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array('Event.organization'			=> trim($this->request->data['Search']['keyword']) );
		}
		
		$this->modelClass = "Event";
		$this->paginate = array(
			'contain' => array(
				'Expense'
			),
			'conditions' => $conditions,
		);
		$events = $this->paginate();
		$this->set('events', $events);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		//企画ステータスの一覧
		$event_status = Configure::read('App.event_status');
		$event_status[-1] = '------';
		ksort($event_status);
		$this->set('event_status', $event_status);
	}

	// 研究会場用データ
	function venues ()
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
		$conditions[] = array('Event.is_delete' => 0);
		
		// 種別
		if ( isset($this->request->data['Search']['event_type']) && !empty($this->request->data['Search']['event_type']) )
		{
			$conditions[] = array('Event.type'	=> $this->request->data['Search']['event_type']);
		}
		
		// ステータス
		if ( isset($this->request->data['Search']['status']) && $this->request->data['Search']['status'] != '-1' )
		{
			$conditions[] = array('Event.status'	=> $this->request->data['Search']['status']);
		}
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array(
				'OR' => array(
					0 => array('Event.event_number'		=> trim($this->request->data['Search']['keyword'])),
					1 => array('Event.title'			=> trim($this->request->data['Search']['keyword']))
				)
			);
		}
		
		// 開始日
		if ( isset($this->request->data['Search']['start']) && !empty($this->request->data['Search']['start']) )
		{
			$conditions[] = array('Event.start >= ?'		=> $this->request->data['Search']['start']);
		}
		
		// 終了日
		if ( isset($this->request->data['Search']['end']) && !empty($this->request->data['Search']['end']) )
		{
			$conditions[] = array('Event.end <= ?'		=> $this->request->data['Search']['end']);
		}
		
		$this->modelClass = "Event";
		$this->paginate = array(
			'contain' => array(
				'Expense'
			),
			'conditions' => $conditions,
		);
		$events = $this->paginate();
		$this->set('events', $events);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		//企画ステータスの一覧
		$event_status = Configure::read('App.event_status');
		$event_status[-1] = '------';
		ksort($event_status);
		$this->set('event_status', $event_status);
	}

	// 研究事例用データ
	function cases ()
	{
	}
	
	/**********************************************************
	 * 報告書
	 */
	// 報告書一覧
	function report_list ()
	{
		$this->modelClass = 'Event';
		$this->paginate = array(
			'contain' => array(
				'EventProgram' => array(
					'EventPerformer'
				),
				'EventFile'
			),
			'conditions' => array(
				'Event.status >=' => 4
			)
		);
		$events = $this->paginate();
		$this->set('events', $events);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		//企画ステータスの一覧
		$this->set('event_status', Configure::read('App.event_status'));
	}

	// 1ページ目 報告書の概要
	public function report_add1($id = null) {
		
		// データの存在確認
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$event_id = $id;
		
		// 企画データの取得
		$this->Event->hasMany['EventTheme']['conditions']	= array('EventTheme.is_delete' => 0);
		$this->Event->hasMany['EventManager']['conditions']	= array('EventManager.is_delete' => 0);
		$this->Event->hasMany['EventAffair']['conditions']	= array('EventAffair.is_delete' => 0);
		
		// バリデーションの設定を避難
		$event_manager_validate	= $this->EventManager->validate;
		$event_affair_validate	= $this->EventAffair->validate;
		
		// 途中保存時はバリデーションを外す
		$this->EventManager->validate = array();
		$this->EventAffair->validate = array();
		
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme',
				'EventKeyword',
				'EventManager',
				'EventAffair',
			),
			'conditions' => array(
				'Event.is_delete' => 0,
				'Event.id' => $event_id
			)
		));
		$this->set('event', $event);
		
		
		// POST時
		if( $this->request->is('post') )
		{
			/// 一時保存ボタン押下時
			if ( isset($this->request->data['save']) )
			{
				
				$rollback = false;
				$this->Event->begin();
				
				// Eventテーブルの保存
				$this->request->data['Event']['id'] = $event_id;
				if ( !$this->Event->event_update($this->request->data)) 
				{
					// 保存失敗ロールバックフラグを立て、処理を止める
					$rollback = true;
				}
				
				// 保存失敗していなければ
				if ( !$rollback )
				{
					// EventeThemeの更新
					
					// 既存DBのIDのみ集める
					$event_theme_ids = array();
					foreach ( $event['EventTheme'] as $event_theme )
					{
						$event_theme_ids[] = $event_theme['theme_id'];
					}
					
					
					// POSTされたテーマのIDを集める
					$post_theme_ids_buff = array();
					foreach ( $this->request->data['EventTheme'] as $theme_id => $post_theme )
					{
						// チェックが付いている場合のみ
						if ( $post_theme['id'] == 1 )
						{
							$post_theme_ids_buff[] = $theme_id;
						}
					}
					
					$post_theme_ids = array();
					if ( !empty($post_theme_ids_buff) )
					{
						foreach ( $post_theme_ids_buff as $post_theme_id )
						{
							$theme = $this->Theme->find('first', array(
								'contain' => array(),
								'conditions' => array(
									'Theme.is_delete' => 0,
									'Theme.id' => $post_theme_id
								)
							));
							$post_theme_ids[] = $theme['Theme']['id'];
						}
					}
					
					// チェックを外された差分（削除フラグを立てる）
					$event_theme_deletes = array_diff($event_theme_ids, $post_theme_ids);
					
					// 新たにチェックをつけた差分（挿入する）
					$event_theme_inserts = array_diff( $post_theme_ids, $event_theme_ids);
					
					
					// EventThemeの削除処理
					if ( !empty($event_theme_deletes) )
					{
						foreach ( $event_theme_deletes as $event_theme_id )
						{
							$save = array();
							$save['EventTheme']['id']			= $event_theme_id;
							$save['EventTheme']['is_delete']	= 1;
							
							// ループ中にupdateを行う場合は、setを使い、必ずModelにデータを渡すこと
							$this->EventTheme->set($save);
							if ( !$this->EventTheme->save($save) )
							{
								// 保存失敗ロールバックフラグを立て、処理を止める
								$rollback = true;
								break;
							}
						}
					}
					
					
					// EventTheme挿入処理
					if ( !empty($event_theme_inserts) )
					{
						foreach ( $event_theme_inserts as $event_theme_id )
						{
							$save = array();
							$save['EventTheme']['event_id']		= $event_id;
							$save['EventTheme']['theme_id']		= $event_theme_id;
							
							// ループ中にinsertを行う場合は、createを使いDBのIDを作成する
							$this->EventTheme->create();
							if ( !$this->EventTheme->save($save) )
							{
								// 保存失敗ロールバックフラグを立て、処理を止める
								$rollback = true;
								break;
							}
						}
					}
				}
				
				// EventKeywordの更新
				if ( !$rollback )
				{
					if ( !empty($this->request->data['EventKeyword']) )
					{
						foreach ( $this->request->data['EventKeyword'] as $event_keyword )
						{
							// キーワードが入力されている場合のみ挿入 or 更新
							if ( !empty($event_keyword['title']) )
							{
								$save = array();
								$save['EventKeyword']['event_id']	= $event_id;
								$save['EventKeyword']['title']		= trim($event_keyword['title']);
								if ( !empty($event_keyword['id']) )
								{
									// IDが存在すれば既存データと見なしupdate
									$save['EventKeyword']['id'] = $event_keyword['id'];
									$this->EventKeyword->set($save);
								}
								else
								{
									// IDが存在しなければ新規データと見なしinsert
									$this->EventKeyword->create();
								}
								
								if ( !$this->EventKeyword->save($save) )
								{
									// 保存失敗ロールバックフラグを立て、処理を止める
									$rollback = true;
									break;
								}
							}
						}
					}
				}
				
				// EventManagerの更新
				if ( !$rollback )
				{
					foreach ( $this->request->data['EventManager'] as $key =>  $event_manager )
					{
						$event_manager['event_id'] = $event_id;
						if ( empty($event_manager['id']) )
						{
							// IDが空であればinsert
							
							if ( $event_manager['is_delete'] == 1 )
							{
								// DBに存在せず、セッションのみ存在するデータに削除チェックされた場合
								unset($this->request->data['EventManager'][$key]);
							}
							else
							{
								$this->EventManager->create();
								if ( !$this->EventManager->save($event_manager) )
								{
									// 保存失敗
									// 処理を止めてrollback フラグを立てる
									$rollback = true;
									break;
								}
							}
						}
						else
						{
							// IDが空でなければupdate
							$this->EventManager->set($event_manager);
							if ( !$this->EventManager->save($event_manager) )
							{
								// 保存失敗
								// 処理を止めてrollback フラグを立てる
								$rollback = true;
								break;
							}
						}
					}
				}
				
				// EventAffairの更新
				if ( !$rollback )
				{
					foreach ( $this->request->data['EventAffair'] as $key => $event_affair )
					{
						$event_affair['event_id'] = $event_id;
						if ( empty($event_affair['id']) )
						{
							// IDが空であればinsert
							
							if ( isset($event_affair['is_delete']) && $event_affair['is_delete'] == 1 )
							{
								// DBに存在せず、セッションのみ存在するデータに削除チェックされた場合
								unset($this->request->data['EventAffair'][$key]);
							}
							else
							{
								$this->EventManager->create();
								if ( !$this->EventAffair->save($event_affair) )
								{
									// 保存失敗
									// 処理を止めてrollback フラグを立てる
									$rollback = true;
									break;
								}
							}
						}
						else
						{
							// IDが空でなければupdate
							$this->EventAffair->set($event_affair);
							if ( !$this->EventAffair->save($event_affair) )
							{
								// 保存失敗
								// 処理を止めてrollback フラグを立てる
								$rollback = true;
								break;
							}
						}
					}
				}
			
				if ( !$rollback )
				{
					// 成功時
					// ここでコミットする
					$this->Event->commit();
					$this->Session->setFlash('入力データを更新しました。', 'Flash/success');
					$this->redirect(array('action' => 'report_add1', $id));
				}
				else
				{
					// 失敗時
					$this->Event->rollback();
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			// 運営責任者を増やすボタン押下時
			else if ( isset($this->request->data['manager']) )
			{
				if ( !empty($this->request->data['EventManager']) )
				{
					$next = count($this->request->data['EventManager']);
					
					$this->request->data['EventManager'][$next] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				else
				{
					// 通常ありえないが万が一、一つもない場合
					$this->request->data['EventManager'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
			}
			// 事務担当者を増やすボタン押下時
			else if ( isset($this->request->data['affair']) )
			{
				if ( !empty($this->request->data['EventAffair']) )
				{
					$next = count($this->request->data['EventAffair']);
					
					$this->request->data['EventAffair'][$next] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				else
				{
					// 通常ありえないが万が一、一つもない場合
					$this->request->data['EventAffair'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
			}
		}
		else
		{
			// 初期表示
			// EventTheme
			if ( !empty($event['EventTheme']) )
			{
				foreach ( $event['EventTheme'] as $event_theme )
				{
					$event['EventTheme'][$event_theme['theme_id']]['id'] = 1;
				}
			}
			
			// 空の入力枠を１つ用意
			if ( empty($event['EventManager']) )
			{
				$event['EventManager'][0] = array(
					'id'				=> '',
					'email'				=> '',
					'lastname'			=> '',
					'firstname'			=> '',
					'lastname_kana'		=> '',
					'firstname_kana'	=> '',
					'organization'		=> '',
					'department'		=> '',
					'job_title'			=> '',
					'url'				=> '',
					'zip'				=> '',
					'prefecture_id'		=> 0,
					'city'				=> '',
					'address'			=> '',
					'tel'				=> '',
					'fax'				=> ''
				);
			}
			
			// 空の入力枠を１つ用意
			if ( empty($event['EventAffair']) )
			{
				$event['EventAffair'][0] = array(
					'id'				=> '',
					'email'				=> '',
					'lastname'			=> '',
					'firstname'			=> '',
					'lastname_kana'		=> '',
					'firstname_kana'	=> '',
					'organization'		=> '',
					'department'		=> '',
					'job_title'			=> '',
					'url'				=> '',
					'zip'				=> '',
					'prefecture_id'		=> 0,
					'city'				=> '',
					'address'			=> '',
					'tel'				=> '',
					'fax'				=> ''
				);
			}
			$this->request->data = $event;
		}
		
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}
	
	// 2ページ目 報告書のプログラム
	public function report_add2($id = null)
	{
		// データの存在確認
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$event_id =  $id;
		$event = $this->Event->find('first', array(
			'contain' => array(
			),
			'conditions' => array(
				'Event.id' => $event_id,
			)
		));
		$this->set('event', $event);
		
		if ( $this->request->is('post') )
		{
			// 保存ボタン押下時
			if ( isset($this->request->data['save']) )
			{
				$this->request->data['Event']['id'] = $event_id;
				if ( $this->Event->save($this->request->data) )
				{
					$this->Session->setFlash('入力データを更新しました。', 'Flash/success');
					$this->redirect(array('action' => 'report_add2', $id));
				}
				else
				{
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			else
			{
				// 更新以外のボタンを押下時
				foreach ($this->request->data['EventProgram']  as $key1 => $event_programs )
				{
					foreach ( $event_programs as $key2 => $event_program )
					{
						// 講演を追加の場合
						if ( isset($event_program['add-program']) )
						{
							$program_count = count($this->request->data['EventProgram'][$key1]) + 1;
							$this->request->data['EventProgram'][$key1][] = array(
								'id'		=> 0,
								'event_id'	=> $event_id,
								'title'		=> '',
								'date'		=> date('Y-m-d',  strtotime($key1)),
								'sort'		=> $program_count,
								'EventPerformer' => array(
									0 => array(
										'id'				=> 0,
										'event_program_id'	=> 0,
										'organization'		=> '',
										'role'				=> '',
										'lastname'			=> '',
										'firstname'			=> '',
										'is_delete'			=> 0
									)
								)
							);
						}
						// 講演者を追加の場合
						else if ( isset($event_program['add-performer']) )
						{
							 $this->request->data['EventProgram'][$key1][$key2]['EventPerformer'][] = array(
								'id'				=> 0,
								'event_program_id'	=> 0,
								'organization'		=> '',
								'role'				=> '',
								'lastname'			=> '',
								'firstname'			=> '',
								'is_delete'			=> 0
							);
						}
						// 講演を削除の場合
						if ( isset($event_program['delete-program']) )
						{
							// DBに保存済み
							if ( !empty($event_program['id']) && is_numeric($event_program['id']) )
							{
								$event_program['is_delete']  = 1;
								if ( $this->EventProgram->save($event_program) )
								{
									
								}
							}
							unset($this->request->data['EventProgram'][$key1][$key2]);
						}
						else
						{
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
						}
					}
				}
			}
		}
		else
		{
			// 初期表示
			if ( !empty($event['EventProgram']) )
			{
				$i=0;
				$report_programs_buff = array();
				foreach ( $event['EventProgram'] as $key => $report_program )
				{
					$date = date('Ymd', strtotime($report_program['date']));
					$report_programs_buff[$date][$i] = $report_program;
					$i++;
				}
			}
			else
			{
				// 開始日と終了日から開催期間を取得し、その日数分ループ用の配列を作成
				$diff = ((strtotime($event['Event']['end']) - strtotime($event['Event']['start'])) / 60 / 60 / 24) + 1;
				$report_programs_buff = array();
				for ( $i = 0; $i < $diff; $i++ )
				{
					$day = date('Ymd', strtotime($event['Event']['start']) + ($i * 86400));
					$report_programs_buff[$day] = array();
				}
				
				foreach ( $report_programs_buff as $key1 =>  $date )
				{
					$report_programs_buff[$key1][] = array(
						'id'		=> 0,
						'event_id'	=> $event_id,
						'title'		=> '',
						'date'		=> date('Y-m-d',  strtotime($key1)),
						'sort'		=> 1,
						'EventPerformer' => array(
							0 => array(
								'id'				=> 0,
								'event_program_id'	=> 0,
								'organization'		=> '',
								'role'				=> '',
								'lastname'			=> '',
								'firstname'			=> '',
								'is_delete'			=> 0
							)
						)
					);
				}
			}
			
			$event['EventProgram'] = $report_programs_buff;
			$buff = $event;
			
			$this->request->data = $buff;
		}
	}
	
	// 3ページ目　添付ファイル
	function report_add3 ($id = null)
	{
		// データの存在確認
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$event_id = $id;
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventFile'
			),
			'conditions' => array(
				'Event.id' => $id
			)
		));
		$this->set('event', $event);
		
		if ( $this->request->is('post') || $this->request->is('put') )
		{
			//一時保存 or 確認画面
			if ( isset($this->request->data['save']) )
			{
				$rollback = false;
				$this->EventFile->begin();
				
				foreach ( $this->request->data['EventFile'] as $event_file  )
				{
					if ( isset($event_file['file']['name']) && !empty($event_file['file']['name']) )
					{
						if ( !empty($event_file['id']) && is_numeric($event_file['id']) )
						{
							// 更新
							$this->EventFile->set($event_file);
						}
						else
						{
							// 新規
							$this->EventFile->create();
						}
						if ( !$this->EventFile->save($event_file) )
						{
							$rollback = true;
						 	break;
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->EventFile->commit();
					//一時保存
					$this->Session->setFlash('入力データを一時保存しました。', 'Flash/success');
					$this->redirect(array('action' => 'report_add3', $id));
				}
				else
				{
					$this->EventFile->rollback();
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			else if (isset($this->request->data['add_event_file']))
			{
				$this->request->data['EventFile'][] = array(
					'id'		=> 0,
					'event_id'	=> $event_id,
					'name'		=> '',
					'file'		=> '',
					'file_org'	=> '',
				);
			}
		}
		else
		{
			// 初期表示
			$buff = array();
			if ( !empty($event['EventFile']) )
			{
				$diff = 3 - count($event['EventFile']);
				
				if ( $diff > 0 )
				{
					for ( $i = 1; $i <= $diff; $i++ )
					{
						$event['EventFile'][] = array(
							'id'		=> 0,
							'event_id'	=> $event_id,
							'name'		=> '',
							'file'		=> '',
							'file_org'	=> '',
						);
					}
				}
				$buff['EventFile'] = $event['EventFile'];
			}
			else
			{
				$buff['EventFile'] = array(
					0 => array(
						'id'		=> 0,
						'event_id'	=> $event_id,
						'name'		=> '',
						'file'		=> '',
						'file_org'	=> '',
					),
					1 => array(
						'id'		=> 0,
						'event_id'	=> $event_id,
						'name'		=> '',
						'file'		=> '',
						'file_org'	=> '',
					),
					2 => array(
						'id'		=> 0,
						'event_id'	=> $event_id,
						'name'		=> '',
						'file'		=> '',
						'file_org'	=> '',
					),
				);
			}
			$this->request->data = $buff;
		}
	}

	// 添付ファイル削除
	public function file_delete($id = null)
	{
		if ( empty($id) )
		{
			$this->Session->setFlash('Invalid ID', 'Flash/error');
			$this->redirect(array('action' => 'report_list'));
		}
		
		$event_file = $this->EventFile->find('first', array(
			'contain' => array(
				'Event'
			),
			'conditions' => array(
				'EventFile.id' => $id
			)
		));
		
		if ( !$this->EventFile->delete($id) )
		{
			$this->Session->setFlash('添付ファイルの削除に失敗しました。管理者にお問合わせください。', 'Flash/error');
		}
		else
		{
			$this->Session->setFlash('添付ファイルを削除しました。', 'Flash/success');
		}
		$this->redirect(array('action' => 'report_add3', $event_file['Event']['id']));
	}

	// 講演課題用データ作成
	public function event_program_add($id = null)
	{
		
		$this->EventProgram->validate = array();
		$this->EventPerformer->validate = array();
		
		// データの存在確認
		$this->Event->id = $id;
		if ( !$this->Event->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$event_id =  $id;
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventProgram' => array(
					'EventPerformer'
				)
			),
			'conditions' => array(
				'Event.id' => $event_id,
			)
		));
		$this->set('event', $event);
		
		if ( $this->request->is('post') )
		{
			// 保存ボタン押下時
			if ( isset($this->request->data['save']) )
			{
				$this->request->data['Event']['id'] = $event_id;
				
				$rollback = false;
				$this->EventProgram->begin();
				
				if ( !empty($this->request->data['EventProgram']) )
				{
					foreach ( $this->request->data['EventProgram'] as $key1 => $event_program )
					{
						if ( $event_program['id'] == 0 )
						{
							// 新規
							$this->EventProgram->create();
							if ( $this->EventProgram->save($event_program) )
							{
								// 保存成功
								// 講演者を登録
								$event_program_id = $this->EventProgram->getLastInsertID();
								
								if ( !empty( $event_program['EventPerformer'] ) )
								{
									foreach ( $event_program['EventPerformer'] as $event_performer )
									{
										$this->EventPerformer->create();
										$event_performer['event_program_id'] = $event_program_id;
										if ( !$this->EventPerformer->save($event_performer) )
										{
											$rollback = true;
											break;
										}
									}
								}
							}
							else
							{
								// 失敗
								$rollback = true;
								break;
							}
						}
						else
						{
							// 更新
							$this->EventProgram->set($event_program);
							if ( $this->EventProgram->save($event_program) )
							{
								// 保存成功
								// 講演者を登録
								$event_program_id = $event_program['id'];
								
								if ( !empty( $event_program['EventPerformer'] ) )
								{
									foreach ( $event_program['EventPerformer'] as $event_performer )
									{
										if ( $event_performer['id'] == 0 )
										{
											// 新規作成
											$this->EventPerformer->create();
											$event_performer['event_program_id'] = $event_program_id;
											if ( !$this->EventPerformer->save($event_performer) )
											{
												$rollback = true;
												break;
											}
										}
										else
										{
											// 更新
											$this->EventPerformer->set($event_performer);
											$event_performer['event_program_id'] = $event_program_id;
											if ( !$this->EventPerformer->save($event_performer) )
											{
												$rollback = true;
												break;
											}
										}
									}
								}
							}
							else
							{
								// 失敗
								$rollback = true;
								break;
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->EventProgram->commit();
					$this->Session->setFlash('入力データを更新しました。', 'Flash/success');
					$this->redirect(array('action' => 'event_program_add', $id));
				}
				else
				{
					$this->EventProgram->rollback();
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			else
			{
				// 更新以外のボタンを押下時
				// 講演を追加の場合
				if ( isset($this->request->data['add-program']) )
				{
					$program_count = count($this->request->data['EventProgram']) + 1;
					$this->request->data['EventProgram'][] = array(
						'id'		=> 0,
						'event_id'	=> $event_id,
						'title'		=> '',
						'date'		=> '',
						'sort'		=> $program_count,
						'EventPerformer' => array(
							0 => array(
								'id'				=> 0,
								'event_program_id'	=> 0,
								'organization'		=> '',
								'role'				=> '',
								'lastname'			=> '',
								'firstname'			=> '',
								'is_delete'			=> 0
							)
						)
					);
				}
				else
				{
					foreach ( $this->request->data['EventProgram'] as $key1 => $event_program )
					{
						
						// 講演を削除の場合
						if ( isset($this->request->data['EventProgram'][$key1]['delete-program']) )
						{
							// DBに保存済み
							if ( !empty($event_program['id']) && is_numeric($event_program['id']) )
							{
								$event_program['is_delete']  = 1;
								if ( $this->EventProgram->save($event_program) )
								{
									
								}
							}
							unset($this->request->data['EventProgram'][$key1]);
							break;
						}
						
						foreach ( $event_program['EventPerformer'] as $key2 => $event_performer )
						{
							// 講演者を追加の場合
							if ( isset($this->request->data['EventProgram'][$key1]['add-performer']) )
							{
								 $this->request->data['EventProgram'][$key1]['EventPerformer'][] = array(
									'id'				=> 0,
									'event_program_id'	=> 0,
									'organization'		=> '',
									'role'				=> '',
									'lastname'			=> '',
									'firstname'			=> '',
									'is_delete'			=> 0
								);
								break;
							}
							
							// 講演者を削除の場合
							else if ( isset($this->request->data['EventProgram'][$key1]['EventPerformer'][$key2]['delete-performer']) )
							{
								// DBに保存済み
								if ( !empty($event_performer['id']) && is_numeric($event_performer['id']) )
								{
									$event_performer['is_delete']  = 1;
									if ( $this->EventPerformer->save($event_performer) )
									{
										
									}
								}
								unset($this->request->data['EventProgram'][$key1]['EventPerformer'][$key2]);
								break;
							}
						}
					}
				}
			}
		}
		else
		{
			// 初期表示
			if ( !empty($event['EventProgram']) )
			{
				$i=0;
				$report_programs_buff = array();
				foreach ( $event['EventProgram'] as $key => $report_program )
				{
					$report_programs_buff[$i] = $report_program;
					$i++;
				}
			}
			else
			{
				$report_programs_buff[] = array(
					'id'		=> 0,
					'event_id'	=> $event_id,
					'title'		=> '',
					'date'		=> '',
					'sort'		=> 1,
					'EventPerformer' => array(
						0 => array(
							'id'				=> 0,
							'event_program_id'	=> 0,
							'organization'		=> '',
							'role'				=> '',
							'lastname'			=> '',
							'firstname'			=> '',
							'is_delete'			=> 0
						)
					)
				);
			}
			
			
			$event['EventProgram'] = $report_programs_buff;
			$buff = $event;
			
			$this->request->data = $buff;
		}
	}

	public function csv ()
	{
		if ( $this->request->is('post') )
		{
			$conditions = array(
				'Event.is_delete' => 0
			);
			
			// 開始日
			if ( isset($this->request->data['Search']['start']) && !empty($this->request->data['Search']['start']) )
			{
				$conditions[] = array('Event.start >= ?'		=> $this->request->data['Search']['start']);
			}
			
			// 終了日
			if ( isset($this->request->data['Search']['end']) && !empty($this->request->data['Search']['end']) )
			{
				$conditions[] = array('Event.end <= ?'		=> $this->request->data['Search']['end']);
			}
			
			// 0:企画申請中
			// 1:企画検討中
			// 2:企画承認済み
			// 3:報告書受付中
			// 4:報告書提出済み
			// 5:報告書承認（HPに表示）
			// 99:企画不採択
			
			$type = 0;
			if ( isset($this->request->data['event']) )
			{
				$conditions[] = array('Event.status >= ?'		=> 0);
				$conditions[] = array('Event.status <= ?'		=> 3);
			}
			else if ( isset($this->request->data['report']) )
			{
				$type = 1;
				$conditions[] = array('Event.status >= ?'		=> 4);
				$conditions[] = array('Event.status <= ?'		=> 5);
			}
			else
			{
				die('Invalid parameter.');
			}
			
			
			
			$this->layout = false;
			
			if ( $type == 0 )
			{
				$filename = 'event_' . date('YmdHis');
				
				//表の一行目を作成
				$th = array(
					'DB-ID',
					'集会等名称',
					'申請額合計（円）',
					'開催時期',
					'開催場所',
					'運営責任者',
					'主催機関',
					'集会等のタイプ',

					'趣旨・目的',
					'キーワード',
					'連携相手の分野・業界',
					'プログラム',
					'取り扱うテーマ・トピックや解決すべき課題',
					'考えられる数学・数理科学的アプローチ',
					'これまでの準備状況',
					'終了後のフォローアップの計画',
					'他機関等からの支援',
					'有の場合は支援元',
					
					'申請経費内訳（円）旅費',
					'申請経費内訳（円）諸謝金',
					'申請経費内訳（円）印刷製本費',
					'申請経費内訳（円）その他',

					'参加制限',
					'有の場合は参加資格',
					'参加申込',
					
					'運営責任者',

					'事務担当者',
					
					
					
					'旅費',
					'諸謝金',
					'印刷製本費',
					'その他',
					
				);
				
				
				
				$this->Event->hasMany['Expense']['conditions'] = array('Expense.is_delete' => 0);
				$this->Event->hasMany['EventManager']['conditions'] = array('EventManager.is_delete' => 0);
				$this->Event->hasMany['EventAffair']['conditions'] = array('EventAffair.is_delete' => 0);
				$events = $this->Event->find('all', array(
					'contain' => array(
						'EventTheme' => array(
							'Theme'
						),
						'EventKeyword',
						'EventManager',
						'EventAffair',
						'Expense',
					),
					'conditions' => $conditions,
				));
				
				
				//print_a_die($events);
				
				
				$options1 = array(
					'0' => '無',
					'1' => '有',
				);
				
				$options2 = array(
					'0' => '不要',
					'1' => '必要',
				);
				
				$data = array();
				foreach ( $events as $key => $event )
				{
					$data[$key]['Event']['id']			= $event['Event']['id'];
					$data[$key]['Event']['title']		= $event['Event']['title'];
					
					$total = 0;
					if ( !empty($event['Expense']) )
					{
						foreach ( $event['Expense'] as $expense )
						{
							$total += $expense['request_price'];
						}
					}
					$data[$key]['Event']['total']		= $total;
					$data[$key]['Event']['period']		= $event['Event']['start'] . '～' . $event['Event']['end'];
					$data[$key]['Event']['place']		= $event['Event']['place'];
					
					$manager_name = '';
					if ( isset($event['EventManager'][0]['lastname']) && isset($event['EventManager'][0]['firstname']) )
					{
						$manager_name = $event['EventManager'][0]['lastname'] . ' ' . $event['EventManager'][0]['firstname'];
					}
					$data[$key]['Event']['manager_name']	= $manager_name;
					
					$data[$key]['Event']['organization']	= $event['Event']['organization'];
					
					$theme = '';
					if ( !empty($event['EventTheme']) )
					{
						$theme = array();
						foreach ( $event['EventTheme'] as $tm )
						{
							$theme[] = $tm['Theme']['name'];
						}
						$theme = implode('、', $theme);
					}
					$data[$key]['Event']['theme']		= $theme;
					$data[$key]['Event']['purpose']		= $event['Event']['purpose'];
					
					$keyword = '';
					if ( !empty($event['EventKeyword']) )
					{
						$keyword = array();
						foreach ( $event['EventKeyword'] as $k )
						{
							$keyword[] = $k['title'];
						}
						$keyword = implode('、', $keyword);
					}
					$data[$key]['Event']['keyword']		= $keyword;
					$data[$key]['Event']['field']		= $event['Event']['field'];
					$data[$key]['Event']['program']		= $event['Event']['program'];
					$data[$key]['Event']['subject']		= $event['Event']['subject'];
					$data[$key]['Event']['approach']		= $event['Event']['approach'];
					$data[$key]['Event']['prepare']		= $event['Event']['prepare'];
					$data[$key]['Event']['follow']		= $event['Event']['follow'];
					$data[$key]['Event']['is_support']	= $options1[$event['Event']['is_support']];
					$data[$key]['Event']['support']		= $event['Event']['support'];
					
					
					$items = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0)));
					$items[0] = '----';
					ksort($items);
					
					$cost1 = 0;
					$cost2 = 0;
					$cost3 = 0;
					$cost4 = 0;
					
					$text1 = '';
					$text2 = '';
					$text3 = '';
					$text4 = '';
					if ( !empty($event['Expense']) )
					{
						foreach ( $event['Expense'] as $expense )
						{
							if ( $expense['type'] == 1 )
							{
								$cost1 += $expense['request_price'];
								
								foreach ( $expense as $k => $val )
								{
									if ( $k == 'item_id' && !empty($val) )
									{
									//	$text1 .= $items[$val] . " ";
									}
									if ( $k == 'affiliation' )
									{
										$text1 .= $val . " ";
									}
									else if ( $k == 'job' )
									{
										$text1 .= $val . " ";
									}
									else if ( $k == 'lastname' )
									{
										$text1 .= $val . " ";
									}
									else if ( $k == 'firstname' )
									{
										$text1 .= $val . " ";
									}
									else if ( $k == 'title' )
									{
										$text1 .= $val . " ";
									}
									else if ( $k == 'count' )
									{
										$text1 .= $val . " ";
									}
									else if ( $k == 'price' )
									{
										$text1 .= $val . " ";
									}
									else if ( $k == 'request_price' )
									{
										if ( empty($val) )
										{
											$val = 0;
										}
										
										$text1 .= number_format($val) . "円 ";
									}
									else if ( $k == 'note' )
									{
										$text1 .= $val . " ";
									}
									else
									{
										continue;
									}
								}
								$text1 .= "\r\n";
							}
							else if ( $expense['type'] == 2 )
							{
								$cost2 += $expense['request_price'];
								
								foreach ( $expense as $k => $val )
								{
									if ( $k == 'item_id' && !empty($val) )
									{
									//	$text2 .= $items[$val] . " ";
									}
									if ( $k == 'affiliation' )
									{
										$text2 .= $val . " ";
									}
									else if ( $k == 'job' )
									{
										$text2 .= $val . " ";
									}
									else if ( $k == 'lastname' )
									{
										$text2 .= $val . " ";
									}
									else if ( $k == 'firstname' )
									{
										$text2 .= $val . " ";
									}
									else if ( $k == 'title' )
									{
										$text2 .= $val . " ";
									}
									else if ( $k == 'count' )
									{
										$text2 .= $val . " ";
									}
									else if ( $k == 'price' )
									{
										$text2 .= $val . " ";
									}
									else if ( $k == 'request_price' )
									{
										if ( empty($val) )
										{
											$val = 0;
										}
										
										$text2 .= number_format($val) . "円 ";
									}
									else if ( $k == 'note' )
									{
										$text2 .= $val . " ";
									}
									else
									{
										continue;
									}
								}
								$text2 .= "\r\n";
							}
							else if ( $expense['type'] == 3 )
							{
								$cost3 += $expense['request_price'];
								
								foreach ( $expense as $k => $val )
								{
									if ( $k == 'item_id' && !empty($val) )
									{
									//	$text3 .= $items[$val] . " ";
									}
									if ( $k == 'affiliation' )
									{
										$text3 .= $val . " ";
									}
									else if ( $k == 'job' )
									{
										$text3 .= $val . " ";
									}
									else if ( $k == 'lastname' )
									{
										$text3 .= $val . " ";
									}
									else if ( $k == 'firstname' )
									{
										$text3 .= $val . " ";
									}
									else if ( $k == 'title' )
									{
										$text3 .= $val . " ";
									}
									else if ( $k == 'count' )
									{
										$text3 .= $val . " ";
									}
									else if ( $k == 'price' )
									{
										$text3 .= $val . " ";
									}
									else if ( $k == 'request_price' )
									{
										if ( empty($val) )
										{
											$val = 0;
										}
										
										$text3 .= number_format($val) . "円 ";
									}
									else if ( $k == 'note' )
									{
										$text3 .= $val . " ";
									}
									else
									{
										continue;
									}
								}
								$text3 .= "\r\n";
							}
							else if ( $expense['type'] == 4 )
							{
								$cost4 += $expense['request_price'];
								
								foreach ( $expense as $k => $val )
								{
									if ( $k == 'item_id' && !empty($val) )
									{
										$text4 .= $items[$val] . " ";
									}
									if ( $k == 'affiliation' )
									{
										$text4 .= $val . " ";
									}
									else if ( $k == 'job' )
									{
										$text4 .= $val . " ";
									}
									else if ( $k == 'lastname' )
									{
										$text4 .= $val . " ";
									}
									else if ( $k == 'firstname' )
									{
										$text4 .= $val . " ";
									}
									else if ( $k == 'title' )
									{
										$text4 .= $val . " ";
									}
									else if ( $k == 'count' )
									{
										$text4 .= $val . " ";
									}
									else if ( $k == 'price' )
									{
										$text4 .= $val . " ";
									}
									else if ( $k == 'request_price' )
									{
										if ( empty($val) )
										{
											$val = 0;
										}
										
										$text4 .= number_format($val) . "円 ";
									}
									else if ( $k == 'note' )
									{
										$text4 .= $val . " ";
									}
									else
									{
										continue;
									}
								}
								$text4 .= "\r\n";
							}
						}
					}
					
					
					
					$data[$key]['Event']['cost1']		= $cost1;
					$data[$key]['Event']['cost2']		= $cost2;
					$data[$key]['Event']['cost3']		= $cost3;
					$data[$key]['Event']['cost4']		= $cost4;
					
					$qualification = 0;
					if ( is_numeric($event['Event']['qualification']) )
					{
						$qualification = $event['Event']['qualification'];
					}
					$data[$key]['Event']['qualification']		= $options1[$qualification];
					$data[$key]['Event']['qualification_other']	= $event['Event']['qualification_other'];
					$data[$key]['Event']['qualification_apply']	= $options2[$event['Event']['qualification_apply']];
					
					$managers = '';
					if ( !empty($event['EventManager']) )
					{
						foreach ( $event['EventManager'] as $manager )
						{
							$managers .= '運営責任者ID:' . $manager['email'] . "\r\n";
							$managers .= '姓名:' . $manager['lastname'] . ' ' . $manager['firstname'] . "\r\n";
							$managers .= 'フリガナ:' . $manager['lastname_kana'] . ' ' . $manager['firstname_kana'] . "\r\n";
							$managers .= '所属機関:' . $manager['organization'] . "\r\n";
							$managers .= '所属部局:' . $manager['department'] . "\r\n";
							$managers .= '職名:' . $manager['job_title'] . "\r\n";
							$managers .= '郵便番号:' . $manager['zip'] . "\r\n";
							$managers .= '都道府県:' . $manager['prefecture_id'] . "\r\n";
							$managers .= '市区町村:' . $manager['city'] . "\r\n";
							$managers .= '住所:' . $manager['address'] . "\r\n";
							$managers .= 'TEL:' . $manager['tel'] . "\r\n";
							$managers .= 'FAX:' . $manager['fax'] . "\r\n";
							$managers .= 'URL:' . $manager['url'] . "\r\n";
							$managers .= "\r\n";
							$managers .= "\r\n";
						}
					}
					
					$affairs = '';
					if ( !empty($event['EventAffair']) )
					{
						foreach ( $event['EventAffair'] as $affair )
						{
							$affairs .= '事務担当者ID:' . $affair['email'] . "\r\n";
							$affairs .= '姓名:' . $affair['lastname'] . ' ' . $affair['firstname'] . "\r\n";
							$affairs .= 'フリガナ:' . $affair['lastname_kana'] . ' ' . $affair['firstname_kana'] . "\r\n";
							$affairs .= '所属機関:' . $affair['organization'] . "\r\n";
							$affairs .= '所属部局:' . $affair['department'] . "\r\n";
							$affairs .= '職名:' . $affair['job_title'] . "\r\n";
							$affairs .= '郵便番号:' . $affair['zip'] . "\r\n";
							$affairs .= '都道府県:' . $affair['prefecture_id'] . "\r\n";
							$affairs .= '市区町村:' . $affair['city'] . "\r\n";
							$affairs .= '住所:' . $affair['address'] . "\r\n";
							$affairs .= 'TEL:' . $affair['tel'] . "\r\n";
							$affairs .= 'FAX:' . $affair['fax'] . "\r\n";
							$affairs .= 'URL:' . $affair['url'] . "\r\n";
							$affairs .= "\r\n";
							$affairs .= "\r\n";
						}
					}
					
					$data[$key]['Event']['managers']			= $managers;
					$data[$key]['Event']['affairs']				= $affairs;
					
					
					
					if ( $text1 == '0円' )
					{
						$text1 = '';
					}
					if ( $text2 == '0円' )
					{
						$text2 = '';
					}
					if ( $text3 == '0円' )
					{
						$text3 = '';
					}
					if ( $text4 == '0円' )
					{
						$text4 = '';
					}
					
					$data[$key]['Event']['text1']		= $text1;
					$data[$key]['Event']['text2']		= $text2;
					$data[$key]['Event']['text3']		= $text3;
					$data[$key]['Event']['text4']		= $text4;
					
					
					
					
					
					
				}
				
				$td = $data;
				
				
				//print_a_die($data);
				
			}
			else if( $type == 1 )
			{
				$filename = 'report_' . date('YmdHis');
				
				//表の一行目を作成
				$th = array(
					'DB-ID',
					'名称',
					'採択番号',
					'重点テーマ',
					'キーワード',
					'主催機関',
					'運営責任者',
					'開催日時(開始)',
					'開催日時(終了)',
					'開催場所',
					'最終プログラム',
					'参加者数',
					'当日の論点',
					'研究の現状と課題',
					'新たに明らかになった課題',
					'今後解決すべきこと、今後の展開・フォローアップ',
					'添付写真1',
					'添付写真2',
					'添付写真3',
				);
				
				$events = $this->Event->find('all', array(
					'contain' => array(
						'EventFile',
						'EventKeyword',
						'EventManager',
					),
					'conditions' => $conditions,
				));
				
				
				//print_a_die($events);
				
				
				$data = array();
				foreach ( $events as $key => $event )
				{
					$data[$key]['Event']['id']				= $event['Event']['id'];
					$data[$key]['Event']['title']			= $event['Event']['title'];
					$data[$key]['Event']['event_number']	= $event['Event']['event_number'];
					$data[$key]['Event']['important']		= $event['Event']['important'];
					$keyword = '';
					if ( !empty($event['EventKeyword']) )
					{
						$keyword = array();
						foreach ( $event['EventKeyword'] as $k )
						{
							$keyword[] = $k['title'];
						}
						$keyword = implode('、', $keyword);
					}
					$data[$key]['Event']['keyword']			= $keyword;
					$data[$key]['Event']['organization']	= $event['Event']['organization'];
					
					$manager_name = '';
					if ( isset($event['EventManager'][0]['lastname']) && isset($event['EventManager'][0]['firstname']) )
					{
						$manager_name = $event['EventManager'][0]['lastname'] . ' ' . $event['EventManager'][0]['firstname'];
					}
					$data[$key]['Event']['manager_name']	= $manager_name;
					
					$data[$key]['Event']['start']		= $event['Event']['start'];
					$data[$key]['Event']['end']			= $event['Event']['end'];
					$data[$key]['Event']['place']		= $event['Event']['place'];
					$data[$key]['Event']['program']		= $event['Event']['program'];
					$data[$key]['Event']['join_number']	= $event['Event']['join_number'];
					
					$data[$key]['Event']['issue']		= $event['Event']['issue'];
					$data[$key]['Event']['subject']		= $event['Event']['subject'];
					$data[$key]['Event']['new_subject']	= $event['Event']['new_subject'];
					$data[$key]['Event']['follow']		= $event['Event']['follow'];
					
					
					if ( !empty($event['EventFile']) )
					{
						$i=1;
						foreach ( $event['EventFile'] as $file )
						{
							$data[$key]['Event']['file'.$i] = '/app/webroot/files/event_file/file/' . $file['id'] . '/' . $file['file'];
							$i++;
						}
					}
					
				}
				
				$td = $data;
				
			}
			$this -> set(compact('filename', 'th', 'td'));
			
			//print_a_die($td);
			
			$this->render('download_csv');
			
		}
	}
	
	// 詳細全体
	public function view($id = null)
	{
		//$this->layout = false;
		$this->Event->hasMany['EventManager']['conditions']	= array('EventManager.is_delete' => 0);
		$this->Event->hasMany['EventAffair']['conditions']	= array('EventAffair.is_delete' => 0);
		$this->Event->hasMany['Expense']['conditions']	= array('Expense.is_delete' => 0);
		$this->Event->hasMany['Expense']['order']		= array('Expense.type ASC');
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme' => array(
					'Theme'
				),
				'EventKeyword',
				'EventManager',
				'EventAffair',
				'Expense' => array(
					'Item'
				),
				'AddUser',
				'LatestUser',
			),
			'conditions' => array(
				'Event.id' => $id
			),
		));
		
		$expenses = array();
		foreach ( $event['Expense'] as $expense )
		{
			$expenses[$expense['type']][] = $expense;
		}
		$event['Expense'] = $expenses;
				
		$this->set('event', $event);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		// 参加についてのドロップダウン
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		
		//企画ステータスの一覧
		$this->set('event_status', Configure::read('App.event_status'));
		
		//課目の一覧
		$this->set('items', $this->Item->find('list', array('conditions' => array('Item.is_delete' => 0))));
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	public function csv2 ()
	{
		if ( $this->request->is('post') )
		{
			$conditions = array(
				'Event.is_delete' => 0
			);
			
			// 開始日
			if ( isset($this->request->data['Search']['start']) && !empty($this->request->data['Search']['start']) )
			{
				$conditions[] = array('Event.start >= ?'		=> $this->request->data['Search']['start']);
			}
			
			// 終了日
			if ( isset($this->request->data['Search']['end']) && !empty($this->request->data['Search']['end']) )
			{
				$conditions[] = array('Event.end <= ?'		=> $this->request->data['Search']['end']);
			}
			
			// 0:企画申請中
			// 1:企画検討中
			// 2:企画承認済み
			// 3:報告書受付中
			// 4:報告書提出済み
			// 5:報告書承認（HPに表示）
			// 99:企画不採択
			
			$type = 0;
			if ( isset($this->request->data['event']) )
			{
				$conditions[] = array('Event.status >= ?'		=> 0);
				$conditions[] = array('Event.status <= ?'		=> 3);
			}
			else if ( isset($this->request->data['report']) )
			{
				$type = 1;
				$conditions[] = array('Event.status >= ?'		=> 4);
				$conditions[] = array('Event.status <= ?'		=> 5);
			}
			else
			{
				die('Invalid parameter.');
			}
			
			
			
			$this->layout = false;
			
			if ( $type == 0 )
			{
				$filename = '企画申請_' . date('YmdHis');
				
				/*
				集会等名称
				申請額合計（円）
				開催時期
				開催場所
				運営責任者	
				主催機関	
				集会等のタイプ

				趣旨・目的
				キーワード	
				連携相手の分野・業界
				プログラム
				取り扱うテーマ・トピックや解決すべき課題
				考えられる数学・数理科学的アプローチ
				これまでの準備状況
				終了後のフォローアップの計画
				他機関等からの支援
				有の場合は支援元
				
				申請経費内訳（円）旅費
				申請経費内訳（円）諸謝金
				申請経費内訳（円）印刷製本費
				申請経費内訳（円）その他

				参加制限
				有の場合は参加資格
				参加申込
				
				運営責任者
				フリガナ
				氏名
				専門分野
				所属機関
				職名
				住所
				email
				Tel
				URL

				事務担当者
				フリガナ
				氏名
				所属機関
				職名
				住所
				email
				Tel
				*/
				
				
				
				
				
				// 表の一行目を作成
				$th = array(
					'DB-ID',
					'種別',
					'申請年度',
					'企画番号',
					'ログインID',
					'名称',
					'連携相手の分野・業界',
					'重点テーマ',
					'主催機関',
					'開始日',
					'終了日',
					'開催場所',
					'プログラム',
					'趣旨・目的',
					'取り扱うテーマ・トピックや解決すべき課題',
					'考えられる数学・数理科学的アプローチ',
					'これまでの準備状況',
					'終了後のフォローアップの計画 ',
					'他機関からの支援',
					'有の場合は支援元',
					'当日の論点',
					'新たに明らかになった課題、今後解決すべきこと',
					'参加制限',
					'有の場合は参加資格',
					'参加申込'
				);
				
				// 表の内容を取得
				$column = array(
					'id',
					'type',
					'fiscal_year',
					'event_number',
					'username',
					'title',
					'field',
					'important',
					'organization',
					'start',
					'end',
					'place',
					'program',
					'purpose',
					'subject',
					'approach',
					'prepare',
					'follow',
					'is_support',
					'support',
					'issue',
					'new_subject',
					'qualification',
					'qualification_other',
					'qualification_apply'
				
				);
			}
			else if( $type == 1 )
			{
				$filename = '報告書_' . date('YmdHis');
				
				// 表の一行目を作成
				$th = array(
					'DB-ID',
					'種別',
					'申請年度',
					'企画番号',
					'ログインID',
					'名称',
					'連携相手の分野・業界',
					//'重点テーマ',
					'主催機関',
					'開始日',
					'終了日',
					'開催場所',
					'プログラム',
					'趣旨・目的',
					'取り扱うテーマ・トピックや解決すべき課題',
					'考えられる数学・数理科学的アプローチ',
					'これまでの準備状況',
					'終了後のフォローアップの計画 ',
					'他機関からの支援',
					'有の場合は支援元',
					'当日の論点',
					'新たに明らかになった課題、今後解決すべきこと',
					'参加制限',
					'有の場合は参加資格',
					'参加申込'
				);
				
				// 表の内容を取得
				$column = array(
					'id',
					'type',
					'fiscal_year',
					'event_number',
					'username',
					'title',
					'field',
					//'important',
					'organization',
					'start',
					'end',
					'place',
					'program',
					'purpose',
					'subject',
					'approach',
					'prepare',
					'follow',
					'is_support',
					'support',
					'issue',
					'new_subject',
					'qualification',
					'qualification_other',
					'qualification_apply'
				);
			}
			
			$td = $this->Event->find('all', array(
				'contain' => array(),
				'fields' => $column,
				'conditions' => $conditions,
			));
			$this -> set(compact('filename', 'th', 'td'));
			
			//print_a_die($td);
			
			$this->render('download_csv');
			
		}
	}
	
	/**********************************************************
	 * 関数
	 */
	// 1項目でも入力されているか調べる
	private function _is_input( $datas = array() )
	{
		$ret = false;
		foreach ( $datas as $data  )
		{
			if ( !empty($data) )
			{
				$ret = true;
				break;
			}
		}
		return $ret;
	}
}
