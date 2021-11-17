<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class Event extends AppModel {

	public $actsAs = array(
		'Containable'
	);
	
	public $belongsTo = array(
		'AddUser' => array(
			'className' => 'Admin',
			'foreignKey' => 'admin_id'
		),
		'LatestUser' => array(
			'className' => 'Admin',
			'foreignKey' => 'latest_admin_id'
		),
	);
	
	public $hasMany = array(
		'Expense' => array(
			'className' => 'Expense',
			'foreignKey' => 'event_id'
		),
		'EventTheme' => array(
			'className' => 'EventTheme',
			'foreignKey' => 'event_id'
		),
		'EventKeyword' => array(
			'className' => 'EventKeyword',
			'foreignKey' => 'event_id'
		),
		'EventManager' => array(
			'className' => 'EventManager',
			'foreignKey' => 'event_id'
		),
		'EventAffair' => array(
			'className' => 'EventAffair',
			'foreignKey' => 'event_id'
		),
		'EventProgram' => array(
			'className' => 'EventProgram',
			'foreignKey' => 'event_id'
		),
		'EventFile' => array(
			'className' => 'EventFile',
			'foreignKey' => 'event_id'
		),
	);

	public function __construct ()
	{
		parent::__construct();
		
		$page = $this->_get_event_add_page();
		$validate = array();
		
		if ( $page == 1 )
		{
			// 1ページ目
			$validate = array(
				'username' => array(
					//'alphaNumeric' => array(
					//	'rule' => 'alphaNumeric',
					//	'message' => 'ログインIDは半角英数で入力して下さい。',
					//),
					
					'custom' => array(
						'rule' => array('custom', '/^[a-zA-Z0-9,\-,\_]+$/'),
						'message' => 'ログインIDは半角英数、ハイフン、アンダーバーで入力して下さい。',
					),
        
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => '入力したログインIDは既に使用されております。',
					),
				),
				'password' => array(
					'alphaNumeric' => array(
						'rule' => 'alphaNumeric',
						'message' => 'パスワードは半角英数で入力して下さい。',
					),
				),
				'title' => array(
					'notBlank' => array(
						'rule' => 'notBlank',
						'message' => '企画タイトルを入力して下さい。',
					),
				),
				'field	' => array(
					'notBlank' => array(
						'rule' => 'notBlank',
						'message' => '連携分野を入力して下さい。',
					),
				),
				'start' => array(
					'date' => array(
						'rule' => 'date',
						'message' => '開催時期（開始）は正しい形式で入力して下さい。',
					),
				),
				'end' => array(
					'date' => array(
						'rule' => 'date',
						'message' => '開催時期（終了）は正しい形式で入力して下さい。',
					),
				),
				'organization' => array(
					'notBlank' => array(
						'rule' => 'notBlank',
						'message' => '主催機関を入力して下さい。',
					),
				),
				'place' => array(
					'notBlank' => array(
						'rule' => 'notBlank',
						'message' => '開催場所を入力して下さい。',
					),
				)
			);
		}
		elseif ( $page == 2 )
		{
			// 2ページ目
			$validate = array(
				'program' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 3000),
						'message' => 'プログラムは3,000文字以内で入力して下さい。'
					)
				),
				'purpose' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 1000),
						'message' => '趣旨・目的は1,000文字以内で入力して下さい。'
					)
				),
				'subject' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 1000),
						'message' => '解決すべき課題は1,000文字以内で入力して下さい。'
					)
				),
				'approach' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 1000),
						'message' => '考えられる数学・数理科学的アプローチは1,000文字以内で入力して下さい。'
					)
				),
				'follow' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 1000),
						'message' => '会議終了後に考えられるフォローアップは1,000文字以内で入力して下さい。'
					)
				),
				'is_support' => array(
					'comparison' => array(
						'rule' => array('comparison', '>=', 0),
						'message' => '他機関からの支援を選択して下さい。'
					),
					'comparison' => array(
						'rule' => array('comparison', '<=', 1),
						'message' => '他機関からの支援を選択して下さい。'
					),
				),
				'support' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 1000),
						'message' => '支援元は1,000文字以内で入力して下さい。',
						'required' => false,
						'allowEmpty' => true,
					),
				),
			);
		}
		elseif ( $page == 3 )
		{
			// 3ページ目 経費
			// ここではバリデーションしない
			// Expense モデルで行う
		}
		elseif ( $page == 4 )
		{
			// 4ページ目 
			$validate = array(
				'qualification' => array(
					'between' => array(
						'rule' => array('comparison', '>=', 0),
						'message' => '参加制限を選択して下さい。'
					)
				),
				'qualification_apply' => array(
					'comparison' => array(
						'rule' => array('comparison', '>=', 0),
						'message' => '参加申込みの要不要を選択して下さい。'
					),
					'comparison' => array(
						'rule' => array('comparison', '<=', 1),
						'message' => '参加申込の要不要を選択して下さい。'
					),
				),
			//	'qualification_method' => array(
			//		'between' => array(
			//			'rule' => array('lengthBetween', 1, 200),
			//			'message' => '申込方法は200文字以内で入力して下さい。'
			//		)
			//	),
			//	'is_qualification_cost' => array(
			//		'comparison' => array(
			//			'rule' => array('comparison', '>=', 0),
			//			'message' => '参加費の有無を選択して下さい。'
			//		),
			//		'comparison' => array(
			//			'rule' => array('comparison', '<=', 1),
			//			'message' => '参加費の有無を選択して下さい。'
			//		),
			//	),
			//	'qualification_cost' => array(
			//		'between' => array(
			//			'rule' => array('lengthBetween', 1, 200),
			//			'message' => '参加費の詳細は200文字以内で入力して下さい。'
			//		)
			//	),
			);
		}
		/*
		elseif ( $page == 4 )
		{
			// 4ページ目 
			$validate = array(
				'qualification' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 200),
						'message' => '参加資格は200文字以内で入力して下さい。'
					)
				),
				'qualification_apply' => array(
					'comparison' => array(
						'rule' => array('comparison', '>=', 0),
						'message' => '参加申込みの要不要を選択して下さい。'
					),
					'comparison' => array(
						'rule' => array('comparison', '<=', 1),
						'message' => '参加申込みの要不要を選択して下さい。'
					),
				),
				'qualification_method' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 200),
						'message' => '申込方法は200文字以内で入力して下さい。'
					)
				),
				'is_qualification_cost' => array(
					'comparison' => array(
						'rule' => array('comparison', '>=', 0),
						'message' => '参加費の有無を選択して下さい。'
					),
					'comparison' => array(
						'rule' => array('comparison', '<=', 1),
						'message' => '参加費の有無を選択して下さい。'
					),
				),
				'qualification_cost' => array(
					'between' => array(
						'rule' => array('lengthBetween', 1, 200),
						'message' => '参加費の詳細は200文字以内で入力して下さい。'
					)
				),
			);
		}
		*/
		elseif ( $page == 5 )
		{
			// 5ページ目 運営責任者
			// ここではバリデーションしない
			// EventManager モデルで行う
			
			
		}
		else
		{
			// 管理画面用
		}
		
		//
		$this->validate = $validate;
	}

	// 保存直前の自動メソッド
	public function beforeSave($options = array())
	{
		if (!empty($this->data[$this->alias]['password']))
		{
			$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		
		return true;
	}

	// パスワードの暗号化
	public function passhash($pass)
	{
		$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
		$pass = $passwordHasher->hash($pass);
		return $pass;
	}

	// Eventテーブル更新用
	// $dataの中にDBのIDを含むこと
	public function event_update($data = array())
	{
		if (empty($data))
		{
			return false;
		}
		
		$ret = false;
		$this->set($data);
		if ( $this->save($data) )
		{
			$ret = true;
		}
		return $ret;
	}
	

	// get_event_number
	// 企画番号を作成する
	// param @type 1:公募(2018A00X) 2:日本数学会(2018S00X) 3:九大(2018K00X)
	// 年度 + 種別( A or S or K) + DBのID3桁のゼロ埋め
	public function get_event_number ($type = 0)
	{
		if ( $type == 0 )
		{
			return '';
		}
		
		$ret = '';
		
		// 現在の年度取得
		$fiscal_year = $this->get_fiscal_year();
		
		// 企画種別別のシリアル番号取得
		$next_serial = $this->get_next_serial($fiscal_year, $type);
		$next_serial = sprintf('%03d', $next_serial);
		
		// A or S or K を判別
		$event_symbol = '';
		if ( $type == 1 )
		{
			$event_symbol = 'A';
		}
		elseif ( $type == 2 )
		{
			$event_symbol = 'S';
		}
		elseif ( $type == 3 )
		{
			$event_symbol = 'K';
		}
		else
		{
			return '';
		}
		
		$ret .= $fiscal_year . $event_symbol . $next_serial;
		
		return $ret;
	}

	
	// 
	public function get_max_serial ( $fiscal_year = '', $type = 0 )
	{
		$event = $this->find('first', array(
			'contain' => array(),
			'fields' => array(
				'MAX(Event.event_serial) AS max_serial'
			),
			'conditions' => array(
				'Event.fiscal_year'	=> $fiscal_year,
				'Event.type'		=> $type,
			)
		));
		$ret = 0;
		if( !empty($event['0']['max_serial']) )
		{
			$ret = $event['0']['max_serial'];
		}
		return $ret;
	}

	// 
	public function get_next_serial ( $fiscal_year = '', $type = 0 )
	{
		if ( empty($fiscal_year) )
		{
			return '';
		}
		
		if ( empty($type) )
		{
			return '';
		}
		
		$ret = $this->get_max_serial ( $fiscal_year, $type );
		$ret = $ret + 1;
		return $ret;
	}

	public function rename_file ($field, $filename, $data, $option)
	{
		$this->data[$this->name][$field . '_org'] = $filename;
		return hash("md5", $filename) . "." . pathinfo( $filename, PATHINFO_EXTENSION );
	}

}