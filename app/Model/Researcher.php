<?php
class Researcher extends AppModel {

	public $actsAs = array('Containable');
	
	public $hasMany = array(
		'ResearcherCareer' => array(
			'className' => 'ResearcherCareer',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherPrize' => array(
			'className' => 'ResearcherPrize',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherConference' => array(
			'className' => 'ResearcherConference',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherBiblio' => array(
			'className' => 'ResearcherBiblio',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherResearchKeyword' => array(
			'className' => 'ResearcherResearchKeyword',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherResearchArea' => array(
			'className' => 'ResearcherResearchArea',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherAcademicSociety' => array(
			'className' => 'ResearcherAcademicSociety',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherTeachingExperience' => array(
			'className' => 'ResearcherTeachingExperience',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherPaper' => array(
			'className' => 'ResearcherPaper',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherCompetitiveFund' => array(
			'className' => 'ResearcherCompetitiveFund',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherOther' => array(
			'className' => 'ResearcherOther',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherPatent' => array(
			'className' => 'ResearcherPatent',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherAcademicBackground' => array(
			'className' => 'ResearcherAcademicBackground',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherCommitteeCareer' => array(
			'className' => 'ResearcherCommitteeCareer',
			'foreignKey' => 'researcher_id'
		),
		'ResearcherSocialContribution' => array(
			'className' => 'ResearcherSocialContribution',
			'foreignKey' => 'researcher_id'
		),
	);
}