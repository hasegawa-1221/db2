<?php
class Item extends AppModel {

	public $actsAs = array('Containable');
	
	public $hasMany = array(
		'Children' => array(
			'className' => 'Item',
			'foreignKey' => 'parent_id'
		),
	);

	public $validate = array(
		'name' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 100),
				'message' => '課目名は100文字以内で入力して下さい。',
				'allowEmpty' => false
			),
		),
	);

}