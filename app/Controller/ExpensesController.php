<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class ExpensesController extends AppController {

	public $uses = array(
		'Expense', 'Event', 'Item'
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	// 一覧
	public function index()
	{
		// 検索するボタン押下時 もしくは ページネーションのリンククリック時
		if ($this->request->is('post') || !empty($this->request->params['named']))
		{
			if (empty($this->request->data['Search']) && !empty($this->request->params['named']))
			{
				foreach ($this->request->params['named'] as $k => $v)
				{
					$this->request->data['Search'][$k] = $v;
				}
			}
			
			// 検索条件をページネーションに引き継ぎ
			foreach ($this->request->data['Search'] as $k => $v)
			{
				$this->request->params['named'][$k] = $v;
			}
		}
		else
		{
			
		}
		
		$conditions = array();
		$conditions += array('Expense.is_delete' => 0);
		
		if (isset($this->request->data['Search']['date_start']) && !empty($this->request->data['Search']['date_start']))
		{
			// 日付の妥当性チェック
			list($y, $m, $d) = explode('-', $this->request->data['Search']['date_start']);
			if (checkdate($m, $d, $y) )
			{
				$conditions += array(
					'Event.date >= ?'	=> $this->request->data['Search']['date_start'],
				);
			}
		}
		
		if (isset($this->request->data['Search']['date_end']) && !empty($this->request->data['Search']['date_end']))
		{
			// 日付の妥当性チェック
			list($y, $m, $d) = explode('-', $this->request->data['Search']['date_end']);
			if (checkdate($m, $d, $y) )
			{
				$conditions += array(
						'Event.date <= ?'	=> $this->request->data['Search']['date_end'],
				);
			}
		}
		
		// 状態
		if (isset($this->request->data['Search']['is_delete']) && !empty($this->request->data['Search']['is_delete']))
		{
			$conditions += array(
					'Event.is_delete'	=> $this->request->data['Search']['is_delete'],
			);
		}
		
		$this->paginate = array(
			'contain' => array(
				'Event', 'Item'
			),
			'conditions' => $conditions,
			'order' => 'Expense.id DESC',
			'limit' => 50
		);
		
		$expenses = $this->paginate();
		$this->set('expenses', $expenses);
		
		$items = $this->Item->find('list', array('conditions' => array('Item.is_delete' => 0)));
		$items[0] = '-----';
		ksort($items);
		$this->set('items', $items);
		
		$this->set('expense_status', Configure::read('App.expense_status'));
	}

	// 一覧
	public function index2()
	{
		// 検索するボタン押下時 もしくは ページネーションのリンククリック時
		if ($this->request->is('post') || !empty($this->request->params['named']))
		{
			if (empty($this->request->data['Search']) && !empty($this->request->params['named']))
			{
				foreach ($this->request->params['named'] as $k => $v)
				{
					$this->request->data['Search'][$k] = $v;
				}
			}
			
			// 検索条件をページネーションに引き継ぎ
			foreach ($this->request->data['Search'] as $k => $v)
			{
				$this->request->params['named'][$k] = $v;
			}
		}
		else
		{
			
		}
		
		$conditions = array();
		$conditions += array('Expense.is_delete' => 0);
		
		if (isset($this->request->data['Search']['date_start']) && !empty($this->request->data['Search']['date_start']))
		{
			// 日付の妥当性チェック
			list($y, $m, $d) = explode('-', $this->request->data['Search']['date_start']);
			if (checkdate($m, $d, $y) )
			{
				$conditions += array(
					'Event.date >= ?'	=> $this->request->data['Search']['date_start'],
				);
			}
		}
		
		if (isset($this->request->data['Search']['date_end']) && !empty($this->request->data['Search']['date_end']))
		{
			// 日付の妥当性チェック
			list($y, $m, $d) = explode('-', $this->request->data['Search']['date_end']);
			if (checkdate($m, $d, $y) )
			{
				$conditions += array(
						'Event.date <= ?'	=> $this->request->data['Search']['date_end'],
				);
			}
		}
		
		// 状態
		if (isset($this->request->data['Search']['is_delete']) && !empty($this->request->data['Search']['is_delete']))
		{
			$conditions += array(
					'Event.is_delete'	=> $this->request->data['Search']['is_delete'],
			);
		}
		
		$this->paginate = array(
			'contain' => array(
				'Event', 'Item'
			),
			'conditions' => $conditions,
			'order' => 'Expense.id DESC',
			'limit' => 50
		);
		
		$expenses = $this->paginate();
		$this->set('expenses', $expenses);
		
		$items = $this->Item->find('list', array('conditions' => array('Item.is_delete' => 0)));
		$items[0] = '-----';
		ksort($items);
		$this->set('items', $items);
		
		$this->set('expense_status', Configure::read('App.expense_status'));
	}

	// 年度別
	public function fiscal()
	{
		// 検索するボタン押下時 もしくは ページネーションのリンククリック時
		if ($this->request->is('post') || !empty($this->request->params['named']))
		{
			if (empty($this->request->data['Search']) && !empty($this->request->params['named']))
			{
				foreach ($this->request->params['named'] as $k => $v)
				{
					$this->request->data['Search'][$k] = $v;
				}
			}
			
			// 検索条件をページネーションに引き継ぎ
			foreach ($this->request->data['Search'] as $k => $v)
			{
				$this->request->params['named'][$k] = $v;
			}
		}
		else
		{
			$this->request->data['Search']['fiscal_year'] = date('Y');
		}
		
		$conditions = array();
		$conditions += array('Expense.is_delete' => 0);
		
		if (isset($this->request->data['Search']['fiscal_year']) && !empty($this->request->data['Search']['fiscal_year']))
		{
			// 経理上の締め日は？
			$this->request->data['Search']['fiscal_year_start']	= $this->request->data['Search']['fiscal_year'] . '/4/1';
			$this->request->data['Search']['fiscal_year_end']	= ($this->request->data['Search']['fiscal_year'] + 1) . '/3/31';
			
			// 日付の妥当性チェック
			list($y, $m, $d) = explode('/', $this->request->data['Search']['fiscal_year_start']);
			
			if (checkdate($m, $d, $y) )
			{
				$conditions += array(
					'Expense.execution_date >= ?'	=> $this->request->data['Search']['fiscal_year_start'],
				);
			}
			
			// 日付の妥当性チェック
			list($y, $m, $d) = explode('/', $this->request->data['Search']['fiscal_year_end']);
			
			if (checkdate($m, $d, $y) )
			{
				$conditions += array(
					'Expense.execution_date <= ?'	=> $this->request->data['Search']['fiscal_year_end'],
				);
			}
		}
		
		//print_a_die($conditions);
		
		
		$expenses = $this->Expense->find('all', array(
			'contain' => array(
				'Item'
			),
			'conditions' => $conditions,
			'order' => 'Expense.execution_date ASC',
		));
		
		$total_ask_price		= 0;
		$total_consumption_tax	= 0;
		$tabs = array();
		if ( !empty($expenses) )
		{
			foreach ( $expenses as $expense )
			{
				$total_ask_price		+= (int)$expense['Expense']['ask_price'];
				$total_consumption_tax	+= (int)$expense['Expense']['consumption_tax'];
				
				// 初期化
				$tabs[$expense['Expense']['tab_name']]['ask_price']			= 0;
				$tabs[$expense['Expense']['tab_name']]['consumption_tax']	= 0;
			}
			
			foreach ( $expenses as $expense )
			{
				$tabs[$expense['Expense']['tab_name']]['ask_price']			+= $expense['Expense']['ask_price'];
				$tabs[$expense['Expense']['tab_name']]['consumption_tax']	+= $expense['Expense']['consumption_tax'];
			}
		}
		
		$this->set('expenses', $expenses);
		$this->set('total_ask_price', $total_ask_price);
		$this->set('total_consumption_tax', $total_consumption_tax);
		$this->set('tabs', $tabs);
		
		$items = $this->Item->find('list', array('conditions' => array('Item.is_delete' => 0)));
		$items[0] = '-----';
		ksort($items);
		$this->set('items', $items);
		
		$this->set('expense_status', Configure::read('App.expense_status'));
		
		// 年度のドロップダウン
		$start = date('Y') + 1;
		$years = array();
		for ( $i= $start; $i >= 2010; $i-- )
		{
			$years[$i] = $i;
		}
		$this->set('fiscal_years', $years);
	}


	function add ()
	{
		$items = $this->Item->find('list', array('conditions' => array('Item.is_delete' => 0)));
		$items[0] = '-----';
		ksort($items);
		$this->set('items', $items);
		
		$this->set('expense_type', Configure::read('App.expense_type'));
		
		$this->set('expense_status', Configure::read('App.expense_status'));
	}
	
	function edit ($id = null)
	{
		$this->Expense->id = $id;
		if ( !$this->Expense->exists() )
		{
			throw new Exception('Invalid id');
		}
		$expense = $this->Expense->find('first', array(
			'contain' => array(
				'Event'
			),
			'conditions' => array(
				'Expense.id' => $id
			)
		));
		$this->set('expense', $expense);
		
		if ( $this->request->is('post') )
		{
			
			$this->request->data['Expense']['id'] = $expense['Expense']['id'];
			if ( $this->Expense->save($this->request->data) )
			{
				$this->Session->setFlash('入力データを更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('入力データの更新に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data  = $expense;
		}
		
		$items = $this->Item->find('list', array('conditions' => array('Item.is_delete' => 0)));
		$items[0] = '-----';
		ksort($items);
		$this->set('items', $items);
		
		$this->set('expense_type', Configure::read('App.expense_type'));
		
		$this->set('expense_status', Configure::read('App.expense_status'));
	}
	
	// ASKデータアップロード
	public function upload()
	{
		if ( $this->request->is('post') )
		{
			if (($fp = fopen($this->request->data['Expense']['csv']['tmp_name'], "r")) === false) {
				//エラー処理
				die('error');
			}
			
			$i=0;
			while (($line = fgetcsv($fp, 0, "\t")) !== FALSE)
			{
				// 文字コードがUTF-8でない場合、UTFへ変換する
				$encode = mb_detect_encoding($line[0], "UTF-8, JIS, eucjp-win, sjis-win");
				if ( $encode != 'UTF-8' )
				{
					mb_convert_variables('UTF-8', 'sjis-win', $line);
				}
				if($i == 0){
					// タイトル行
					$headers = $line;
					$i++;
					continue;
				}
				$rows[] = $line;
				$i++;
			}
			
			header("Content-Type: text/html; charset=UTF-8");
			
			// トランザクション開始
			$rollback = false;
			$this->Expense->begin();
			
			foreach ( $rows as $row )
			{
				if ( count($row) != 19 )
				{
					die('Error CSVの列数に以上があります。19列でなければなりません。');
				}
				
				$tab_name			= trim($row[0]);	// タブ名
				$execution_cd		= trim($row[1]);	// 執行形態別科目コード
				$execution_name		= trim($row[2]);	// 執行形態別科目名称
				$account_item_cd	= trim($row[3]);	// 勘定科目コード
				$account_item_name	= trim($row[4]);	// 勘定科目名称
				$partner_cd			= trim($row[5]);	// 相手先コード
				$partner_name		= trim($row[6]);	// 相手先名称
				$departure_date		= trim($row[7]);	// 出発日
				$return_date		= trim($row[8]);	// 帰着日
				$ask_title			= trim($row[9]);	// 件名
				$comment			= trim($row[10]);	// 備考
				$description		= trim($row[11]);	// 品名／内容
				$ask_price			= trim($row[12]);	// 執行額
				$consumption_tax	= trim($row[13]);	// 消費税額
				$execution_date		= trim($row[14]);	// 執行日
				$payment_status		= trim($row[15]);	// 支払状態
				$contract_number	= trim($row[16]);	// 契約NO
				$contract_branch	= trim($row[17]);	// 契約行NO
				$tax_cd				= trim($row[18]);	// 税区分コード
				
				$expenses = $this->Expense->find('all', array(
					'contain' => array(),
					'fields' => array('Expense.id'),
					'conditions' => array(
						'Expense.contract_number' => $contract_number,
						'Expense.contract_branch' => $contract_branch,
						
					)
				));
				
				$count = count($expenses);
				
				// 既存データかどうか
				// 既存の場合はUpdate、そうでない場合はInsert
				
				$save = array();
				$save['Expense']['tab_name']			= $tab_name;
				$save['Expense']['execution_cd']		= $execution_cd;
				$save['Expense']['execution_name']		= $execution_name;
				$save['Expense']['account_item_cd']		= $account_item_cd;
				$save['Expense']['account_item_name']	= $account_item_name;
				$save['Expense']['partner_cd']			= $partner_cd;
				$save['Expense']['partner_name']		= $partner_name;
				$save['Expense']['departure_date']		= $departure_date;
				$save['Expense']['return_date']			= $return_date;
				$save['Expense']['ask_title']			= $ask_title;
				$save['Expense']['comment']				= $comment;
				$save['Expense']['description']			= $description;
				$save['Expense']['ask_price']			= $ask_price;
				$save['Expense']['consumption_tax']		= $consumption_tax;
				$save['Expense']['execution_date']		= date('Y-m-d', strtotime($execution_date));
				$save['Expense']['payment_status']		= $payment_status;
				$save['Expense']['contract_number']		= $contract_number;
				$save['Expense']['contract_branch']		= $contract_branch;
				$save['Expense']['tax_cd']				= $tax_cd;
				$save['Expense']['admin_id']			= $this->Auth->user('id');
				$save['Expense']['latest_admin_id']		= $this->Auth->user('id');
				
				// タブ名から科目ID取得
				// 人件費
				// 会議開催費
				// 国内旅費
				// 外国人等招へい旅費
				// 消耗品費
				// 給与
				// 諸謝金
				// 雑役務費
				// 印刷製本費
				
				$item = $this->Item->find('first', array(
					'contain' => array(),
					'conditions' => array(
						'Item.name' => $tab_name
					)
				));
				
				$save['Expense']['item_id']	= 0;
				if ( !empty($item) )
				{
					$save['Expense']['item_id']	= $item['Item']['id'];
				}
				
				// 国内旅費
				// 諸謝金
				// 会議開催費
				// その他
				if ( $tab_name == '国内旅費' )
				{
					$save['Expense']['type']	= 1;
				}
				else if ( $tab_name == '諸謝金' )
				{
					$save['Expense']['type']	= 2;
				}
				else if ( $tab_name == '会議開催費' )
				{
					$save['Expense']['type']	= 3;
				}
				else
				{
					$save['Expense']['type']	= 4;
				}
				
				// 
				if ( $payment_status == '支払指示済' )
				{
					// 確定
					$save['Expense']['status']	= 2;
				}
				
				if ( $count == 0 )
				{
					// 新規挿入処理
					$this->Expense->create();
					if ( !$this->Expense->save($save) )
					{
						$rollback = true;
						break;
					}
				}
				else if ( $count == 1 )
				{
					// 更新処理
					$save['Expense']['id'] = $expenses[0]['Expense']['id'];
					$this->Expense->set($save);
					if ( !$this->Expense->save($save) )
					{
						$rollback = true;
						break;
					}
				}
				else if ( $count > 1 )
				{
					// データ重複
					$this->Expense->rollback();
					$this->Session->setFlash('データベース上で同一の契約番号が複数見つかりました。', 'Flash/error');
					$this->redirect(array('action' => 'upload'));
				}
			}
			
			if ( !$rollback )
			{
				$this->Expense->commit();
				$this->Session->setFlash('データを更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'upload'));
			}
			else
			{
				$this->Expense->rollback();
				$this->Session->setFlash('データの更新に失敗しました。', 'Flash/error');
				$this->redirect(array('action' => 'upload'));
			}
		}
	}
}
