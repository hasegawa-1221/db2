<?php
class EventProgram extends AppModel {

	public $actsAs = array('Containable');
	
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id'
		),
		'Meeting' => array(
			'className' => 'Meeting',
			'foreignKey' => 'event_id',
			'primaryKey' => 'event_id',
		),
	);
	
	public $hasMany = array(
		'EventPerformer' => array(
			'className' => 'EventPerformer',
			'foreignKey' => 'event_program_id',
			'conditions' => array('EventPerformer.is_delete' => 0),
		),
	);
	
	public $validate = array(
		'title' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => '講演タイトルを入力して下さい。',
			),
		),
		'sort' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => '並び順は半角数値を入力して下さい。',
			),
		)
	);
}