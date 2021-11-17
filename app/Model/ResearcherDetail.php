<?php
class ResearcherDetail extends AppModel {

	public $actsAs = array('Containable');
	
	public $bellongsTo = array(
		'Researcher' => array(
			'className' => 'Researcher',
			'foreignKey' => 'researcher_id'
		),
	);
}