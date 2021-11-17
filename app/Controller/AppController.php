<?php
App::uses('Controller', 'Controller');
class AppController extends Controller {
	
	public $uses = array('Admin');
	public $helpers = array('Display');
	public $components = array(
		'Session',
		'Auth'
	);
	
	public function beforeFilter()
	{
		App::import('Vendor', 'debuglib');
		App::import('Vendor', 'Util');
		App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
		CakeSession::$requestCountdown = 1;

		if ( $this->name == 'Databases' )
		{
			$this->Auth->loginAction = array('controller' => 'databases', 'action' => 'login');
			$this->Auth->authError = 'Auth Error';
			$this->Auth->authenticate = array(
				'Form' => array(
					'userModel' => 'Event',
					'passwordHasher' => array(
						'className' => 'Simple',
						'hashType' => 'sha256'
					),
					'scope' => array( 'Event.is_delete = ?' => 0)
				)
			);
			// ŠÇ—‰æ–Ê‚Æ‚ÍƒZƒbƒVƒ‡ƒ“‚ð•ª‚¯‚é
			AuthComponent::$sessionKey = 'Auth.Event';
		}
		else
		{
			$this->Auth->loginAction = array('controller' => 'admins', 'action' => 'login');
			$this->Auth->authError = 'Auth Error';
			$this->Auth->authenticate = array(
				'Form' => array(
					'userModel' => 'Admin',
					'passwordHasher' => array(
						'className' => 'Simple',
						'hashType' => 'sha256'
					),
					'scope' => array( 'Admin.is_delete = ?' => 0)
				)
			);
			AuthComponent::$sessionKey = 'Auth.Admin';
		}
		
		
		
		
		
		
		
		
		
		
		
		$this->appConfig = Configure::read('App');
		$this->set('appConfig', $this->appConfig);
	}
}
