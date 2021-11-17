<?php
class Migration extends AppModel {

	public $actsAs = array('Containable');
	
	
	public $hasMany = array(
		'MigrationChapter' => array(
			'className' => 'MigrationChapter',
			'foreignKey' => 'migration_id'
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