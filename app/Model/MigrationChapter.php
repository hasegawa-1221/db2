<?php
class MigrationChapter extends AppModel {

	public $actsAs = array('Containable');
	
	public $belongsTo = array(
		'Migration' => array(
			'className' => 'Migration',
			'foreignKey' => 'migration_id'
		),
	);
	
	public $hasMany = array(
		'MigrationPage' => array(
			'className' => 'MigrationPage',
			'foreignKey' => 'migration_chapter_id'
		),
	);
	/*
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
		),
	);
	*/
}