<?php
class EventAffair extends AppModel {

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id'
		),
	);

	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'メールアドレスの形式で入力して下さい。',
			),
			'between' => array(
				'rule' => array('lengthBetween', 1, 255),
				'message' => 'メールアドレスは255文字以内で入力して下さい。',
			)
		),
		'lastname' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 50),
				'message' => '姓は50文字以内で入力して下さい。',
			)
		),
		'firstname' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 50),
				'message' => '名は50文字以内で入力して下さい。',
			)
		),
		'lastname_kana' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 50),
				'message' => '姓（フリガナ）は50文字以内で入力して下さい。',
			),
			'katakana' => array(
				'rule' => array('katakana_only'),
				'message' => '姓（フリガナ）はカタカナで入力してください。',
			),
		),
		'firstname_kana' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 50),
				'message' => '名（フリガナ）は50文字以内で入力して下さい。',
			),
			'katakana' => array(
				'rule' => array('katakana_only'),
				'message' => '名（フリガナ）はカタカナで入力してください。',
			),
		),
		'organization' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1,100),
				'message' => '所属機関は100文字以内で入力して下さい。',
			)
		),
		'department' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 100),
				'message' => '所属部局は100文字以内で入力して下さい。',
			)
		),
		'job_title' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 100),
				'message' => '職名は100文字以内で入力して下さい。',
				'allowEmpty' => true
			)
		),
		'url' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 255),
				'message' => 'URLは255文字以内で入力して下さい。',
				'allowEmpty' => true
			),
			'url' => array(
				'rule' => array('url'),
				'message' => 'URLの形式で入力して下さい。',
				'allowEmpty' => true
			),
		),
		'zip' => array(
			'custom' => array(
				//'rule' => array('lengthBetween', 1, 100),
				//'message' => '郵便番号は10文字以内で入力して下さい。',
				'rule' => array( 'custom', '/^[0-9]{3}-[0-9]{4}$/'),
				'message' => '郵便番号は半角数字、半角ハイフンで入力して下さい。',
			)
		),
		'prefecture_id' => array(
			'between' => array(
				'rule' => array('comparison', '>', 0),
				'message' => '都道府県を選択して下さい。',
			)
		),
		'city' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 100),
				'message' => '市区町村は100文字以内で入力して下さい。',
			)
		),
		'address' => array(
			'between' => array(
				'rule' => array('lengthBetween', 1, 100),
				'message' => '住所は100文字以内で入力して下さい。',
			)
		),
		'tel' => array(
			/*
			'between' => array(
				'rule' => array('lengthBetween', 1, 100),
				'message' => 'TELは100文字以内で入力して下さい。',
			),
			*/
			'custom' => array(
				'rule' => array( 'custom', '/^(0\d{1,4}-\d{1,4}-\d{4})$/'),
				'message' => 'TELの形式で入力して下さい。',
			)
		),
		'fax' => array(
			/*
			'between' => array(
				'rule' => array('lengthBetween', 1, 100),
				'message' => 'FAXは100文字以内で入力して下さい。',
				'allowEmpty' => true
			)
			*/
			'custom' => array(
				'rule' => array( 'custom', '/^(0\d{1,4}-\d{1,4}-\d{4})$/'),
				'message' => 'FAXの形式で入力して下さい。',
				'allowEmpty' => true
			)
		)
	);
	
	function katakana_only ( $wordvalue )
	{
		$value = array_shift($wordvalue);
		return preg_match("/^[ァ-ヶー゛゜]*$/u", $value); // カタカナ
		//return preg_match("/^[ァ-ヶー゛゜]*$/u", $value); // スペースも含むカタカナ
	}
}