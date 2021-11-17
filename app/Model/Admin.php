<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class Admin extends AppModel {

	public $actsAs = array('Containable');

	public $validate = array(
		'username' => array(
			'custom' => array(
				'rule' => array('custom', '/^[a-zA-Z0-9\.\+\-]+$/'),
				'message' => '半角英数、ドット、プラス、マイナスのみです。',
			),
			'minLength' => array(
				'rule' => array('minLength', 3),
				'message' => '最低３文字以上入力してください。'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'そのログインIDはすでに使われています。'
			),
		),
		'password' => array(
			'custom' => array(
				'required' => false,
				'rule' => array('custom', '/^[a-zA-Z0-9\.\+\-]+$/'),
				'message' => '半角英数、ドット、プラス、マイナスのみです。',
			),
			'minLength' => array(
				'rule' => array('minLength', 3),
				'message' => '最低３文字以上入力してください。'
			)
		),
	);

	public function beforeSave($options = array())
	{
		if (!empty($this->data[$this->alias]['password']))
		{
			$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		return true;
	}

}