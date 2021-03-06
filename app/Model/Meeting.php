<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class Meeting extends AppModel {

	public $actsAs = array('Containable');
	
	// 一対一
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
		),
	);
	
	// 一対多
	public $hasMany = array(
		'MeetingFile' => array(
			'className' => 'MeetingFile',
			'foreignKey' => 'meeting_id',
		),
	);
}