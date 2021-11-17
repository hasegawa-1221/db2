<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class Meeting extends AppModel {

	public $actsAs = array('Containable');
	
	// ˆê‘Îˆê
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
		),
	);
	
	// ˆê‘Î‘½
	public $hasMany = array(
		'MeetingFile' => array(
			'className' => 'MeetingFile',
			'foreignKey' => 'meeting_id',
		),
	);
}