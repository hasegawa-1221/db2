<?php
class ResearcherPaper extends AppModel {

	public $actsAs = array('Containable');
	
	public $belongsTo = array(
		'Researcher' => array(
			'className' => 'Researcher',
			'foreignKey' => 'researcher_id'
		),
	);
}