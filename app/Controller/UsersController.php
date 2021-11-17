<?php
App::uses('AppController', 'Controller');
class UsersController extends AppController {

	public $uses = array('User', 'Affiliation');

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	// 一覧
	public function index()
	{
		$users = $this->User->find('all', array(
			'contain' => array(
				'Affiliation'
			),
			'conditions' => array(
				
			),
			'order' => 'User.lastname_kana ASC, User.id ASC'
		));
		$this->set('users', $users);
	}

	// 追加
	public function add() {
		
		if ($this->request->is('post'))
		{
			if ( $this->User->save($this->request->data) )
			{
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				
			}
		}
		else
		{
			
		}
		
		// ドロップダウン用データ
		$affiliations = $this->Affiliation->find('list', array('conditions' => array('is_delete' => 0)));
		$Util = new Util();
		$affiliations = $Util->set_dropdown_default($affiliations);
		$this->set('affiliations', $affiliations);
	}

	// edit
	public function edit( $id = null ) {
		
		$this->User->id = $id;
		if ( !$this->User->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$user = $this->User->find('first', array(
			'contain' => array(
				'Affiliation'
			),
			'conditions' => array(
				'User.id' => $id
			)
		));
		$this->set('user', $user);
		
		if ($this->request->is('post'))
		{
			$this->request->data['User']['id'] = $id;
			$this->User->set($this->request->data);
			if ( $this->User->save($this->request->data) )
			{
				$this->Session->setFlash('ユーザーを編集しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('ユーザーの編集に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $user;
		}
		// ドロップダウン用データ
		$affiliations = $this->Affiliation->find('list', array('conditions' => array('is_delete' => 0)));
		$Util = new Util();
		$affiliations = $Util->set_dropdown_default($affiliations);
		$this->set('affiliations', $affiliations);
	}

	// AJAXで並び順を変更
	function sorts ()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		// 結果用配列
		$ret = array(
			'message' => '',
			'data' => array(),
		);
		
		if ( isset($this->request->data) && !empty($this->request->data) )
		{
			$rollback = false;
			$this->User->begin();
			$i = 1;
			foreach ($this->request->data as $key => $id)
			{
				$save						= array();
				$sort						= $i;
				$save['User']['id']			= $id;
				$save['User']['sort']		= $i;
				$ret['data'][$id] 			= $sort;
				
				$this->User->set( $save );
				if ( !$this->User->save($save) )
				{
					$rollback = true;
					break;
				}
				else
				{
					$i++;
				}
			}
			
			if ( !$rollback )
			{
				$ret['message'] = '並び順を変更しました。';
				$this->User->commit();
			}
			else
			{
				$ret['message'] = '並び順の変更に失敗しました。';
				$this->User->rollback();
			}
		}
		
		return json_encode($ret);
	}
	
	function sorts2 ()
	{
		$users = $this->User->find('all',array('order' => 'User.sort ASC'));
		$i=1;
		foreach ($users as $user)
		{
			$save = array();
			$save['User']['id']			= $user['User']['id'];
			$save['User']['sort']		= $i;
			
			$this->User->set( $save );
			if ( !$this->User->save($save) )
			{
				die('failed');
			}
			$i++;
		}
	}
}
