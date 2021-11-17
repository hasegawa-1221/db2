<?php
class EventPerformer extends AppModel {

	public $actsAs = array('Containable');
	
	public $belongsTo = array(
		'EventProgram' => array(
			'className' => 'EventProgram',
			'foreignKey' => 'event_program_id',
		),
	);
	
	public $validate = array(
		'organization' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => '所属機関を入力して下さい。',
			),
		),
		'role' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => '役職を入力して下さい。',
			),
		),
		'lastname' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => '姓を入力して下さい。',
			),
		),
		'firstname' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => '名を入力して下さい。',
			),
		)
	);
}