<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class Expense extends AppModel {

	public $actsAs = array('Containable');
	
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => array('Expense.is_delete' => 0)
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
		),
	);

	public function __construct ()
	{
		parent::__construct();
		
		$page = $this->_get_event_add_page();
		$validate = array();
		
		if ( $page == 3 )
		{
			// 3ページ目
			$validate = array(
				'request_price' => array(
					'numeric' => array(
						'rule' => 'numeric',
						'message' => '半角数値を入力して下さい。',
						'allowEmpty' => true
					),
				),
				'count' => array(
					'numeric' => array(
						'rule' => 'numeric',
						'message' => '半角数値を入力して下さい。',
						'allowEmpty' => true
					),
				),
				'price' => array(
					'numeric' => array(
						'rule' => 'numeric',
						'message' => '半角数値を入力して下さい。',
						'allowEmpty' => true
					),
				)
			);
		}
		else
		{
			// 管理画面用
		}
		
		//
		$this->validate = $validate;
	}
}