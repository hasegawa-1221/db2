<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class Meeting extends AppModel {

	public $actsAs = array('Containable');
	
	// ��Έ�
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
		),
	);
	
	// ��Α�
	public $hasMany = array(
		'MeetingFile' => array(
			'className' => 'MeetingFile',
			'foreignKey' => 'meeting_id',
		),
	);
}