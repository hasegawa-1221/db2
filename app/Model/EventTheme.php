<?php
class EventTheme extends AppModel {

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id'
		),
		'Theme' => array(
			'className' => 'Theme',
			'foreignKey' => 'theme_id'
		),
	);

}