<?php
class Affiliation extends AppModel {

	public $actsAs = array('Containable');

	public $validate = array(
		'name' => array(
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'その名前はすでに使われています。'
			),
		),
		'sort' => array(
			'alphaNumeric' => array(
				'rule' => 'numeric',
				'message' => '数字のみです。',
			)
		),
	);

}