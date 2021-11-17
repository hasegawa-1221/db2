<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
App::uses('AppController', 'Controller');
class AdminsController extends AppController {

	public $uses = array('Admin');

	public $components = array(
/*
		'Auth' => array(
			'loginAction' => array(
				'controller'	=> 'admins',
				'action'		=> 'login',
			),
			'authError' => 'Auth Error',
			'authenticate' => array(
				'Form' => array(
					'userModel' => 'Admin',
					'passwordHasher' => array(
						'className' => 'Simple',
						'hashType' => 'sha256'
					),
					'scope' => array( 'Admin.is_delete = ?' => 0)
				)
			)
		)
*/
	);
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('add');
		
		
		
		//	$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
		//	print_a_die($passwordHasher->hash('badbrains'));
		
		
	}

	// ログイン
	public function login() {
		$this->layout = 'login';
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect(array('controller' => 'dashboards', 'action' => 'index'));
			}
			$this->Session->SetFlash('ID か パスワードが間違っています。', 'Flash/error');
		}
	}

	// ログアウト
	public function logout() {
		$this->Auth->logout();
    	$this->Session->delete("Auth.Admin"); 
		$this->redirect(array('action' => 'login'));
	}

	// 一覧
	public function index()
	{
		$admins = $this->Admin->find('all', array(
			'contain' => array(
				
			),
			'conditions' => array(
				
			),
			'order' => 'Admin.id ASC'
		));
		$this->set('admins', $admins);
	}

	// 追加
	public function add() {
		
		if ($this->request->is('post'))
		{
			if ( $this->Admin->save($this->request->data) )
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
	}

	// edit
	public function edit( $id = null ) {
		$this->Admin->id = $id;
		if ( !$this->Admin->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$admin = $this->Admin->find('first', array(
			'contain' => array(
				
			),
			'conditions' => array(
				'Admin.id' => $id
			)
		));
		$this->set('admin', $admin);
		
		if ( empty($this->request->data['Admin']['password']) )
		{
			// パスワードが空の場合、パスワードは更新しない
			unset($this->request->data['Admin']['password']);
			unset($this->Admin->validate['password']);
		}
		
		if ($this->request->is('post'))
		{
			$this->request->data['Admin']['id'] = $id;
			$this->Admin->set($this->request->data);
			if ( $this->Admin->save($this->request->data) )
			{
				$this->Session->setFlash('管理者を編集しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('管理者の更新に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			unset($admin['Admin']['password']);
			$this->request->data = $admin;
		}
	}

	// pass
	public function password() {
		
		$this->Admin->id = $this->Auth->user('id');
		if ( !$this->Admin->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$user = $this->Admin->find('first', array(
			'contain' => array(
				
			),
			'conditions' => array(
				'Admin.id' => $this->Auth->user('id')
			)
		));
		$this->set('user', $user);
		
		
		if ($this->request->is('post'))
		{
			$this->request->data['Admin']['id'] = $this->Auth->user('id');
			$this->Admin->set($this->request->data);
			if ( $this->Admin->save($this->request->data) )
			{
				$this->Session->setFlash('パスワードを変更しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('パスワードの変更に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $user;
			unset($this->request->data['Admin']['password']);
		}
	}
}
