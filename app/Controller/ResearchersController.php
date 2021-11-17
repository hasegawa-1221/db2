<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
class ResearchersController extends AppController {

	public $uses = array(
		'Researcher',
		'ResearcherCareer',
		'ResearcherPrize',
		'ResearcherConference',
		'ResearcherBiblio',
		'ResearcherResearchKeyword',
		'ResearcherResearchArea',
		'ResearcherAcademicSociety',
		'ResearcherTeachingExperience',
		'ResearcherPaper',
		'ResearcherCompetitiveFund',
		'ResearcherOther',
		'ResearcherPatent',
		'ResearcherAcademicBackground',
		'ResearcherCommitteeCareer',
		'ResearcherSocialContribution'
	);

	public $components = array(
		'Session'
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function index ()
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
		
		$joins = array();
		$conditions = array();
		$conditions += array('Researcher.is_delete' => 0);
		
		// 氏名
		if ( isset($this->request->data['Search']['name']) && !empty($this->request->data['Search']['name']) )
		{
			$conditions += array(
				'OR' => array(
					array('Researcher.name_ja LIKE ?'	=> '%' . trim($this->request->data['Search']['name']) . '%'),
					array('Researcher.name_en LIKE ?'	=> '%' . trim($this->request->data['Search']['name']) . '%'),
					array('Researcher.name_kana LIKE ?'	=> '%' . trim($this->request->data['Search']['name']) . '%'),
				)
			);
		}
		
		// 所属
		if ( isset($this->request->data['Search']['affiliation']) && !empty($this->request->data['Search']['affiliation']) )
		{
			$conditions += array(
				'Researcher.affiliation LIKE ?'	=> '%' . trim($this->request->data['Search']['affiliation']) . '%'
			);
		}
		
		// 部署
		if ( isset($this->request->data['Search']['section']) && !empty($this->request->data['Search']['section']) )
		{
			$conditions += array(
				'Researcher.section LIKE ?'	=> '%' . trim($this->request->data['Search']['section']) . '%'
			);
		}
		
		// プロフィール
		if ( isset($this->request->data['Search']['profile']) && !empty($this->request->data['Search']['profile']) )
		{
			$conditions += array(
				'Researcher.profile LIKE ?'	=> '%' . trim($this->request->data['Search']['profile']) . '%'
			);
		}
		
		// 研究分野
		if ( isset($this->request->data['Search']['field']) && !empty($this->request->data['Search']['field']) )
		{
			$joins[] = array(
				'type'	=> 'LEFT',
				'table'	=> 'researcher_research_areas',
				'alias'	=> 'ResearcherResearchArea2',
				'conditions' => 'Researcher.id = ResearcherResearchArea2.researcher_id'
			);
			
			$conditions += array(
				'ResearcherResearchArea2.title LIKE ?'	=> '%' . trim($this->request->data['Search']['field']) . '%'
			);
		}
		
		// 研究キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$joins[] = array(
				'type'	=> 'LEFT',
				'table'	=> 'researcher_research_keywords',
				'alias'	=> 'ResearcherResearchKeyword2',
				'conditions' => 'Researcher.id = ResearcherResearchKeyword2.researcher_id',
			);
			
			$conditions += array(
				'ResearcherResearchKeyword2.title LIKE ?'	=> '%' . trim($this->request->data['Search']['keyword']) . '%'
			);
		}
		
		
		$this->modelClass = 'Researcher';
		$this->paginate = array(
			'contain' => array(
				'ResearcherCareer',
				'ResearcherPrize',
				'ResearcherConference',
				'ResearcherBiblio',
				'ResearcherResearchKeyword',
				'ResearcherResearchArea',
				'ResearcherAcademicSociety',
				'ResearcherTeachingExperience',
				'ResearcherPaper',
				'ResearcherCompetitiveFund',
				'ResearcherOther',
				'ResearcherPatent',
				'ResearcherAcademicBackground',
				'ResearcherCommitteeCareer',
				'ResearcherSocialContribution'
			),
			'conditions' => $conditions,
 			'joins' => $joins,
			'order' => 'Researcher.id DESC',
			'limit' => 20
		);
		
		$researchers = $this->paginate();
		$this->set('researchers', $researchers);
	}

	// CSV一括登録
	public function bulk_add($id = null)
	{
		
		if ( $this->request->is('post') )
		{
			
			if (($fp = fopen($this->request->data['Upload']['csv']['tmp_name'], "r")) === false) {
				//エラー処理
				die('error');
			}
			setlocale(LC_ALL, 'ja_JP');

			$i=0;
			while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
				mb_convert_variables('UTF-8', 'sjis-win', $line);
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
			
			
			//print_a_die($rows, 'y:200');
			
			if ( !empty($rows) )
			{
				$rollback = false;
				$this->Researcher->begin();
				
				// 0	挿入なし					NO.				1
				// 1	base						拠点			幹事拠点
				// 2	affiliation					所属機関		九州大学
				// 3	section						部門			農学研究院
				// 4	name_ja						氏名			岡本 正宏
				// 5	job							役職			教授
				// 6	email						メールアドレス	''
				// 7	specialty					専門			システム生物学
				// 8	recommender					推薦者			福本　康秀
				// 9	recommender_affiliation		推薦者所属先	九州大学マス・フォア・インダストリ研究所
				// 10	comment						備考			''
				
				foreach ( $rows as $row )
				{
					$save = array();
					$save['Researcher']['base']						= $row[1];
					$save['Researcher']['affiliation']				= $row[2];
					$save['Researcher']['section']					= $row[3];
					$save['Researcher']['name_ja']					= $row[4];
					$save['Researcher']['job']						= $row[5];
					$save['Researcher']['email']					= $row[6];
					$save['Researcher']['specialty']				= $row[7];
					$save['Researcher']['recommender']				= $row[8];
					$save['Researcher']['recommender_affiliation']	= $row[9];
					$save['Researcher']['comment']					= $row[10];
					
					$this->Researcher->create();
					if ( !$this->Researcher->save($save) )
					{
						$rollbaclk = true;
						break;
					}
				}
				
				if ( !$rollback )
				{
					$this->Session->setFlash('データを一括登録しました。', 'Flash/success');
					$this->Researcher->commit();
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->Session->setFlash('データの登録に失敗しました。', 'Flash/error');
					$this->Researcher->rollback();
				}
			}
			else
			{
				$this->Session->setFlash('データが見つかりません。', 'Flash/error');
			}
		}
	}

	public function edit($id = null)
	{
		$this->Researcher->id = $id;
		if ( !$this->Researcher->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// 研究者データ取得
		$researcher = $this->Researcher->find('first', array(
			'contain' => array(
				'ResearcherCareer',
				'ResearcherPrize',
				'ResearcherConference',
				'ResearcherBiblio',
				'ResearcherResearchKeyword',
				'ResearcherResearchArea',
				'ResearcherAcademicSociety',
				'ResearcherTeachingExperience',
				'ResearcherPaper',
				'ResearcherCompetitiveFund',
				'ResearcherOther',
				'ResearcherPatent',
				'ResearcherAcademicBackground',
				'ResearcherCommitteeCareer',
				'ResearcherSocialContribution'
			),
			'conditions' => array(
				'Researcher.id' => $id
			),
		));
		$this->set('researcher', $researcher);
		
		if ( $this->request->is('post') )
		{
			$this->request->data['Researcher']['id']				= $id;
			$this->request->data['Researcher']['latest_admin_id']	= $this->Auth->user('id');
			
			if ( $this->Researcher->save( $this->request->data ) )
			{
				$this->Session->setFlash('データを保存しました。', 'Flash/success');
				$this->redirect(array('action' => 'edit', $id));
			}
			else
			{
				$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
			}
			
		}
		else
		{
			$this->request->data = $researcher;
		}
	}

/*
	@type
	1 : 研究キーワード
	2 : 研究分野
	3 : 経歴
	4 : 学歴
	5 : 委員歴
	6 : 受賞
	7 : 論文
	8 : 書籍等出版物
	9 : 講演・口頭発表等
	10: 担当経験のある科目
	11: 所属学協会
	12: 競争的資金等の研究課題
	13: 特許
	14: 社会貢献活動
	15: その他
*/
	public function edit_detail($type = null, $id = null)
	{
		if ( !is_numeric($type))
		{
			throw new Exception('Invalid type');
		}
		$this->Researcher->id = $id;
		if ( !$this->Researcher->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// 研究者データ取得
		$researcher = $this->Researcher->find('first', array(
			'contain' => array(
				'ResearcherCareer',
				'ResearcherPrize',
				'ResearcherConference',
				'ResearcherBiblio',
				'ResearcherResearchKeyword',
				'ResearcherResearchArea',
				'ResearcherAcademicSociety',
				'ResearcherTeachingExperience',
				'ResearcherPaper',
				'ResearcherCompetitiveFund',
				'ResearcherOther',
				'ResearcherPatent',
				'ResearcherAcademicBackground',
				'ResearcherCommitteeCareer',
				'ResearcherSocialContribution'
			),
			'conditions' => array(
				'Researcher.id' => $id
			),
		));
		$this->set('researcher', $researcher);
		
		if ( $this->request->is('post') )
		{
			// 研究者関連テーブルの一覧を取得
			$tables	= Configure::read('App.researcher_detail_table');
			
			// 更新するテーブルを選択
			$table	= $tables[$type];
			
			// トランザクションの開始
			$rollback = false;
			$this->{$table}->begin();
			
			// 既存データの更新
			if ( isset($this->request->data[$table]) && !empty($this->request->data[$table]) )
			{
				foreach ( $this->request->data[$table] as $save )
				{
					$this->{$table}->set($save);
					if ( !$this->{$table}->save($save) )
					{
						$rollback = true;
						break;
					}
				}
			}
			
			if ( !$rollback )
			{
				// 新規データの挿入
				
				// どこかしか入力されているか
				if ( $this->_is_input($this->request->data['Add'][$table]) )
				{
					// 入力されている場合→新規挿入
					$save = array();
					$save = $this->request->data['Add'][$table];
					$save['researcher_id'] = $id;
					$this->{$table}->create();
					if ( !$this->{$table}->save($save) )
					{
						$rollback = true;
					}
				}
			}
			
			if ( !$rollback )
			{
				$save = array();
				$save['Researcher']['id']				= $id;
				$save['Researcher']['latest_admin_id']	= $this->Auth->user('id');
				if ( !$this->Researcher->save($save) )
				{
					$rollback = true;
				}
			}
			
			if ( !$rollback )
			{
				$this->{$table}->commit();
				$this->Session->setFlash('データを保存しました。', 'Flash/success');
				$this->redirect(array('action' => 'edit_detail', $type, $id));
			}
			else
			{
				$this->{$table}->rollback();
				$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
			}
			/*
			print_a($this->request->data[$table]);
			print_a($this->request->data['Add'][$table]);
			print_a($this->{$table});
			print_a_die($this->request->data);
			die();
			*/
		}
		else
		{
			$this->request->data = $researcher;
		}
		
		$this->set('type', $type);
	}

	public function rm_delete()
	{
		// デバッグ用
		// 関連DBの削除
		
		$this->Researcher->deleteAll(array('is_delete' => 0));
		$this->ResearcherCareer->deleteAll(array('is_delete' => 0));
		$this->ResearcherPrize->deleteAll(array('is_delete' => 0));
		$this->ResearcherConference->deleteAll(array('is_delete' => 0));
		$this->ResearcherBiblio->deleteAll(array('is_delete' => 0));
		$this->ResearcherResearchKeyword->deleteAll(array('is_delete' => 0));
		$this->ResearcherResearchArea->deleteAll(array('is_delete' => 0));
		$this->ResearcherAcademicSociety->deleteAll(array('is_delete' => 0));
		$this->ResearcherTeachingExperience->deleteAll(array('is_delete' => 0));
		$this->ResearcherPaper->deleteAll(array('is_delete' => 0));
		$this->ResearcherCompetitiveFund->deleteAll(array('is_delete' => 0));
		$this->ResearcherOther->deleteAll(array('is_delete' => 0));
		$this->ResearcherPatent->deleteAll(array('is_delete' => 0));
		$this->ResearcherAcademicBackground->deleteAll(array('is_delete' => 0));
		$this->ResearcherCommitteeCareer->deleteAll(array('is_delete' => 0));
		$this->ResearcherSocialContribution->deleteAll(array('is_delete' => 0));
		die();
	}

	public function search()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		if ( !isset($this->request->query['name']) || empty($this->request->query['name']) )
		{
			json_encode(false);
			die();
		}
		
		$start = 1;
		if ( isset($this->request->query['start']) && is_numeric($this->request->query['start']) )
		{
			$start = $this->request->query['start'];
		}
		
		$researcher_id = 0;
		if ( isset($this->request->query['researcher_id']) && !empty($this->request->query['researcher_id']) )
		{
			$researcher_id = $this->request->query['researcher_id'];
		}
		
		$this->appid = Configure::read('App.appid');
		
		$HttpSocket = new HttpSocket();
		$results = $HttpSocket->get(
			'https://api.researchmap.jp/opensearch/search',
			array(
				'appid' => $this->appid,
				'name' => $this->request->query['name'],
				'lang' => 'ja',
				'start' => $start
			)
		);

		$xml = simplexml_load_string($results->body);
		$nameSpaces = $xml->getNamespaces(true);
		
		$opensearchNode = $xml->children($nameSpaces['opensearch']);
		
		//print_a(json_decode(json_encode($opensearchNode), true));
		
		$ret = array();
		$ret['opensearch'] = json_decode(json_encode($opensearchNode), true);
		
		$p = $ret['opensearch']['totalResults'] / $ret['opensearch']['itemsPerPage'];
		$p = ceil($p); // 切り上げ
		
		$x = 1;
		for ( $i=1; $i <= $p; $i++)
		{
			$ret['opensearch']['paging'][$i] = '?name=' . $this->request->query['name'] . '&start=' . $x;
			$x = $x + 20;
		}
		
		$i=0;
		foreach ( $xml as $k => $x )
		{
			if ( $k == 'entry' )
			{
				$entry = $xml->entry[$i];
				$rmNode = $entry->children($nameSpaces['rm']);
				
				$detailLinks = array();
				$detailLinks = array(
					'basic'					=> $rmNode->detailLinks->basic->attributes()->href->__toString(),
					'career'				=> $rmNode->detailLinks->career->attributes()->href->__toString(),
					'prize'					=> $rmNode->detailLinks->prize->attributes()->href->__toString(),
					'conference'			=> $rmNode->detailLinks->conference->attributes()->href->__toString(),
					'biblio'				=> $rmNode->detailLinks->biblio->attributes()->href->__toString(),
					'researchKeyword'		=> $rmNode->detailLinks->researchKeyword->attributes()->href->__toString(),
					'researchArea'			=> $rmNode->detailLinks->researchArea->attributes()->href->__toString(),
					'academicSociety'		=> $rmNode->detailLinks->academicSociety->attributes()->href->__toString(),
					'teachingExperience'	=> $rmNode->detailLinks->teachingExperience->attributes()->href->__toString(),
					'paper'					=> $rmNode->detailLinks->paper->attributes()->href->__toString(),
					'misc'					=> $rmNode->detailLinks->misc->attributes()->href->__toString(),
					'work'					=> $rmNode->detailLinks->work->attributes()->href->__toString(),
					'competitiveFund'		=> $rmNode->detailLinks->competitiveFund->attributes()->href->__toString(),
					'other'					=> $rmNode->detailLinks->other->attributes()->href->__toString(),
					'patent'				=> $rmNode->detailLinks->patent->attributes()->href->__toString(),
					'academicBackground'	=> $rmNode->detailLinks->academicBackground->attributes()->href->__toString(),
					'committeeCareer'		=> $rmNode->detailLinks->committeeCareer->attributes()->href->__toString(),
					'socialContribution'	=> $rmNode->detailLinks->socialContribution->attributes()->href->__toString()
				);
				
				$data	= json_decode(json_encode($x), true);
				$detail	= json_decode(json_encode($rmNode), true);
				
				// スペースが入るのでスペース削除
				foreach ( $data as &$_data )
				{
					if ( !is_array($_data) )
					{
						$_data = trim($_data);
					}
				}
				
				foreach ( $detail as &$_detail )
				{
					if ( !is_array($_detail) )
					{
						$_detail = trim($_detail);
					}
				}
				
				$ret['User'][$i]['data']					= $data;
				$ret['User'][$i]['detail']					= $detail;
				$ret['User'][$i]['detail']['detailLinks']	= $detailLinks;
				$ret['User'][$i]['Researcher']['id']		= $researcher_id;
				$i++;
			}
		}
		
		//print_a_die($ret);
		
		echo json_encode($ret);
		die();
	}

	
	public function add_researcher()
	{
		$this->layout = false;
		$this->autoRender = false;
		
		if ( !isset($this->request->query['rm-id']) || empty($this->request->query['rm-id']) )
		{
			$ret['message'] = 'URLに不備がある為、登録出来ませんでした。';
			echo json_encode($ret);
			die();
		}
		
		$this->appid = Configure::read('App.appid');
		
		$url = $this->request->query['rm-id'] . '&lang=ja&appid=' . $this->appid;
		
		$HttpSocket = new HttpSocket();
		$results = $HttpSocket->get(
			$url,
			array()
		);
		
		// xml_to_json 名前空間つきのxmlでも欠損せず配列に変換出来る関数
		$xml = json_decode($this->xml_to_json($results), true);
		
		$count = $this->Researcher->find('count', array('conditions' => array(
			'Researcher.rm_id' => $xml['entry']['id']
		)));
		
		if ( $count > 0 )
		{
			$ret['message'] = '既に登録されています。';
			echo json_encode($ret);
			die();
		}
		
		// スペースが入るのでスペース削除
		foreach ( $xml['entry'] as &$entry )
		{
			if ( !is_array($entry) )
			{
				$entry = trim($entry);
			}
		}
		
		$detail = array();
		$detail['first'] = $xml;
		
		$links = array(
			'basic'					=> $xml['entry']['rm_detailLinks']['rm_basic@href'],
			'career'				=> $xml['entry']['rm_detailLinks']['rm_career@href'],
			'prize'					=> $xml['entry']['rm_detailLinks']['rm_prize@href'],
			'conference'			=> $xml['entry']['rm_detailLinks']['rm_conference@href'],
			'biblio'				=> $xml['entry']['rm_detailLinks']['rm_biblio@href'],
			'researchKeyword'		=> $xml['entry']['rm_detailLinks']['rm_researchKeyword@href'],
			'researchArea'			=> $xml['entry']['rm_detailLinks']['rm_researchArea@href'],
			'academicSociety'		=> $xml['entry']['rm_detailLinks']['rm_academicSociety@href'],
			'teachingExperience'	=> $xml['entry']['rm_detailLinks']['rm_teachingExperience@href'],
			'paper'					=> $xml['entry']['rm_detailLinks']['rm_paper@href'],
			'misc'					=> $xml['entry']['rm_detailLinks']['rm_misc@href'],
			'work'					=> $xml['entry']['rm_detailLinks']['rm_work@href'],
			'competitiveFund'		=> $xml['entry']['rm_detailLinks']['rm_competitiveFund@href'],
			'other'					=> $xml['entry']['rm_detailLinks']['rm_other@href'],
			'patent'				=> $xml['entry']['rm_detailLinks']['rm_patent@href'],
			'academicBackground'	=> $xml['entry']['rm_detailLinks']['rm_academicBackground@href'],
			'committeeCareer'		=> $xml['entry']['rm_detailLinks']['rm_committeeCareer@href'],
			'socialContribution'	=> $xml['entry']['rm_detailLinks']['rm_socialContribution@href'],
		);
		
		foreach ( $links  as $key => $url )
		{
			$detail[$key] = $this->get_rm_detail( $url, $key );
		}
		
		// 
		if ( empty($detail) )
		{
			$ret['message'] = '詳細データを取得出来ませんでした。';
			echo json_encode($ret);
			die();
		}
		
		// 初期化
		$researcher['Researcher']['rm_id']						= '';	//rm_id
		$researcher['Researcher']['name_ja']					= '';	// 氏名（日本語）
		$researcher['Researcher']['name_en']					= '';	// 氏名（英語）
		$researcher['Researcher']['name_kana']					= '';	// 氏名（かな）
		$researcher['Researcher']['email']						= '';	// email
		$researcher['Researcher']['url']						= '';	// URL
		$researcher['Researcher']['gender']						= '';	// 性別
		$researcher['Researcher']['birth_date']					= '';	// 生年月日
		$researcher['Researcher']['rm_affiliation_id']			= '';	// rm上の所属ID
		$researcher['Researcher']['affiliation']				= '';	// 所属
		$researcher['Researcher']['section']					= '';	// 部署
		$researcher['Researcher']['job']						= '';	// 職名
		$researcher['Researcher']['degree']						= '';	// 学位
		$researcher['Researcher']['other_affiliation_id']		= '';	// その他の所属rm上のID
		$researcher['Researcher']['other_affiliation']			= '';	// その他の所属名
		$researcher['Researcher']['other_affiliation_section']	= '';	// その他の所属部署
		$researcher['Researcher']['other_affiliation_job']		= '';	// その他の所属職名
		$researcher['Researcher']['kaken_id']					= '';	// 科研費ID
		$researcher['Researcher']['profile']					= '';	// プロフィール
		
		$researcher['ResearcherResearchKeyword']				= array();	// 研究キーワード
		$researcher['ResearcherResearchArea']					= array();	// 研究分野
		$researcher['ResearcherCareer']							= array();	// 経歴
		$researcher['ResearcherAcademicBackground']				= array();	// 学歴
		$researcher['ResearcherCommitteeCareer']				= array();	// 委員歴
		$researcher['ResearcherPrize']							= array();	// 受賞
		$researcher['ResearcherPaper']							= array();	// 論文
		$researcher['ResearcherBiblio']							= array();	// 書籍等出版物
		$researcher['ResearcherConference']						= array();	// 講演・口頭発表等
		$researcher['ResearcherTeachingExperience']				= array();	// 担当経験のある科目
		$researcher['ResearcherAcademicSociety']				= array();	// 所属学協会
		$researcher['ResearcherCompetitiveFund']				= array();	// 競争的資金等の研究課題
		$researcher['ResearcherPatent']							= array();	// 特許
		$researcher['ResearcherSocialContribution']				= array();	// 社会貢献活動
		$researcher['ResearcherOther']							= array();	// その他
		
		
		// 保存用データに当てはめていく
		$researcher['Researcher']['rm_id']	= $detail['first']['entry']['id'];			//rm_id
		foreach ( $detail['basic']['entry']['author'] as $author )
		{
			if ( $author['@attributes']['xml_lang'] == 'ja' )
			{
				$researcher['Researcher']['name_ja']		= $author['name'];
				$researcher['Researcher']['name_kana']		= ( isset($author['rm_nameKana']) )?$author['rm_nameKana']:'';
			}
			elseif ( $author['@attributes']['xml_lang'] == 'en' )
			{
				$researcher['Researcher']['name_en']		= $author['name'];
			}
		}
		
		//email
		if ( isset($detail['basic']['entry']['rm_email']) )
		{
			$researcher['Researcher']['email']	= ( is_string($detail['basic']['entry']['rm_email']) )?$detail['basic']['entry']['rm_email']:'';
		}
		
		//url
		if ( isset($detail['basic']['entry']['rm_url']) )
		{
			$researcher['Researcher']['url']	= ( is_string($detail['basic']['entry']['rm_url']) )?$detail['basic']['entry']['rm_url']:'';
		}
		
		//gender
		if ( isset($detail['basic']['entry']['rm_gender']) )
		{
			$researcher['Researcher']['gender']	= ( is_string($detail['basic']['entry']['rm_gender']) )?$detail['basic']['entry']['rm_gender']:'';
		}
		
		//birthDate
		if ( isset($detail['basic']['entry']['rm_birthDate']) )
		{
			$researcher['Researcher']['birthdate']	= ( is_string($detail['basic']['entry']['rm_birthDate']) )?$detail['basic']['entry']['rm_birthDate']:'';
		}
		
		//rm_affiliation_id
		if ( isset($detail['basic']['entry']['rm_affiliation']['id']) )
		{
			$researcher['Researcher']['rm_affiliation_id']	= ( is_string($detail['basic']['entry']['rm_affiliation']['id']) )?$detail['basic']['entry']['rm_affiliation']['id']:'';
		}
		
		//affiliation
		if ( isset($detail['basic']['entry']['rm_affiliation']['name']) )
		{
			$researcher['Researcher']['affiliation']	= ( is_string($detail['basic']['entry']['rm_affiliation']['name']) )?$detail['basic']['entry']['rm_affiliation']['name']:'';
		}
		
		//section
		if ( isset($detail['basic']['entry']['rm_section']) )
		{
			$researcher['Researcher']['section']	= ( is_string($detail['basic']['entry']['rm_section']) )?$detail['basic']['entry']['rm_section']:'';
		}
		
		//section
		if ( isset($detail['basic']['entry']['rm_job']) )
		{
			$researcher['Researcher']['job']	= ( is_string($detail['basic']['entry']['rm_job']) )?$detail['basic']['entry']['rm_job']:'';
		}
		
		//degree
		if ( isset($detail['basic']['entry']['rm_degree']['name']) )
		{
			$researcher['Researcher']['degree']	= ( is_string($detail['basic']['entry']['rm_degree']['name']) )?$detail['basic']['entry']['rm_degree']['name']:'';
		}
		
		//rm_otherAffiliation
		if ( isset($detail['basic']['entry']['rm_otherAffiliation']) && !empty($detail['basic']['entry']['rm_otherAffiliation']) )
		{
			$researcher['Researcher']['other_affiliation_id']		= ( is_string($detail['basic']['entry']['rm_otherAffiliation']['id']) )?$detail['basic']['entry']['rm_otherAffiliation']['id']:'';			// その他の所属rm上のID
			$researcher['Researcher']['other_affiliation']			= ( is_string($detail['basic']['entry']['rm_otherAffiliation']['name']) )?$detail['basic']['entry']['rm_otherAffiliation']['name']:'';			// その他の所属名
			$researcher['Researcher']['other_affiliation_section']	= ( is_string($detail['basic']['entry']['rm_otherAffiliation']['rm_section']) )?$detail['basic']['entry']['rm_otherAffiliation']['rm_section']:'';	// その他の所属部署
			$researcher['Researcher']['other_affiliation_job']		= ( is_string($detail['basic']['entry']['rm_otherAffiliation']['rm_job']) )?$detail['basic']['entry']['rm_otherAffiliation']['rm_job']:'';		// その他の所属職名
		}
		
		//kaken_id
		if ( isset($detail['first']['entry']['rm_kakenid']) )
		{
			$researcher['Researcher']['kaken_id']	= ( is_string($detail['first']['entry']['rm_kakenid']) )?$detail['first']['entry']['rm_kakenid']:'';
		}
		
		//profile
		if ( isset($detail['basic']['entry']['content']) )
		{
			$researcher['Researcher']['profile']	= ( is_string($detail['basic']['entry']['content']) )?$detail['basic']['entry']['content']:'';
		}
		
		// researchKeyword
		if ( isset($detail['researchKeyword']['entry']))
		{
			if ( isset($detail['researchKeyword']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['researchKeyword']['entry'];
				$detail['researchKeyword']['entry'] = array();
				$detail['researchKeyword']['entry'][] = $entry;
			}
			foreach ( $detail['researchKeyword']['entry'] as $key => $keyword )
			{
				$researcher['ResearcherResearchKeyword'][$key]['title']		= ( isset($keyword['title'])						&& is_string($keyword['title'])							)?$keyword['title']:'';
				$researcher['ResearcherResearchKeyword'][$key]['author']	= ( isset($keyword['author']['name'])				&& is_string($keyword['author']['name'])				)?$keyword['author']['name']:'';
				$researcher['ResearcherResearchKeyword'][$key]['link']		= ( isset($keyword['link']['@attributes']['href'])	&& is_string($keyword['link']['@attributes']['href'])	)?$keyword['link']['@attributes']['href']:'';
			}
		}
		
		// researchArea
		if ( isset($detail['researchArea']['entry']))
		{
			if ( isset($detail['researchArea']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['researchArea']['entry'];
				$detail['researchArea']['entry'] = array();
				$detail['researchArea']['entry'][] = $entry;
			}
			foreach ( $detail['researchArea']['entry'] as $key => $area )
			{
				$researcher['ResearcherResearchArea'][$key]['title']		= ( isset($area['title'])							&& is_string($area['title'])						)?$area['title']:'';
				$researcher['ResearcherResearchArea'][$key]['author']		= ( isset($area['author']['name'])					&& is_string($area['author']['name'])				)?$area['author']['name']:'';
				$researcher['ResearcherResearchArea'][$key]['link']			= ( isset($area['link']['@attributes']['href'])		&& is_string($area['link']['@attributes']['href'])	)?$area['link']['@attributes']['href']:'';
				$researcher['ResearcherResearchArea'][$key]['field_id']		= ( isset($area['rm_field']['id'])					&& is_string($area['rm_field']['id'])				)?$area['rm_field']['id']:'';
				$researcher['ResearcherResearchArea'][$key]['field_name']	= ( isset($area['rm_field']['name'])				&& is_string($area['rm_field']['name'])				)?$area['rm_field']['name']:'';
				$researcher['ResearcherResearchArea'][$key]['subject_id']	= ( isset($area['rm_subject']['id'])				&& is_string($area['rm_subject']['id'])	 			)?$area['rm_subject']['id']:'';
				$researcher['ResearcherResearchArea'][$key]['subject_name']	= ( isset($area['rm_subject']['name'])				&& is_string($area['rm_subject']['name'])			)?$area['rm_subject']['name']:'';
			}
		}
		
		// career
		if ( isset($detail['career']['entry']))
		{
			if ( isset($detail['career']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['career']['entry'];
				$detail['career']['entry'] = array();
				$detail['career']['entry'][] = $entry;
			}
			foreach ( $detail['career']['entry'] as $key => $career )
			{
				$researcher['ResearcherCareer'][$key]['title']			= ( isset($career['title']) 						&& is_string($career['title']) 							)?$career['title']:'';
				$researcher['ResearcherCareer'][$key]['author']			= ( isset($career['author']['name']) 		 		&& is_string($career['author']['name']) 				)?$career['author']['name']:'';
				$researcher['ResearcherCareer'][$key]['link']			= ( isset($career['link']['@attributes']['href']) 	&& is_string($career['link']['@attributes']['href']) 	)?$career['link']['@attributes']['href']:'';
				$researcher['ResearcherCareer'][$key]['from_date']		= ( isset($career['rm_fromDate'])	 				&& is_string($career['rm_fromDate'])					)?$career['rm_fromDate']:'';
				$researcher['ResearcherCareer'][$key]['to_date']		= ( isset($career['rm_toDate'])	 	 				&& is_string($career['rm_toDate'])	 					)?$career['rm_toDate']:'';
				$researcher['ResearcherCareer'][$key]['affiliation']	= ( isset($career['rm_affiliation']) 				&& is_string($career['rm_affiliation'])					)?$career['rm_affiliation']:'';
				$researcher['ResearcherCareer'][$key]['section']		= ( isset($career['rm_section'])	 				&& is_string($career['rm_section'])						)?$career['rm_section']:'';
				$researcher['ResearcherCareer'][$key]['job']			= ( isset($career['rm_job'])		 				&& is_string($career['rm_job'])							)?$career['rm_job']:'';
			}
		}
		
		// academicBackground
		if ( isset($detail['academicBackground']['entry']))
		{
			if ( isset($detail['academicBackground']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['academicBackground']['entry'];
				$detail['academicBackground']['entry'] = array();
				$detail['academicBackground']['entry'][] = $entry;
			}
			
			foreach ( $detail['academicBackground']['entry'] as $key => $academicBackground )
			{
				$researcher['ResearcherAcademicBackground'][$key]['title']					= ( isset($academicBackground['title'])							&& is_string($academicBackground['title'])							 )?$academicBackground['title']:'';
				$researcher['ResearcherAcademicBackground'][$key]['author']					= ( isset($academicBackground['author']['name'])				&& is_string($academicBackground['author']['name'])					 )?$academicBackground['author']['name']:'';
				$researcher['ResearcherAcademicBackground'][$key]['link']					= ( isset($academicBackground['link']['@attributes']['href'])	&& is_string($academicBackground['link']['@attributes']['href'])	 )?$academicBackground['link']['@attributes']['href']:'';
				$researcher['ResearcherAcademicBackground'][$key]['department_name']		= ( isset($academicBackground['rm_departmentName'])				&& is_string($academicBackground['rm_departmentName'])				 )?$academicBackground['rm_departmentName']:'';
				$researcher['ResearcherAcademicBackground'][$key]['subject_name']			= ( isset($academicBackground['rm_subjectName'])				&& is_string($academicBackground['rm_subjectName'])					 )?$academicBackground['rm_subjectName']:'';
				$researcher['ResearcherAcademicBackground'][$key]['country']				= ( isset($academicBackground['rm_country'])					&& is_string($academicBackground['rm_country'])						 )?$academicBackground['rm_country']:'';
				$researcher['ResearcherAcademicBackground'][$key]['from_date']				= ( isset($academicBackground['rm_fromDate'])					&& is_string($academicBackground['rm_fromDate'])					 )?$academicBackground['rm_fromDate']:'';
				$researcher['ResearcherAcademicBackground'][$key]['to_date']				= ( isset($academicBackground['rm_toDate'])						&& is_string($academicBackground['rm_toDate'])						 )?$academicBackground['rm_toDate']:'';
			}
		}
		
		// committeeCareer
		if ( isset($detail['committeeCareer']['entry']))
		{
			if ( isset($detail['committeeCareer']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['committeeCareer']['entry'];
				$detail['committeeCareer']['entry'] = array();
				$detail['committeeCareer']['entry'][] = $entry;
			}
			foreach ( $detail['committeeCareer']['entry'] as $key => $committeeCareer )
			{
				$researcher['ResearcherCommitteeCareer'][$key]['title']					= ( isset($committeeCareer['title'])						&& is_string($committeeCareer['title'])							)?$committeeCareer['title']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['author']				= ( isset($committeeCareer['author']['name'])				&& is_string($committeeCareer['author']['name'])				)?$committeeCareer['author']['name']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['link']					= ( isset($committeeCareer['link']['@attributes']['href'])	&& is_string($committeeCareer['link']['@attributes']['href'])	)?$committeeCareer['link']['@attributes']['href']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['from_date']				= ( isset($committeeCareer['rm_fromDate'])					&& is_string($committeeCareer['rm_fromDate'])					)?$committeeCareer['rm_fromDate']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['to_date']				= ( isset($committeeCareer['rm_toDate'])					&& is_string($committeeCareer['rm_toDate'])						)?$committeeCareer['rm_toDate']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['association']			= ( isset($committeeCareer['rm_association'])				&& is_string($committeeCareer['rm_association'])				)?$committeeCareer['rm_association']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['committee_type_id']		= ( isset($committeeCareer['rm_committeeType']['id'])		&& is_string($committeeCareer['rm_committeeType']['id'])		)?$committeeCareer['rm_committeeType']['id']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['committee_type_name']	= ( isset($committeeCareer['rm_committeeType']['name'])		&& is_string($committeeCareer['rm_committeeType']['name'])		)?$committeeCareer['rm_committeeType']['name']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['summary']				= ( isset($committeeCareer['summary'])						&& is_string($committeeCareer['summary'])						)?$committeeCareer['summary']:'';
			}
		}
		
		// prize
		if ( isset($detail['prize']['entry']))
		{
			if ( isset($detail['prize']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['prize']['entry'];
				$detail['prize']['entry'] = array();
				$detail['prize']['entry'][] = $entry;
			}
			foreach ( $detail['prize']['entry'] as $key => $prize )
			{
				$researcher['ResearcherPrize'][$key]['title']				= ( isset($prize['title'])							&& is_string($prize['title'])						)?$prize['title']:'';
				$researcher['ResearcherPrize'][$key]['author']				= ( isset($prize['author']['name'])					&& is_string($prize['author']['name'])				)?$prize['author']['name']:'';
				$researcher['ResearcherPrize'][$key]['link']				= ( isset($prize['link']['@attributes']['href'])	&& is_string($prize['link']['@attributes']['href'])	)?$prize['link']['@attributes']['href']:'';
				$researcher['ResearcherPrize'][$key]['summary']				= ( isset($prize['summary'])						&& is_string($prize['summary'])						)?$prize['summary']:'';
				$researcher['ResearcherPrize'][$key]['publication_date']	= ( isset($prize['rm_publicationDate'])				&& is_string($prize['rm_publicationDate'])			)?$prize['rm_publicationDate']:'';
				$researcher['ResearcherPrize'][$key]['association']			= ( isset($prize['rm_association'])					&& is_string($prize['rm_association'])				)?$prize['rm_association']:'';
				$researcher['ResearcherPrize'][$key]['subtitle']			= ( isset($prize['rm_subtitle'])					&& is_string($prize['rm_subtitle'])					)?$prize['rm_subtitle']:'';
				$researcher['ResearcherPrize'][$key]['partner']				= ( isset($prize['rm_partner'])						&& is_string($prize['rm_partner'])					)?$prize['rm_partner']:'';
				$researcher['ResearcherPrize'][$key]['prize_type_id']		= ( isset($prize['rm_prizeType']['id'])				&& is_string($prize['rm_prizeType']['id'])			)?$prize['rm_prizeType']['id']:'';
				$researcher['ResearcherPrize'][$key]['prize_type_name']		= ( isset($prize['rm_prizeType']['name'])			&& is_string($prize['rm_prizeType']['name'])		)?$prize['rm_prizeType']['name']:'';
				$researcher['ResearcherPrize'][$key]['country']				= ( isset($prize['rm_country'])						&& is_string($prize['rm_country'])					)?$prize['rm_country']:'';
			}
		}
		
		// paper
		if ( isset($detail['paper']['entry']))
		{
			if ( isset($detail['paper']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['paper']['entry'];
				$detail['paper']['entry'] = array();
				$detail['paper']['entry'][] = $entry;
			}
			foreach ( $detail['paper']['entry'] as $key => $paper )
			{
				$researcher['ResearcherPaper'][$key]['title']								= ( isset($paper['title'])									&& is_string($paper['title'])									)?$paper['title']:'';
				$researcher['ResearcherPaper'][$key]['link']								= ( isset($paper['link']['@attributes']['href'])			&& is_string($paper['link']['@attributes']['href'])				)?$paper['link']['@attributes']['href']:'';
				$researcher['ResearcherPaper'][$key]['author']								= ( isset($paper['author']['name'])							&& is_string($paper['author']['name'])							)?$paper['author']['name']:'';
				$researcher['ResearcherPaper'][$key]['summary']								= ( isset($paper['summary'])								&& is_string($paper['summary'])									)?$paper['summary']:'';
				$researcher['ResearcherPaper'][$key]['journal']								= ( isset($paper['rm_journal'])								&& is_string($paper['rm_journal'])								)?$paper['rm_journal']:'';
				$researcher['ResearcherPaper'][$key]['publisher']							= ( isset($paper['rm_publisher'])							&& is_string($paper['rm_publisher'])							)?$paper['rm_publisher']:'';
				$researcher['ResearcherPaper'][$key]['publication_name']					= ( isset($paper['rm_publicationName'])						&& is_string($paper['rm_publicationName'])						)?$paper['rm_publicationName']:'';
				$researcher['ResearcherPaper'][$key]['volume']								= ( isset($paper['rm_volume'])								&& is_string($paper['rm_volume'])								)?$paper['rm_volume']:'';
				$researcher['ResearcherPaper'][$key]['number']								= ( isset($paper['rm_number'])								&& is_string($paper['rm_number'])								)?$paper['rm_number']:'';
				$researcher['ResearcherPaper'][$key]['starting_page']						= ( isset($paper['rm_startingPage'])						&& is_string($paper['rm_startingPage'])							)?$paper['rm_startingPage']:'';
				$researcher['ResearcherPaper'][$key]['ending_page']							= ( isset($paper['rm_endingPage'])							&& is_string($paper['rm_endingPage'])							)?$paper['rm_endingPage']:'';
				$researcher['ResearcherPaper'][$key]['publication_date']					= ( isset($paper['rm_publicationDate'])						&& is_string($paper['rm_publicationDate'])						)?$paper['rm_publicationDate']:'';
				$researcher['ResearcherPaper'][$key]['referee']								= ( isset($paper['rm_referee'])																								)?1:0;
				$researcher['ResearcherPaper'][$key]['invited']								= ( isset($paper['rm_invited'])																								)?1:0;
				$researcher['ResearcherPaper'][$key]['language']							= ( isset($paper['rm_language'])							&& is_string($paper['rm_language'])								)?$paper['rm_language']:'';
				$researcher['ResearcherPaper'][$key]['paper_type_id']						= ( isset($paper['rm_paperType']['id'])						&& is_string($paper['rm_paperType']['id'])						)?$paper['rm_paperType']['id']:'';
				$researcher['ResearcherPaper'][$key]['paper_type_name']						= ( isset($paper['rm_paperType']['name'])					&& is_string($paper['rm_paperType']['name'])					)?$paper['rm_paperType']['name']:'';
				$researcher['ResearcherPaper'][$key]['issn']								= ( isset($paper['rm_issn'])								&& is_string($paper['rm_issn'])									)?$paper['rm_issn']:'';
				$researcher['ResearcherPaper'][$key]['doi']									= ( isset($paper['rm_doi'])									&& is_string($paper['rm_doi'])									)?$paper['rm_doi']:'';
				$researcher['ResearcherPaper'][$key]['naid']								= ( isset($paper['rm_naid'])								&& is_string($paper['rm_naid'])									)?$paper['rm_naid']:'';
				$researcher['ResearcherPaper'][$key]['pmid']								= ( isset($paper['rm_pmid'])								&& is_string($paper['rm_pmid'])									)?$paper['rm_pmid']:'';
				$researcher['ResearcherPaper'][$key]['permalink']							= ( isset($paper['rm_permalink']['@attributes']['href'])	&& is_string($paper['rm_permalink']['@attributes']['href'])		)?$paper['rm_permalink']['@attributes']['href']:'';
				$researcher['ResearcherPaper'][$key]['url']									= ( isset($paper['rm_url']['@attributes']['href'])			&& is_string($paper['rm_url']['@attributes']['href'])			)?$paper['rm_url']['@attributes']['href']:'';
				$researcher['ResearcherPaper'][$key]['nrid']								= ( isset($paper['rm_nrid'])								&& is_string($paper['rm_nrid'])									)?$paper['rm_nrid']:'';
				$researcher['ResearcherPaper'][$key]['jglobalid']							= ( isset($paper['rm_jglobalid'])							&& is_string($paper['rm_jglobalid'])							)?$paper['rm_jglobalid']:'';
			}
		}
		
		// biblio
		if ( isset($detail['biblio']['entry']))
		{
			if ( isset($detail['biblio']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['biblio']['entry'];
				$detail['biblio']['entry'] = array();
				$detail['biblio']['entry'][] = $entry;
			}
			foreach ( $detail['biblio']['entry'] as $key => $biblio )
			{
				$researcher['ResearcherBiblio'][$key]['title']								= ( isset($biblio['title'])							&& is_string($biblio['title'])							)?$biblio['title']:'';
				$researcher['ResearcherBiblio'][$key]['author']								= ( isset($biblio['author']['name'])				&& is_string($biblio['author']['name'])					)?$biblio['author']['name']:'';
				$researcher['ResearcherBiblio'][$key]['link']								= ( isset($biblio['link']['@attributes']['href'])	&& is_string($biblio['link']['@attributes']['href'])	)?$biblio['link']['@attributes']['href']:'';
				$researcher['ResearcherBiblio'][$key]['summary']							= ( isset($biblio['summary'])						&& is_string($biblio['summary'])						)?$biblio['summary']:'';
				$researcher['ResearcherBiblio'][$key]['publisher']							= ( isset($biblio['rm_publisher'])					&& is_string($biblio['rm_publisher'])					)?$biblio['rm_publisher']:'';
				$researcher['ResearcherBiblio'][$key]['publication_date']					= ( isset($biblio['rm_publicationDate'])			&& is_string($biblio['rm_publicationDate'])				)?$biblio['rm_publicationDate']:'';
				$researcher['ResearcherBiblio'][$key]['total_page_number']					= ( isset($biblio['rm_totalPageNumber'])			&& is_string($biblio['rm_totalPageNumber'])				)?$biblio['rm_totalPageNumber']:'';
				$researcher['ResearcherBiblio'][$key]['rep_page_number']					= ( isset($biblio['rm_repPageNumber'])				&& is_string($biblio['rm_repPageNumber'])				)?$biblio['rm_repPageNumber']:'';
				$researcher['ResearcherBiblio'][$key]['amount']								= ( isset($biblio['rm_amount'])						&& is_string($biblio['rm_amount'])						)?$biblio['rm_amount']:'';
				$researcher['ResearcherBiblio'][$key]['isbn']								= ( isset($biblio['rm_isbn'])						&& is_string($biblio['rm_isbn'])						)?$biblio['rm_isbn']:'';
				$researcher['ResearcherBiblio'][$key]['asin']								= ( isset($biblio['rm_asin'])						&& is_string($biblio['rm_asin'])						)?$biblio['rm_asin']:'';
				$researcher['ResearcherBiblio'][$key]['author_type_id']						= ( isset($biblio['rm_authorType']['id'])			&& is_string($biblio['rm_authorType']['id'])			)?$biblio['rm_authorType']['id']:'';
				$researcher['ResearcherBiblio'][$key]['author_type_name']					= ( isset($biblio['rm_authorType']['name'])			&& is_string($biblio['rm_authorType']['name'])			)?$biblio['rm_authorType']['name']:'';
				$researcher['ResearcherBiblio'][$key]['part_area']							= ( isset($biblio['rm_partArea'])					&& is_string($biblio['rm_partArea'])					)?$biblio['rm_partArea']:'';
				$researcher['ResearcherBiblio'][$key]['amazon_url']							= ( isset($biblio['rm_amazonUrl'])					&& is_string($biblio['rm_amazonUrl'])					)?$biblio['rm_amazonUrl']:'';
				$researcher['ResearcherBiblio'][$key]['small_image_url']					= ( isset($biblio['rm_smallImageUrl'])				&& is_string($biblio['rm_smallImageUrl'])				)?$biblio['rm_smallImageUrl']:'';
				$researcher['ResearcherBiblio'][$key]['medium_image_url']					= ( isset($biblio['rm_mediumImageUrl'])				&& is_string($biblio['rm_mediumImageUrl'])				)?$biblio['rm_mediumImageUrl']:'';
				$researcher['ResearcherBiblio'][$key]['large_image_url']					= ( isset($biblio['rm_largeImageUrl'])				&& is_string($biblio['rm_largeImageUrl'])				)?$biblio['rm_largeImageUrl']:'';
				$researcher['ResearcherBiblio'][$key]['language']							= ( isset($biblio['rm_language'])					&& is_string($biblio['rm_language'])					)?$biblio['rm_language']:'';
				$researcher['ResearcherBiblio'][$key]['biblio_type_id']						= ( isset($biblio['rm_biblioType']['id'])			&& is_string($biblio['rm_biblioType']['id'])			)?$biblio['rm_biblioType']['id']:'';
				$researcher['ResearcherBiblio'][$key]['biblio_type_name']					= ( isset($biblio['rm_biblioType']['name'])			&& is_string($biblio['rm_biblioType']['name'])			)?$biblio['rm_biblioType']['name']:'';
			}
		}
		
		// conference
		if ( isset($detail['conference']['entry']))
		{
			if ( isset($detail['conference']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['conference']['entry'];
				$detail['conference']['entry'] = array();
				$detail['conference']['entry'][] = $entry;
			}
			foreach ( $detail['conference']['entry'] as $key => $conference )
			{
				$researcher['ResearcherConference'][$key]['title']							= ( isset($conference['title'])							&& is_string($conference['title'])							)?$conference['title']:'';
				$researcher['ResearcherConference'][$key]['author']							= ( isset($conference['author']['name'])				&& is_string($conference['author']['name'])					)?$conference['author']['name']:'';
				$researcher['ResearcherConference'][$key]['link']							= ( isset($conference['link']['@attributes']['href'])	&& is_string($conference['link']['@attributes']['href'])	)?$conference['link']['@attributes']['href']:'';
				$researcher['ResearcherConference'][$key]['summary']						= ( isset($conference['summary'])						&& is_string($conference['summary'])						)?$conference['summary']:'';
				$researcher['ResearcherConference'][$key]['journal']						= ( isset($conference['rm_journal'])					&& is_string($conference['rm_journal'])						)?$conference['rm_journal']:'';
				$researcher['ResearcherConference'][$key]['publication_date']				= ( isset($conference['rm_publicationDate'])			&& is_string($conference['rm_publicationDate'])				)?$conference['rm_publicationDate']:'';
				$researcher['ResearcherConference'][$key]['invited']						= ( isset($conference['rm_invited'])																				)?1:0;
				$researcher['ResearcherConference'][$key]['language']						= ( isset($conference['rm_language'])					&& is_string($conference['rm_language'])					)?$conference['rm_language']:'';
				$researcher['ResearcherConference'][$key]['conference_class']				= ( isset($conference['rm_conferenceClass'])			&& is_string($conference['rm_conferenceClass'])				)?$conference['rm_conferenceClass']:'';
				$researcher['ResearcherConference'][$key]['conference_type_id']				= ( isset($conference['rm_conferenceType']['id'])		&& is_string($conference['rm_conferenceType']['id'])		)?$conference['rm_conferenceType']['id']:'';
				$researcher['ResearcherConference'][$key]['conference_type_name']			= ( isset($conference['rm_conferenceType']['name'])		&& is_string($conference['rm_conferenceType']['name'])		)?$conference['rm_conferenceType']['name']:'';
				$researcher['ResearcherConference'][$key]['promoter']						= ( isset($conference['rm_promoter'])					&& is_string($conference['rm_promoter'])					)?$conference['rm_promoter']:'';
				$researcher['ResearcherConference'][$key]['venue']							= ( isset($conference['rm_venue'])						&& is_string($conference['rm_venue'])						)?$conference['rm_venue']:'';
			}
		}
		
		// teachingExperience
		if ( isset($detail['teachingExperience']['entry']))
		{
			if ( isset($detail['teachingExperience']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['teachingExperience']['entry'];
				$detail['teachingExperience']['entry'] = array();
				$detail['teachingExperience']['entry'][] = $entry;
			}
			foreach ( $detail['teachingExperience']['entry'] as $key => $teachingExperience )
			{
				$researcher['ResearcherTeachingExperience'][$key]['title']					= ( isset($teachingExperience['title'])							&& is_string($teachingExperience['title'])							)?$teachingExperience['title']:'';
				$researcher['ResearcherTeachingExperience'][$key]['author']					= ( isset($teachingExperience['author']['name'])				&& is_string($teachingExperience['author']['name'])					)?$teachingExperience['author']['name']:'';
				$researcher['ResearcherTeachingExperience'][$key]['link']					= ( isset($teachingExperience['link']['@attributes']['href'])	&& is_string($teachingExperience['link']['@attributes']['href'])	)?$teachingExperience['link']['@attributes']['href']:'';
				$researcher['ResearcherTeachingExperience'][$key]['affiliation']			= ( isset($teachingExperience['rm_affiliation'])				&& is_string($teachingExperience['rm_affiliation'])					)?$teachingExperience['rm_affiliation']:'';
				$researcher['ResearcherTeachingExperience'][$key]['summaryid']				= ( isset($teachingExperience['rm_summary']['rm_summaryid'])	&& is_string($teachingExperience['rm_summary']['rm_summaryid'])		)?$teachingExperience['rm_summary']['rm_summaryid']:'';
				$researcher['ResearcherTeachingExperience'][$key]['count']					= ( isset($teachingExperience['rm_summary']['rm_count'])		&& is_string($teachingExperience['rm_summary']['rm_count'])			)?$teachingExperience['rm_summary']['rm_count']:'';
			}
		}
		
		// academicSociety
		if ( isset($detail['academicSociety']['entry']))
		{
			if ( isset($detail['academicSociety']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['academicSociety']['entry'];
				$detail['academicSociety']['entry'] = array();
				$detail['academicSociety']['entry'][] = $entry;
			}
			foreach ( $detail['academicSociety']['entry'] as $key => $academicSociety )
			{
				$researcher['ResearcherAcademicSociety'][$key]['title']						= ( isset($academicSociety['title'])						&& is_string($academicSociety['title'])							)?$academicSociety['title']:'';
				$researcher['ResearcherAcademicSociety'][$key]['author']					= ( isset($academicSociety['author']['name'])				&& is_string($academicSociety['author']['name'])				)?$academicSociety['author']['name']:'';
				$researcher['ResearcherAcademicSociety'][$key]['link']						= ( isset($academicSociety['link']['@attributes']['href'])	&& is_string($academicSociety['link']['@attributes']['href'])	)?$academicSociety['link']['@attributes']['href']:'';
				$researcher['ResearcherAcademicSociety'][$key]['summaryid']					= ( isset($academicSociety['rm_summary']['rm_summaryid'])	&& is_string($academicSociety['rm_summary']['rm_summaryid'])	)?$academicSociety['rm_summary']['rm_summaryid']:'';
				$researcher['ResearcherAcademicSociety'][$key]['count']						= ( isset($academicSociety['rm_summary']['rm_count'])		&& is_string($academicSociety['rm_summary']['rm_count'])		)?$academicSociety['rm_summary']['rm_count']:'';
			}
		}
		
		// competitiveFund
		if ( isset($detail['competitiveFund']['entry']))
		{
			if ( isset($detail['competitiveFund']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['competitiveFund']['entry'];
				$detail['competitiveFund']['entry'] = array();
				$detail['competitiveFund']['entry'][] = $entry;
			}
			foreach ( $detail['competitiveFund']['entry'] as $key => $competitiveFund )
			{
				$researcher['ResearcherCompetitiveFund'][$key]['title']						= ( isset($competitiveFund['title'])							&& is_string($competitiveFund['title'])							)?$competitiveFund['title']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['author']					= ( isset($competitiveFund['author']['name'])					&& is_string($competitiveFund['author']['name'])				)?$competitiveFund['author']['name']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['link']						= ( isset($competitiveFund['link']['@attributes']['href'])		&& is_string($competitiveFund['link']['@attributes']['href'])	)?$competitiveFund['link']['@attributes']['href']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['summary']					= ( isset($competitiveFund['summary'])							&& is_string($competitiveFund['summary'])						)?$competitiveFund['summary']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['provider']					= ( isset($competitiveFund['rm_provider'])						&& is_string($competitiveFund['rm_provider'])					)?$competitiveFund['rm_provider']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['system']					= ( isset($competitiveFund['rm_system'])						&& is_string($competitiveFund['rm_system'])						)?$competitiveFund['rm_system']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['from_date']					= ( isset($competitiveFund['rm_fromDate'])						&& is_string($competitiveFund['rm_fromDate'])					)?$competitiveFund['rm_fromDate']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['to_date']					= ( isset($competitiveFund['rm_toDate'])						&& is_string($competitiveFund['rm_toDate'])						)?$competitiveFund['rm_toDate']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['member']					= ( isset($competitiveFund['rm_member'])						&& is_string($competitiveFund['rm_member'])						)?$competitiveFund['rm_member']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['referee_type']				= ( isset($competitiveFund['rm_refereeType'])					&& is_string($competitiveFund['rm_refereeType'])				)?$competitiveFund['rm_refereeType']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['field']						= ( isset($competitiveFund['rm_field'])							&& is_string($competitiveFund['rm_field'])						)?$competitiveFund['rm_field']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['category']					= ( isset($competitiveFund['rm_category'])						&& is_string($competitiveFund['rm_category'])					)?$competitiveFund['rm_category']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['grant_amount_total']		= ( isset($competitiveFund['rm_grantAmount']['rm_total'])		&& is_string($competitiveFund['rm_grantAmount']['rm_total'])	)?$competitiveFund['rm_grantAmount']['rm_total']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['grant_amount_direct']		= ( isset($competitiveFund['rm_grantAmount']['rm_direct'])		&& is_string($competitiveFund['rm_grantAmount']['rm_direct'])	)?$competitiveFund['rm_grantAmount']['rm_direct']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['grant_amount_indirect']		= ( isset($competitiveFund['rm_grantAmount']['rm_indirect'])	&& is_string($competitiveFund['rm_grantAmount']['rm_indirect'])	)?$competitiveFund['rm_grantAmount']['rm_indirect']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['researchid']				= ( isset($competitiveFund['rm_researchid'])					&& is_string($competitiveFund['rm_researchid'])					)?$competitiveFund['rm_researchid']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['institution']				= ( isset($competitiveFund['rm_institution'])					&& is_string($competitiveFund['rm_institution'])				)?$competitiveFund['rm_institution']:'';
			}
		}
		// patent
		if ( isset($detail['patent']['entry']))
		{
			if ( isset($detail['patent']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['patent']['entry'];
				$detail['patent']['entry'] = array();
				$detail['patent']['entry'][] = $entry;
			}
			foreach ( $detail['patent']['entry'] as $key => $patent )
			{
				$researcher['ResearcherPatent'][$key]['title']								= ( isset($patent['title'])									&& is_string($patent['title'])									)?$patent['title']:'';
				$researcher['ResearcherPatent'][$key]['author']								= ( isset($patent['author']['name'])						&& is_string($patent['author']['name'])							)?$patent['author']['name']:'';
				$researcher['ResearcherPatent'][$key]['link']								= ( isset($patent['link']['@attributes']['href'])			&& is_string($patent['link']['@attributes']['href'])			)?$patent['link']['@attributes']['href']:'';
				$researcher['ResearcherPatent'][$key]['summary']							= ( isset($patent['summary'])								&& is_string($patent['summary'])								)?$patent['summary']:'';
				$researcher['ResearcherPatent'][$key]['application_id']						= ( isset($patent['rm_application']['id'])					&& is_string($patent['rm_application']['id'])					)?$patent['rm_application']['id']:'';
				$researcher['ResearcherPatent'][$key]['application_application_date']		= ( isset($patent['rm_application']['rm_applicationDate'])	&& is_string($patent['rm_application']['rm_applicationDate'])	)?$patent['rm_application']['rm_applicationDate']:'';
				$researcher['ResearcherPatent'][$key]['public_id']							= ( isset($patent['rm_public']['id'])						&& is_string($patent['rm_public']['id'])						)?$patent['rm_public']['id']:'';
				$researcher['ResearcherPatent'][$key]['public_public_date']					= ( isset($patent['rm_public']['rm_publicDate'])			&& is_string($patent['rm_public']['rm_publicDate'])				)?$patent['rm_public']['rm_publicDate']:'';
				$researcher['ResearcherPatent'][$key]['translation_id']						= ( isset($patent['rm_translation']['id'])					&& is_string($patent['rm_translation']['id'])					)?$patent['rm_translation']['id']:'';
				$researcher['ResearcherPatent'][$key]['translation_translation_date']		= ( isset($patent['rm_translation']['rm_translationDate'])	&& is_string($patent['rm_translation']['rm_translationDate'])	)?$patent['rm_translation']['rm_translationDate']:'';
				$researcher['ResearcherPatent'][$key]['patent_id']							= ( isset($patent['rm_patent']['id'])						&& is_string($patent['rm_patent']['id'])						)?$patent['rm_patent']['id']:'';
				$researcher['ResearcherPatent'][$key]['patent_patent_date']					= ( isset($patent['rm_patent']['rm_patentDate'])			&& is_string($patent['rm_patent']['rm_patentDate'])				)?$patent['rm_patent']['rm_patentDate']:'';
				$researcher['ResearcherPatent'][$key]['application_person']					= ( isset($patent['rm_applicationPerson'])					&& is_string($patent['rm_applicationPerson'])					)?$patent['rm_applicationPerson']:'';
				$researcher['ResearcherPatent'][$key]['jglobalid']							= ( isset($patent['rm_jglobalid'])							&& is_string($patent['rm_jglobalid'])							)?$patent['rm_jglobalid']:'';
			}
		}
		
		// socialContribution
		if ( isset($detail['socialContribution']['entry']))
		{
			if ( isset($detail['socialContribution']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['socialContribution']['entry'];
				$detail['socialContribution']['entry'] = array();
				$detail['socialContribution']['entry'][] = $entry;
			}
			foreach ( $detail['socialContribution']['entry'] as $key => $socialContribution )
			{
				$researcher['ResearcherSocialContribution'][$key]['title']					= ( isset($socialContribution['title'])							&& is_string($socialContribution['title'])							)?$socialContribution['title']:'';
				$researcher['ResearcherSocialContribution'][$key]['author']					= ( isset($socialContribution['author']['name'])				&& is_string($socialContribution['author']['name'])					)?$socialContribution['author']['name']:'';
				$researcher['ResearcherSocialContribution'][$key]['link']					= ( isset($socialContribution['link']['@attributes']['href'])	&& is_string($socialContribution['link']['@attributes']['href'])	)?$socialContribution['link']['@attributes']['href']:'';
				$researcher['ResearcherSocialContribution'][$key]['summary']				= ( isset($socialContribution['summary'])						&& is_string($socialContribution['summary'])						)?$socialContribution['summary']:'';
				$researcher['ResearcherSocialContribution'][$key]['role_id']				= ( isset($socialContribution['rm_role']['id'])					&& is_string($socialContribution['rm_role']['id'])					)?$socialContribution['rm_role']['id']:'';
				$researcher['ResearcherSocialContribution'][$key]['role_name']				= ( isset($socialContribution['rm_role']['name'])				&& is_string($socialContribution['rm_role']['name'])				)?$socialContribution['rm_role']['name']:'';
				$researcher['ResearcherSocialContribution'][$key]['promoter']				= ( isset($socialContribution['rm_promoter'])					&& is_string($socialContribution['rm_promoter'])					)?$socialContribution['rm_promoter']:'';
				$researcher['ResearcherSocialContribution'][$key]['event']					= ( isset($socialContribution['rm_event'])						&& is_string($socialContribution['rm_event'])						)?$socialContribution['rm_event']:'';
				$researcher['ResearcherSocialContribution'][$key]['from_date']				= ( isset($socialContribution['rm_fromDate'])					&& is_string($socialContribution['rm_fromDate'])					)?$socialContribution['rm_fromDate']:'';
				$researcher['ResearcherSocialContribution'][$key]['to_date']				= ( isset($socialContribution['rm_toDate'])						&& is_string($socialContribution['rm_toDate'])						)?$socialContribution['rm_toDate']:'';
				$researcher['ResearcherSocialContribution'][$key]['location']				= ( isset($socialContribution['rm_location'])					&& is_string($socialContribution['rm_location'])					)?$socialContribution['rm_location']:'';
				$researcher['ResearcherSocialContribution'][$key]['event_type_id']			= ( isset($socialContribution['rm_eventType']['id'])			&& is_string($socialContribution['rm_eventType']['id'])				)?$socialContribution['rm_eventType']['id']:'';
				$researcher['ResearcherSocialContribution'][$key]['event_type_name']		= ( isset($socialContribution['rm_eventType']['name'])			&& is_string($socialContribution['rm_eventType']['name'])			)?$socialContribution['rm_eventType']['name']:'';
				$researcher['ResearcherSocialContribution'][$key]['target_id']				= ( isset($socialContribution['rm_target']['id'])				&& is_string($socialContribution['rm_target']['id'])				)?$socialContribution['rm_target']['id']:'';
				$researcher['ResearcherSocialContribution'][$key]['target_name']			= ( isset($socialContribution['rm_target']['name'])				&& is_string($socialContribution['rm_target']['name'])				)?$socialContribution['rm_target']['name']:'';
			}
		}
		
		// other
		if ( isset($detail['other']['entry']))
		{
			if ( isset($detail['other']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['other']['entry'];
				$detail['other']['entry'] = array();
				$detail['other']['entry'][] = $entry;
			}
			foreach ( $detail['other']['entry'] as $key => $other )
			{
				$researcher['ResearcherOther'][$key]['title']				= ( isset($other['title'])							&& is_string($other['title'])							)?$other['title']:'';
				$researcher['ResearcherOther'][$key]['author']				= ( isset($other['author']['name'])					&& is_string($other['author']['name'])					)?$other['author']['name']:'';
				$researcher['ResearcherOther'][$key]['link']				= ( isset($other['link']['@attributes']['href'])	&& is_string($other['link']['@attributes']['href'])		)?$other['link']['@attributes']['href']:'';
				$researcher['ResearcherOther'][$key]['summary']				= ( isset($other['summary'])						&& is_string($other['summary'])							)?$other['summary']:'';
				$researcher['ResearcherOther'][$key]['publication_date']	= ( isset($other['rm_publicationDate'])				&& is_string($other['rm_publicationDate'])				)?$other['rm_publicationDate']:'';
			}
		}
		
		
		$ret = '';
		$last_id = 0;
		$rollback = false;
		
		$this->Researcher->begin();
		
		$researcher['Researcher']['last_rm_date'] = date('Y-m-d H:i:s');
		$save['Researcher'] = $researcher['Researcher'];
		
		if ( $this->Researcher->save($save) )
		{
			$last_id = $this->Researcher->getLastInsertID();
		}
		else
		{
			$rollback = true;
		}
		
		// ResearcherResearchKeyword	研究キーワード
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherResearchKeyword']) )
			{
				foreach ( $researcher['ResearcherResearchKeyword'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherResearchKeyword->create();
					if ( !$this->ResearcherResearchKeyword->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherResearchArea	研究分野
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherResearchArea']) )
			{
				foreach ( $researcher['ResearcherResearchArea'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherResearchArea->create();
					if ( !$this->ResearcherResearchArea->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherCareer	経歴
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherCareer']) )
			{
				foreach ( $researcher['ResearcherCareer'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherCareer->create();
					if ( !$this->ResearcherCareer->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherAcademicBackground	学歴
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherAcademicBackground']) )
			{
				foreach ( $researcher['ResearcherAcademicBackground'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherAcademicBackground->create();
					if ( !$this->ResearcherAcademicBackground->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherCommitteeCareer	委員歴
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherCommitteeCareer']) )
			{
				foreach ( $researcher['ResearcherCommitteeCareer'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherCommitteeCareer->create();
					if ( !$this->ResearcherCommitteeCareer->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherPrize	受賞
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherPrize']) )
			{
				foreach ( $researcher['ResearcherPrize'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherPrize->create();
					if ( !$this->ResearcherPrize->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherPaper		論文
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherPaper']) )
			{
				foreach ( $researcher['ResearcherPaper'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherPaper->create();
					if ( !$this->ResearcherPaper->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherBiblio	書籍等出版物
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherBiblio']) )
			{
				foreach ( $researcher['ResearcherBiblio'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherBiblio->create();
					if ( !$this->ResearcherBiblio->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherConference	講演・口頭発表等
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherConference']) )
			{
				foreach ( $researcher['ResearcherConference'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherConference->create();
					if ( !$this->ResearcherConference->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherTeachingExperience			担当経験のある科目
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherTeachingExperience']) )
			{
				foreach ( $researcher['ResearcherTeachingExperience'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherTeachingExperience->create();
					if ( !$this->ResearcherTeachingExperience->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherAcademicSociety	所属学協会
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherAcademicSociety']) )
			{
				foreach ( $researcher['ResearcherAcademicSociety'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherAcademicSociety->create();
					if ( !$this->ResearcherAcademicSociety->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherCompetitiveFund	競争的資金等の研究課題
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherCompetitiveFund']) )
			{
				foreach ( $researcher['ResearcherCompetitiveFund'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherCompetitiveFund->create();
					if ( !$this->ResearcherCompetitiveFund->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherPatent	特許
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherPatent']) )
			{
				foreach ( $researcher['ResearcherPatent'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherPatent->create();
					if ( !$this->ResearcherPatent->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherSocialContribution	社会貢献活動
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherSocialContribution']) )
			{
				foreach ( $researcher['ResearcherSocialContribution'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherSocialContribution->create();
					if ( !$this->ResearcherSocialContribution->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherOther	その他
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherOther']) )
			{
				foreach ( $researcher['ResearcherOther'] as $data )
				{
					$data['researcher_id'] = $last_id;
					$this->ResearcherOther->create();
					if ( !$this->ResearcherOther->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		if ( !$rollback )
		{
			$ret['message'] = '研究者データベースに登録しました。';
			$this->Researcher->commit();
		}
		else
		{
			$ret['message'] = 'データの保存に失敗しました。';
			$this->Researcher->rollback();
		}
		
		echo json_encode($ret);
		die();
	}

	// researchmap更新
	public function update_researcher($researcher_id = null)
	{
		$this->layout = false;
		$this->autoRender = false;
		
		if ( !isset($this->request->query['rm-id']) || empty($this->request->query['rm-id']) )
		{
			$ret['message'] = 'URLに不備がある為、登録出来ませんでした。';
			echo json_encode($ret);
			die();
		}
		
		// 研究者ID
		if ( empty($researcher_id) )
		{
			$ret['message'] = 'URLに不備がある為、登録出来ませんでした。';
			echo json_encode($ret);
			die();
		}
		
		
		$this->appid = Configure::read('App.appid');
		
		$url = $this->request->query['rm-id'] . '&lang=ja&appid=' . $this->appid;
		
		$HttpSocket = new HttpSocket();
		$results = $HttpSocket->get(
			$url,
			array()
		);
		
		// xml_to_json 名前空間つきのxmlでも欠損せず配列に変換出来る関数
		$xml = json_decode($this->xml_to_json($results), true);
		
		$researcher = $this->Researcher->find('first', array(
			'conditions' => array('Researcher.id' => $researcher_id
			)
		));
		
		if ( empty($researcher))
		{
			$ret['message'] = 'データが見つかりませんでした。';
			echo json_encode($ret);
			die();
		}
		
		// スペースが入るのでスペース削除
		foreach ( $xml['entry'] as &$entry )
		{
			if ( !is_array($entry) )
			{
				$entry = trim($entry);
			}
		}
		
		$detail = array();
		$detail['first'] = $xml;
		
		$links = array(
			'basic'					=> $xml['entry']['rm_detailLinks']['rm_basic@href'],
			'career'				=> $xml['entry']['rm_detailLinks']['rm_career@href'],
			'prize'					=> $xml['entry']['rm_detailLinks']['rm_prize@href'],
			'conference'			=> $xml['entry']['rm_detailLinks']['rm_conference@href'],
			'biblio'				=> $xml['entry']['rm_detailLinks']['rm_biblio@href'],
			'researchKeyword'		=> $xml['entry']['rm_detailLinks']['rm_researchKeyword@href'],
			'researchArea'			=> $xml['entry']['rm_detailLinks']['rm_researchArea@href'],
			'academicSociety'		=> $xml['entry']['rm_detailLinks']['rm_academicSociety@href'],
			'teachingExperience'	=> $xml['entry']['rm_detailLinks']['rm_teachingExperience@href'],
			'paper'					=> $xml['entry']['rm_detailLinks']['rm_paper@href'],
			'misc'					=> $xml['entry']['rm_detailLinks']['rm_misc@href'],
			'work'					=> $xml['entry']['rm_detailLinks']['rm_work@href'],
			'competitiveFund'		=> $xml['entry']['rm_detailLinks']['rm_competitiveFund@href'],
			'other'					=> $xml['entry']['rm_detailLinks']['rm_other@href'],
			'patent'				=> $xml['entry']['rm_detailLinks']['rm_patent@href'],
			'academicBackground'	=> $xml['entry']['rm_detailLinks']['rm_academicBackground@href'],
			'committeeCareer'		=> $xml['entry']['rm_detailLinks']['rm_committeeCareer@href'],
			'socialContribution'	=> $xml['entry']['rm_detailLinks']['rm_socialContribution@href'],
		);
		
		foreach ( $links  as $key => $url )
		{
			$detail[$key] = $this->get_rm_detail( $url, $key );
		}
		
		// 
		if ( empty($detail) )
		{
			$ret['message'] = '詳細データを取得出来ませんでした。';
			echo json_encode($ret);
			die();
		}
		
		// 初期化
		$researcher['Researcher']['rm_id']						= '';	//rm_id
		$researcher['Researcher']['name_ja']					= '';	// 氏名（日本語）
		$researcher['Researcher']['name_en']					= '';	// 氏名（英語）
		$researcher['Researcher']['name_kana']					= '';	// 氏名（かな）
		$researcher['Researcher']['email']						= '';	// email
		$researcher['Researcher']['url']						= '';	// URL
		$researcher['Researcher']['gender']						= '';	// 性別
		$researcher['Researcher']['birth_date']					= '';	// 生年月日
		$researcher['Researcher']['rm_affiliation_id']			= '';	// rm上の所属ID
		$researcher['Researcher']['affiliation']				= '';	// 所属
		$researcher['Researcher']['section']					= '';	// 部署
		$researcher['Researcher']['job']						= '';	// 職名
		$researcher['Researcher']['degree']						= '';	// 学位
		$researcher['Researcher']['other_affiliation_id']		= '';	// その他の所属rm上のID
		$researcher['Researcher']['other_affiliation']			= '';	// その他の所属名
		$researcher['Researcher']['other_affiliation_section']	= '';	// その他の所属部署
		$researcher['Researcher']['other_affiliation_job']		= '';	// その他の所属職名
		$researcher['Researcher']['kaken_id']					= '';	// 科研費ID
		$researcher['Researcher']['profile']					= '';	// プロフィール
		
		$researcher['ResearcherResearchKeyword']				= array();	// 研究キーワード
		$researcher['ResearcherResearchArea']					= array();	// 研究分野
		$researcher['ResearcherCareer']							= array();	// 経歴
		$researcher['ResearcherAcademicBackground']				= array();	// 学歴
		$researcher['ResearcherCommitteeCareer']				= array();	// 委員歴
		$researcher['ResearcherPrize']							= array();	// 受賞
		$researcher['ResearcherPaper']							= array();	// 論文
		$researcher['ResearcherBiblio']							= array();	// 書籍等出版物
		$researcher['ResearcherConference']						= array();	// 講演・口頭発表等
		$researcher['ResearcherTeachingExperience']				= array();	// 担当経験のある科目
		$researcher['ResearcherAcademicSociety']				= array();	// 所属学協会
		$researcher['ResearcherCompetitiveFund']				= array();	// 競争的資金等の研究課題
		$researcher['ResearcherPatent']							= array();	// 特許
		$researcher['ResearcherSocialContribution']				= array();	// 社会貢献活動
		$researcher['ResearcherOther']							= array();	// その他
		
		
		// 保存用データに当てはめていく
		$researcher['Researcher']['rm_id']	= $detail['first']['entry']['id'];			//rm_id
		foreach ( $detail['basic']['entry']['author'] as $author )
		{
			if ( $author['@attributes']['xml_lang'] == 'ja' )
			{
				$researcher['Researcher']['name_ja']		= $author['name'];
				$researcher['Researcher']['name_kana']		= ( isset($author['rm_nameKana']) )?$author['rm_nameKana']:'';
			}
			elseif ( $author['@attributes']['xml_lang'] == 'en' )
			{
				$researcher['Researcher']['name_en']		= $author['name'];
			}
		}
		
		//email
		if ( isset($detail['basic']['entry']['rm_email']) )
		{
			$researcher['Researcher']['email']	= ( is_string($detail['basic']['entry']['rm_email']) )?$detail['basic']['entry']['rm_email']:'';
		}
		
		//url
		if ( isset($detail['basic']['entry']['rm_url']) )
		{
			$researcher['Researcher']['url']	= ( is_string($detail['basic']['entry']['rm_url']) )?$detail['basic']['entry']['rm_url']:'';
		}
		
		//gender
		if ( isset($detail['basic']['entry']['rm_gender']) )
		{
			$researcher['Researcher']['gender']	= ( is_string($detail['basic']['entry']['rm_gender']) )?$detail['basic']['entry']['rm_gender']:'';
		}
		
		//birthDate
		if ( isset($detail['basic']['entry']['rm_birthDate']) )
		{
			$researcher['Researcher']['birthdate']	= ( is_string($detail['basic']['entry']['rm_birthDate']) )?$detail['basic']['entry']['rm_birthDate']:'';
		}
		
		//rm_affiliation_id
		if ( isset($detail['basic']['entry']['rm_affiliation']['id']) )
		{
			$researcher['Researcher']['rm_affiliation_id']	= ( is_string($detail['basic']['entry']['rm_affiliation']['id']) )?$detail['basic']['entry']['rm_affiliation']['id']:'';
		}
		
		//affiliation
		if ( isset($detail['basic']['entry']['rm_affiliation']['name']) )
		{
			$researcher['Researcher']['affiliation']	= ( is_string($detail['basic']['entry']['rm_affiliation']['name']) )?$detail['basic']['entry']['rm_affiliation']['name']:'';
		}
		
		//section
		if ( isset($detail['basic']['entry']['rm_section']) )
		{
			$researcher['Researcher']['section']	= ( is_string($detail['basic']['entry']['rm_section']) )?$detail['basic']['entry']['rm_section']:'';
		}
		
		//section
		if ( isset($detail['basic']['entry']['rm_job']) )
		{
			$researcher['Researcher']['job']	= ( is_string($detail['basic']['entry']['rm_job']) )?$detail['basic']['entry']['rm_job']:'';
		}
		
		//degree
		if ( isset($detail['basic']['entry']['rm_degree']['name']) )
		{
			$researcher['Researcher']['degree']	= ( is_string($detail['basic']['entry']['rm_degree']['name']) )?$detail['basic']['entry']['rm_degree']['name']:'';
		}
		
		//rm_otherAffiliation
		if ( isset($detail['basic']['entry']['rm_otherAffiliation']) && !empty($detail['basic']['entry']['rm_otherAffiliation']) )
		{
			$researcher['Researcher']['other_affiliation_id']		= ( is_string($detail['basic']['entry']['rm_otherAffiliation']['id']) )?$detail['basic']['entry']['rm_otherAffiliation']['id']:'';			// その他の所属rm上のID
			$researcher['Researcher']['other_affiliation']			= ( is_string($detail['basic']['entry']['rm_otherAffiliation']['name']) )?$detail['basic']['entry']['rm_otherAffiliation']['name']:'';			// その他の所属名
			$researcher['Researcher']['other_affiliation_section']	= ( is_string($detail['basic']['entry']['rm_otherAffiliation']['rm_section']) )?$detail['basic']['entry']['rm_otherAffiliation']['rm_section']:'';	// その他の所属部署
			$researcher['Researcher']['other_affiliation_job']		= ( is_string($detail['basic']['entry']['rm_otherAffiliation']['rm_job']) )?$detail['basic']['entry']['rm_otherAffiliation']['rm_job']:'';		// その他の所属職名
		}
		
		//kaken_id
		if ( isset($detail['first']['entry']['rm_kakenid']) )
		{
			$researcher['Researcher']['kaken_id']	= ( is_string($detail['first']['entry']['rm_kakenid']) )?$detail['first']['entry']['rm_kakenid']:'';
		}
		
		//profile
		if ( isset($detail['basic']['entry']['content']) )
		{
			$researcher['Researcher']['profile']	= ( is_string($detail['basic']['entry']['content']) )?$detail['basic']['entry']['content']:'';
		}
		
		// researchKeyword
		if ( isset($detail['researchKeyword']['entry']))
		{
			if ( isset($detail['researchKeyword']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['researchKeyword']['entry'];
				$detail['researchKeyword']['entry'] = array();
				$detail['researchKeyword']['entry'][] = $entry;
			}
			foreach ( $detail['researchKeyword']['entry'] as $key => $keyword )
			{
				$researcher['ResearcherResearchKeyword'][$key]['title']		= ( isset($keyword['title'])						&& is_string($keyword['title'])							)?$keyword['title']:'';
				$researcher['ResearcherResearchKeyword'][$key]['author']	= ( isset($keyword['author']['name'])				&& is_string($keyword['author']['name'])				)?$keyword['author']['name']:'';
				$researcher['ResearcherResearchKeyword'][$key]['link']		= ( isset($keyword['link']['@attributes']['href'])	&& is_string($keyword['link']['@attributes']['href'])	)?$keyword['link']['@attributes']['href']:'';
			}
		}
		
		// researchArea
		if ( isset($detail['researchArea']['entry']))
		{
			if ( isset($detail['researchArea']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['researchArea']['entry'];
				$detail['researchArea']['entry'] = array();
				$detail['researchArea']['entry'][] = $entry;
			}
			foreach ( $detail['researchArea']['entry'] as $key => $area )
			{
				$researcher['ResearcherResearchArea'][$key]['title']		= ( isset($area['title'])							&& is_string($area['title'])						)?$area['title']:'';
				$researcher['ResearcherResearchArea'][$key]['author']		= ( isset($area['author']['name'])					&& is_string($area['author']['name'])				)?$area['author']['name']:'';
				$researcher['ResearcherResearchArea'][$key]['link']			= ( isset($area['link']['@attributes']['href'])		&& is_string($area['link']['@attributes']['href'])	)?$area['link']['@attributes']['href']:'';
				$researcher['ResearcherResearchArea'][$key]['field_id']		= ( isset($area['rm_field']['id'])					&& is_string($area['rm_field']['id'])				)?$area['rm_field']['id']:'';
				$researcher['ResearcherResearchArea'][$key]['field_name']	= ( isset($area['rm_field']['name'])				&& is_string($area['rm_field']['name'])				)?$area['rm_field']['name']:'';
				$researcher['ResearcherResearchArea'][$key]['subject_id']	= ( isset($area['rm_subject']['id'])				&& is_string($area['rm_subject']['id'])	 			)?$area['rm_subject']['id']:'';
				$researcher['ResearcherResearchArea'][$key]['subject_name']	= ( isset($area['rm_subject']['name'])				&& is_string($area['rm_subject']['name'])			)?$area['rm_subject']['name']:'';
			}
		}
		
		// career
		if ( isset($detail['career']['entry']))
		{
			if ( isset($detail['career']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['career']['entry'];
				$detail['career']['entry'] = array();
				$detail['career']['entry'][] = $entry;
			}
			foreach ( $detail['career']['entry'] as $key => $career )
			{
				$researcher['ResearcherCareer'][$key]['title']			= ( isset($career['title']) 						&& is_string($career['title']) 							)?$career['title']:'';
				$researcher['ResearcherCareer'][$key]['author']			= ( isset($career['author']['name']) 		 		&& is_string($career['author']['name']) 				)?$career['author']['name']:'';
				$researcher['ResearcherCareer'][$key]['link']			= ( isset($career['link']['@attributes']['href']) 	&& is_string($career['link']['@attributes']['href']) 	)?$career['link']['@attributes']['href']:'';
				$researcher['ResearcherCareer'][$key]['from_date']		= ( isset($career['rm_fromDate'])	 				&& is_string($career['rm_fromDate'])					)?$career['rm_fromDate']:'';
				$researcher['ResearcherCareer'][$key]['to_date']		= ( isset($career['rm_toDate'])	 	 				&& is_string($career['rm_toDate'])	 					)?$career['rm_toDate']:'';
				$researcher['ResearcherCareer'][$key]['affiliation']	= ( isset($career['rm_affiliation']) 				&& is_string($career['rm_affiliation'])					)?$career['rm_affiliation']:'';
				$researcher['ResearcherCareer'][$key]['section']		= ( isset($career['rm_section'])	 				&& is_string($career['rm_section'])						)?$career['rm_section']:'';
				$researcher['ResearcherCareer'][$key]['job']			= ( isset($career['rm_job'])		 				&& is_string($career['rm_job'])							)?$career['rm_job']:'';
			}
		}
		
		// academicBackground
		if ( isset($detail['academicBackground']['entry']))
		{
			if ( isset($detail['academicBackground']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['academicBackground']['entry'];
				$detail['academicBackground']['entry'] = array();
				$detail['academicBackground']['entry'][] = $entry;
			}
			
			foreach ( $detail['academicBackground']['entry'] as $key => $academicBackground )
			{
				$researcher['ResearcherAcademicBackground'][$key]['title']					= ( isset($academicBackground['title'])							&& is_string($academicBackground['title'])							 )?$academicBackground['title']:'';
				$researcher['ResearcherAcademicBackground'][$key]['author']					= ( isset($academicBackground['author']['name'])				&& is_string($academicBackground['author']['name'])					 )?$academicBackground['author']['name']:'';
				$researcher['ResearcherAcademicBackground'][$key]['link']					= ( isset($academicBackground['link']['@attributes']['href'])	&& is_string($academicBackground['link']['@attributes']['href'])	 )?$academicBackground['link']['@attributes']['href']:'';
				$researcher['ResearcherAcademicBackground'][$key]['department_name']		= ( isset($academicBackground['rm_departmentName'])				&& is_string($academicBackground['rm_departmentName'])				 )?$academicBackground['rm_departmentName']:'';
				$researcher['ResearcherAcademicBackground'][$key]['subject_name']			= ( isset($academicBackground['rm_subjectName'])				&& is_string($academicBackground['rm_subjectName'])					 )?$academicBackground['rm_subjectName']:'';
				$researcher['ResearcherAcademicBackground'][$key]['country']				= ( isset($academicBackground['rm_country'])					&& is_string($academicBackground['rm_country'])						 )?$academicBackground['rm_country']:'';
				$researcher['ResearcherAcademicBackground'][$key]['from_date']				= ( isset($academicBackground['rm_fromDate'])					&& is_string($academicBackground['rm_fromDate'])					 )?$academicBackground['rm_fromDate']:'';
				$researcher['ResearcherAcademicBackground'][$key]['to_date']				= ( isset($academicBackground['rm_toDate'])						&& is_string($academicBackground['rm_toDate'])						 )?$academicBackground['rm_toDate']:'';
			}
		}
		
		// committeeCareer
		if ( isset($detail['committeeCareer']['entry']))
		{
			if ( isset($detail['committeeCareer']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['committeeCareer']['entry'];
				$detail['committeeCareer']['entry'] = array();
				$detail['committeeCareer']['entry'][] = $entry;
			}
			foreach ( $detail['committeeCareer']['entry'] as $key => $committeeCareer )
			{
				$researcher['ResearcherCommitteeCareer'][$key]['title']					= ( isset($committeeCareer['title'])						&& is_string($committeeCareer['title'])							)?$committeeCareer['title']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['author']				= ( isset($committeeCareer['author']['name'])				&& is_string($committeeCareer['author']['name'])				)?$committeeCareer['author']['name']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['link']					= ( isset($committeeCareer['link']['@attributes']['href'])	&& is_string($committeeCareer['link']['@attributes']['href'])	)?$committeeCareer['link']['@attributes']['href']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['from_date']				= ( isset($committeeCareer['rm_fromDate'])					&& is_string($committeeCareer['rm_fromDate'])					)?$committeeCareer['rm_fromDate']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['to_date']				= ( isset($committeeCareer['rm_toDate'])					&& is_string($committeeCareer['rm_toDate'])						)?$committeeCareer['rm_toDate']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['association']			= ( isset($committeeCareer['rm_association'])				&& is_string($committeeCareer['rm_association'])				)?$committeeCareer['rm_association']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['committee_type_id']		= ( isset($committeeCareer['rm_committeeType']['id'])		&& is_string($committeeCareer['rm_committeeType']['id'])		)?$committeeCareer['rm_committeeType']['id']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['committee_type_name']	= ( isset($committeeCareer['rm_committeeType']['name'])		&& is_string($committeeCareer['rm_committeeType']['name'])		)?$committeeCareer['rm_committeeType']['name']:'';
				$researcher['ResearcherCommitteeCareer'][$key]['summary']				= ( isset($committeeCareer['summary'])						&& is_string($committeeCareer['summary'])						)?$committeeCareer['summary']:'';
			}
		}
		
		// prize
		if ( isset($detail['prize']['entry']))
		{
			if ( isset($detail['prize']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['prize']['entry'];
				$detail['prize']['entry'] = array();
				$detail['prize']['entry'][] = $entry;
			}
			foreach ( $detail['prize']['entry'] as $key => $prize )
			{
				$researcher['ResearcherPrize'][$key]['title']				= ( isset($prize['title'])							&& is_string($prize['title'])						)?$prize['title']:'';
				$researcher['ResearcherPrize'][$key]['author']				= ( isset($prize['author']['name'])					&& is_string($prize['author']['name'])				)?$prize['author']['name']:'';
				$researcher['ResearcherPrize'][$key]['link']				= ( isset($prize['link']['@attributes']['href'])	&& is_string($prize['link']['@attributes']['href'])	)?$prize['link']['@attributes']['href']:'';
				$researcher['ResearcherPrize'][$key]['summary']				= ( isset($prize['summary'])						&& is_string($prize['summary'])						)?$prize['summary']:'';
				$researcher['ResearcherPrize'][$key]['publication_date']	= ( isset($prize['rm_publicationDate'])				&& is_string($prize['rm_publicationDate'])			)?$prize['rm_publicationDate']:'';
				$researcher['ResearcherPrize'][$key]['association']			= ( isset($prize['rm_association'])					&& is_string($prize['rm_association'])				)?$prize['rm_association']:'';
				$researcher['ResearcherPrize'][$key]['subtitle']			= ( isset($prize['rm_subtitle'])					&& is_string($prize['rm_subtitle'])					)?$prize['rm_subtitle']:'';
				$researcher['ResearcherPrize'][$key]['partner']				= ( isset($prize['rm_partner'])						&& is_string($prize['rm_partner'])					)?$prize['rm_partner']:'';
				$researcher['ResearcherPrize'][$key]['prize_type_id']		= ( isset($prize['rm_prizeType']['id'])				&& is_string($prize['rm_prizeType']['id'])			)?$prize['rm_prizeType']['id']:'';
				$researcher['ResearcherPrize'][$key]['prize_type_name']		= ( isset($prize['rm_prizeType']['name'])			&& is_string($prize['rm_prizeType']['name'])		)?$prize['rm_prizeType']['name']:'';
				$researcher['ResearcherPrize'][$key]['country']				= ( isset($prize['rm_country'])						&& is_string($prize['rm_country'])					)?$prize['rm_country']:'';
			}
		}
		
		// paper
		if ( isset($detail['paper']['entry']))
		{
			if ( isset($detail['paper']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['paper']['entry'];
				$detail['paper']['entry'] = array();
				$detail['paper']['entry'][] = $entry;
			}
			foreach ( $detail['paper']['entry'] as $key => $paper )
			{
				$researcher['ResearcherPaper'][$key]['title']								= ( isset($paper['title'])									&& is_string($paper['title'])									)?$paper['title']:'';
				$researcher['ResearcherPaper'][$key]['link']								= ( isset($paper['link']['@attributes']['href'])			&& is_string($paper['link']['@attributes']['href'])				)?$paper['link']['@attributes']['href']:'';
				$researcher['ResearcherPaper'][$key]['author']								= ( isset($paper['author']['name'])							&& is_string($paper['author']['name'])							)?$paper['author']['name']:'';
				$researcher['ResearcherPaper'][$key]['summary']								= ( isset($paper['summary'])								&& is_string($paper['summary'])									)?$paper['summary']:'';
				$researcher['ResearcherPaper'][$key]['journal']								= ( isset($paper['rm_journal'])								&& is_string($paper['rm_journal'])								)?$paper['rm_journal']:'';
				$researcher['ResearcherPaper'][$key]['publisher']							= ( isset($paper['rm_publisher'])							&& is_string($paper['rm_publisher'])							)?$paper['rm_publisher']:'';
				$researcher['ResearcherPaper'][$key]['publication_name']					= ( isset($paper['rm_publicationName'])						&& is_string($paper['rm_publicationName'])						)?$paper['rm_publicationName']:'';
				$researcher['ResearcherPaper'][$key]['volume']								= ( isset($paper['rm_volume'])								&& is_string($paper['rm_volume'])								)?$paper['rm_volume']:'';
				$researcher['ResearcherPaper'][$key]['number']								= ( isset($paper['rm_number'])								&& is_string($paper['rm_number'])								)?$paper['rm_number']:'';
				$researcher['ResearcherPaper'][$key]['starting_page']						= ( isset($paper['rm_startingPage'])						&& is_string($paper['rm_startingPage'])							)?$paper['rm_startingPage']:'';
				$researcher['ResearcherPaper'][$key]['ending_page']							= ( isset($paper['rm_endingPage'])							&& is_string($paper['rm_endingPage'])							)?$paper['rm_endingPage']:'';
				$researcher['ResearcherPaper'][$key]['publication_date']					= ( isset($paper['rm_publicationDate'])						&& is_string($paper['rm_publicationDate'])						)?$paper['rm_publicationDate']:'';
				$researcher['ResearcherPaper'][$key]['referee']								= ( isset($paper['rm_referee'])																								)?1:0;
				$researcher['ResearcherPaper'][$key]['invited']								= ( isset($paper['rm_invited'])																								)?1:0;
				$researcher['ResearcherPaper'][$key]['language']							= ( isset($paper['rm_language'])							&& is_string($paper['rm_language'])								)?$paper['rm_language']:'';
				$researcher['ResearcherPaper'][$key]['paper_type_id']						= ( isset($paper['rm_paperType']['id'])						&& is_string($paper['rm_paperType']['id'])						)?$paper['rm_paperType']['id']:'';
				$researcher['ResearcherPaper'][$key]['paper_type_name']						= ( isset($paper['rm_paperType']['name'])					&& is_string($paper['rm_paperType']['name'])					)?$paper['rm_paperType']['name']:'';
				$researcher['ResearcherPaper'][$key]['issn']								= ( isset($paper['rm_issn'])								&& is_string($paper['rm_issn'])									)?$paper['rm_issn']:'';
				$researcher['ResearcherPaper'][$key]['doi']									= ( isset($paper['rm_doi'])									&& is_string($paper['rm_doi'])									)?$paper['rm_doi']:'';
				$researcher['ResearcherPaper'][$key]['naid']								= ( isset($paper['rm_naid'])								&& is_string($paper['rm_naid'])									)?$paper['rm_naid']:'';
				$researcher['ResearcherPaper'][$key]['pmid']								= ( isset($paper['rm_pmid'])								&& is_string($paper['rm_pmid'])									)?$paper['rm_pmid']:'';
				$researcher['ResearcherPaper'][$key]['permalink']							= ( isset($paper['rm_permalink']['@attributes']['href'])	&& is_string($paper['rm_permalink']['@attributes']['href'])		)?$paper['rm_permalink']['@attributes']['href']:'';
				$researcher['ResearcherPaper'][$key]['url']									= ( isset($paper['rm_url']['@attributes']['href'])			&& is_string($paper['rm_url']['@attributes']['href'])			)?$paper['rm_url']['@attributes']['href']:'';
				$researcher['ResearcherPaper'][$key]['nrid']								= ( isset($paper['rm_nrid'])								&& is_string($paper['rm_nrid'])									)?$paper['rm_nrid']:'';
				$researcher['ResearcherPaper'][$key]['jglobalid']							= ( isset($paper['rm_jglobalid'])							&& is_string($paper['rm_jglobalid'])							)?$paper['rm_jglobalid']:'';
			}
		}
		
		// biblio
		if ( isset($detail['biblio']['entry']))
		{
			if ( isset($detail['biblio']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['biblio']['entry'];
				$detail['biblio']['entry'] = array();
				$detail['biblio']['entry'][] = $entry;
			}
			foreach ( $detail['biblio']['entry'] as $key => $biblio )
			{
				$researcher['ResearcherBiblio'][$key]['title']								= ( isset($biblio['title'])							&& is_string($biblio['title'])							)?$biblio['title']:'';
				$researcher['ResearcherBiblio'][$key]['author']								= ( isset($biblio['author']['name'])				&& is_string($biblio['author']['name'])					)?$biblio['author']['name']:'';
				$researcher['ResearcherBiblio'][$key]['link']								= ( isset($biblio['link']['@attributes']['href'])	&& is_string($biblio['link']['@attributes']['href'])	)?$biblio['link']['@attributes']['href']:'';
				$researcher['ResearcherBiblio'][$key]['summary']							= ( isset($biblio['summary'])						&& is_string($biblio['summary'])						)?$biblio['summary']:'';
				$researcher['ResearcherBiblio'][$key]['publisher']							= ( isset($biblio['rm_publisher'])					&& is_string($biblio['rm_publisher'])					)?$biblio['rm_publisher']:'';
				$researcher['ResearcherBiblio'][$key]['publication_date']					= ( isset($biblio['rm_publicationDate'])			&& is_string($biblio['rm_publicationDate'])				)?$biblio['rm_publicationDate']:'';
				$researcher['ResearcherBiblio'][$key]['total_page_number']					= ( isset($biblio['rm_totalPageNumber'])			&& is_string($biblio['rm_totalPageNumber'])				)?$biblio['rm_totalPageNumber']:'';
				$researcher['ResearcherBiblio'][$key]['rep_page_number']					= ( isset($biblio['rm_repPageNumber'])				&& is_string($biblio['rm_repPageNumber'])				)?$biblio['rm_repPageNumber']:'';
				$researcher['ResearcherBiblio'][$key]['amount']								= ( isset($biblio['rm_amount'])						&& is_string($biblio['rm_amount'])						)?$biblio['rm_amount']:'';
				$researcher['ResearcherBiblio'][$key]['isbn']								= ( isset($biblio['rm_isbn'])						&& is_string($biblio['rm_isbn'])						)?$biblio['rm_isbn']:'';
				$researcher['ResearcherBiblio'][$key]['asin']								= ( isset($biblio['rm_asin'])						&& is_string($biblio['rm_asin'])						)?$biblio['rm_asin']:'';
				$researcher['ResearcherBiblio'][$key]['author_type_id']						= ( isset($biblio['rm_authorType']['id'])			&& is_string($biblio['rm_authorType']['id'])			)?$biblio['rm_authorType']['id']:'';
				$researcher['ResearcherBiblio'][$key]['author_type_name']					= ( isset($biblio['rm_authorType']['name'])			&& is_string($biblio['rm_authorType']['name'])			)?$biblio['rm_authorType']['name']:'';
				$researcher['ResearcherBiblio'][$key]['part_area']							= ( isset($biblio['rm_partArea'])					&& is_string($biblio['rm_partArea'])					)?$biblio['rm_partArea']:'';
				$researcher['ResearcherBiblio'][$key]['amazon_url']							= ( isset($biblio['rm_amazonUrl'])					&& is_string($biblio['rm_amazonUrl'])					)?$biblio['rm_amazonUrl']:'';
				$researcher['ResearcherBiblio'][$key]['small_image_url']					= ( isset($biblio['rm_smallImageUrl'])				&& is_string($biblio['rm_smallImageUrl'])				)?$biblio['rm_smallImageUrl']:'';
				$researcher['ResearcherBiblio'][$key]['medium_image_url']					= ( isset($biblio['rm_mediumImageUrl'])				&& is_string($biblio['rm_mediumImageUrl'])				)?$biblio['rm_mediumImageUrl']:'';
				$researcher['ResearcherBiblio'][$key]['large_image_url']					= ( isset($biblio['rm_largeImageUrl'])				&& is_string($biblio['rm_largeImageUrl'])				)?$biblio['rm_largeImageUrl']:'';
				$researcher['ResearcherBiblio'][$key]['language']							= ( isset($biblio['rm_language'])					&& is_string($biblio['rm_language'])					)?$biblio['rm_language']:'';
				$researcher['ResearcherBiblio'][$key]['biblio_type_id']						= ( isset($biblio['rm_biblioType']['id'])			&& is_string($biblio['rm_biblioType']['id'])			)?$biblio['rm_biblioType']['id']:'';
				$researcher['ResearcherBiblio'][$key]['biblio_type_name']					= ( isset($biblio['rm_biblioType']['name'])			&& is_string($biblio['rm_biblioType']['name'])			)?$biblio['rm_biblioType']['name']:'';
			}
		}
		
		// conference
		if ( isset($detail['conference']['entry']))
		{
			if ( isset($detail['conference']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['conference']['entry'];
				$detail['conference']['entry'] = array();
				$detail['conference']['entry'][] = $entry;
			}
			foreach ( $detail['conference']['entry'] as $key => $conference )
			{
				$researcher['ResearcherConference'][$key]['title']							= ( isset($conference['title'])							&& is_string($conference['title'])							)?$conference['title']:'';
				$researcher['ResearcherConference'][$key]['author']							= ( isset($conference['author']['name'])				&& is_string($conference['author']['name'])					)?$conference['author']['name']:'';
				$researcher['ResearcherConference'][$key]['link']							= ( isset($conference['link']['@attributes']['href'])	&& is_string($conference['link']['@attributes']['href'])	)?$conference['link']['@attributes']['href']:'';
				$researcher['ResearcherConference'][$key]['summary']						= ( isset($conference['summary'])						&& is_string($conference['summary'])						)?$conference['summary']:'';
				$researcher['ResearcherConference'][$key]['journal']						= ( isset($conference['rm_journal'])					&& is_string($conference['rm_journal'])						)?$conference['rm_journal']:'';
				$researcher['ResearcherConference'][$key]['publication_date']				= ( isset($conference['rm_publicationDate'])			&& is_string($conference['rm_publicationDate'])				)?$conference['rm_publicationDate']:'';
				$researcher['ResearcherConference'][$key]['invited']						= ( isset($conference['rm_invited'])																				)?1:0;
				$researcher['ResearcherConference'][$key]['language']						= ( isset($conference['rm_language'])					&& is_string($conference['rm_language'])					)?$conference['rm_language']:'';
				$researcher['ResearcherConference'][$key]['conference_class']				= ( isset($conference['rm_conferenceClass'])			&& is_string($conference['rm_conferenceClass'])				)?$conference['rm_conferenceClass']:'';
				$researcher['ResearcherConference'][$key]['conference_type_id']				= ( isset($conference['rm_conferenceType']['id'])		&& is_string($conference['rm_conferenceType']['id'])		)?$conference['rm_conferenceType']['id']:'';
				$researcher['ResearcherConference'][$key]['conference_type_name']			= ( isset($conference['rm_conferenceType']['name'])		&& is_string($conference['rm_conferenceType']['name'])		)?$conference['rm_conferenceType']['name']:'';
				$researcher['ResearcherConference'][$key]['promoter']						= ( isset($conference['rm_promoter'])					&& is_string($conference['rm_promoter'])					)?$conference['rm_promoter']:'';
				$researcher['ResearcherConference'][$key]['venue']							= ( isset($conference['rm_venue'])						&& is_string($conference['rm_venue'])						)?$conference['rm_venue']:'';
			}
		}
		
		// teachingExperience
		if ( isset($detail['teachingExperience']['entry']))
		{
			if ( isset($detail['teachingExperience']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['teachingExperience']['entry'];
				$detail['teachingExperience']['entry'] = array();
				$detail['teachingExperience']['entry'][] = $entry;
			}
			foreach ( $detail['teachingExperience']['entry'] as $key => $teachingExperience )
			{
				$researcher['ResearcherTeachingExperience'][$key]['title']					= ( isset($teachingExperience['title'])							&& is_string($teachingExperience['title'])							)?$teachingExperience['title']:'';
				$researcher['ResearcherTeachingExperience'][$key]['author']					= ( isset($teachingExperience['author']['name'])				&& is_string($teachingExperience['author']['name'])					)?$teachingExperience['author']['name']:'';
				$researcher['ResearcherTeachingExperience'][$key]['link']					= ( isset($teachingExperience['link']['@attributes']['href'])	&& is_string($teachingExperience['link']['@attributes']['href'])	)?$teachingExperience['link']['@attributes']['href']:'';
				$researcher['ResearcherTeachingExperience'][$key]['affiliation']			= ( isset($teachingExperience['rm_affiliation'])				&& is_string($teachingExperience['rm_affiliation'])					)?$teachingExperience['rm_affiliation']:'';
				$researcher['ResearcherTeachingExperience'][$key]['summaryid']				= ( isset($teachingExperience['rm_summary']['rm_summaryid'])	&& is_string($teachingExperience['rm_summary']['rm_summaryid'])		)?$teachingExperience['rm_summary']['rm_summaryid']:'';
				$researcher['ResearcherTeachingExperience'][$key]['count']					= ( isset($teachingExperience['rm_summary']['rm_count'])		&& is_string($teachingExperience['rm_summary']['rm_count'])			)?$teachingExperience['rm_summary']['rm_count']:'';
			}
		}
		
		// academicSociety
		if ( isset($detail['academicSociety']['entry']))
		{
			if ( isset($detail['academicSociety']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['academicSociety']['entry'];
				$detail['academicSociety']['entry'] = array();
				$detail['academicSociety']['entry'][] = $entry;
			}
			foreach ( $detail['academicSociety']['entry'] as $key => $academicSociety )
			{
				$researcher['ResearcherAcademicSociety'][$key]['title']						= ( isset($academicSociety['title'])						&& is_string($academicSociety['title'])							)?$academicSociety['title']:'';
				$researcher['ResearcherAcademicSociety'][$key]['author']					= ( isset($academicSociety['author']['name'])				&& is_string($academicSociety['author']['name'])				)?$academicSociety['author']['name']:'';
				$researcher['ResearcherAcademicSociety'][$key]['link']						= ( isset($academicSociety['link']['@attributes']['href'])	&& is_string($academicSociety['link']['@attributes']['href'])	)?$academicSociety['link']['@attributes']['href']:'';
				$researcher['ResearcherAcademicSociety'][$key]['summaryid']					= ( isset($academicSociety['rm_summary']['rm_summaryid'])	&& is_string($academicSociety['rm_summary']['rm_summaryid'])	)?$academicSociety['rm_summary']['rm_summaryid']:'';
				$researcher['ResearcherAcademicSociety'][$key]['count']						= ( isset($academicSociety['rm_summary']['rm_count'])		&& is_string($academicSociety['rm_summary']['rm_count'])		)?$academicSociety['rm_summary']['rm_count']:'';
			}
		}
		
		// competitiveFund
		if ( isset($detail['competitiveFund']['entry']))
		{
			if ( isset($detail['competitiveFund']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['competitiveFund']['entry'];
				$detail['competitiveFund']['entry'] = array();
				$detail['competitiveFund']['entry'][] = $entry;
			}
			foreach ( $detail['competitiveFund']['entry'] as $key => $competitiveFund )
			{
				$researcher['ResearcherCompetitiveFund'][$key]['title']						= ( isset($competitiveFund['title'])							&& is_string($competitiveFund['title'])							)?$competitiveFund['title']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['author']					= ( isset($competitiveFund['author']['name'])					&& is_string($competitiveFund['author']['name'])				)?$competitiveFund['author']['name']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['link']						= ( isset($competitiveFund['link']['@attributes']['href'])		&& is_string($competitiveFund['link']['@attributes']['href'])	)?$competitiveFund['link']['@attributes']['href']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['summary']					= ( isset($competitiveFund['summary'])							&& is_string($competitiveFund['summary'])						)?$competitiveFund['summary']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['provider']					= ( isset($competitiveFund['rm_provider'])						&& is_string($competitiveFund['rm_provider'])					)?$competitiveFund['rm_provider']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['system']					= ( isset($competitiveFund['rm_system'])						&& is_string($competitiveFund['rm_system'])						)?$competitiveFund['rm_system']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['from_date']					= ( isset($competitiveFund['rm_fromDate'])						&& is_string($competitiveFund['rm_fromDate'])					)?$competitiveFund['rm_fromDate']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['to_date']					= ( isset($competitiveFund['rm_toDate'])						&& is_string($competitiveFund['rm_toDate'])						)?$competitiveFund['rm_toDate']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['member']					= ( isset($competitiveFund['rm_member'])						&& is_string($competitiveFund['rm_member'])						)?$competitiveFund['rm_member']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['referee_type']				= ( isset($competitiveFund['rm_refereeType'])					&& is_string($competitiveFund['rm_refereeType'])				)?$competitiveFund['rm_refereeType']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['field']						= ( isset($competitiveFund['rm_field'])							&& is_string($competitiveFund['rm_field'])						)?$competitiveFund['rm_field']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['category']					= ( isset($competitiveFund['rm_category'])						&& is_string($competitiveFund['rm_category'])					)?$competitiveFund['rm_category']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['grant_amount_total']		= ( isset($competitiveFund['rm_grantAmount']['rm_total'])		&& is_string($competitiveFund['rm_grantAmount']['rm_total'])	)?$competitiveFund['rm_grantAmount']['rm_total']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['grant_amount_direct']		= ( isset($competitiveFund['rm_grantAmount']['rm_direct'])		&& is_string($competitiveFund['rm_grantAmount']['rm_direct'])	)?$competitiveFund['rm_grantAmount']['rm_direct']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['grant_amount_indirect']		= ( isset($competitiveFund['rm_grantAmount']['rm_indirect'])	&& is_string($competitiveFund['rm_grantAmount']['rm_indirect'])	)?$competitiveFund['rm_grantAmount']['rm_indirect']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['researchid']				= ( isset($competitiveFund['rm_researchid'])					&& is_string($competitiveFund['rm_researchid'])					)?$competitiveFund['rm_researchid']:'';
				$researcher['ResearcherCompetitiveFund'][$key]['institution']				= ( isset($competitiveFund['rm_institution'])					&& is_string($competitiveFund['rm_institution'])				)?$competitiveFund['rm_institution']:'';
			}
		}
		// patent
		if ( isset($detail['patent']['entry']))
		{
			if ( isset($detail['patent']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['patent']['entry'];
				$detail['patent']['entry'] = array();
				$detail['patent']['entry'][] = $entry;
			}
			foreach ( $detail['patent']['entry'] as $key => $patent )
			{
				$researcher['ResearcherPatent'][$key]['title']								= ( isset($patent['title'])									&& is_string($patent['title'])									)?$patent['title']:'';
				$researcher['ResearcherPatent'][$key]['author']								= ( isset($patent['author']['name'])						&& is_string($patent['author']['name'])							)?$patent['author']['name']:'';
				$researcher['ResearcherPatent'][$key]['link']								= ( isset($patent['link']['@attributes']['href'])			&& is_string($patent['link']['@attributes']['href'])			)?$patent['link']['@attributes']['href']:'';
				$researcher['ResearcherPatent'][$key]['summary']							= ( isset($patent['summary'])								&& is_string($patent['summary'])								)?$patent['summary']:'';
				$researcher['ResearcherPatent'][$key]['application_id']						= ( isset($patent['rm_application']['id'])					&& is_string($patent['rm_application']['id'])					)?$patent['rm_application']['id']:'';
				$researcher['ResearcherPatent'][$key]['application_application_date']		= ( isset($patent['rm_application']['rm_applicationDate'])	&& is_string($patent['rm_application']['rm_applicationDate'])	)?$patent['rm_application']['rm_applicationDate']:'';
				$researcher['ResearcherPatent'][$key]['public_id']							= ( isset($patent['rm_public']['id'])						&& is_string($patent['rm_public']['id'])						)?$patent['rm_public']['id']:'';
				$researcher['ResearcherPatent'][$key]['public_public_date']					= ( isset($patent['rm_public']['rm_publicDate'])			&& is_string($patent['rm_public']['rm_publicDate'])				)?$patent['rm_public']['rm_publicDate']:'';
				$researcher['ResearcherPatent'][$key]['translation_id']						= ( isset($patent['rm_translation']['id'])					&& is_string($patent['rm_translation']['id'])					)?$patent['rm_translation']['id']:'';
				$researcher['ResearcherPatent'][$key]['translation_translation_date']		= ( isset($patent['rm_translation']['rm_translationDate'])	&& is_string($patent['rm_translation']['rm_translationDate'])	)?$patent['rm_translation']['rm_translationDate']:'';
				$researcher['ResearcherPatent'][$key]['patent_id']							= ( isset($patent['rm_patent']['id'])						&& is_string($patent['rm_patent']['id'])						)?$patent['rm_patent']['id']:'';
				$researcher['ResearcherPatent'][$key]['patent_patent_date']					= ( isset($patent['rm_patent']['rm_patentDate'])			&& is_string($patent['rm_patent']['rm_patentDate'])				)?$patent['rm_patent']['rm_patentDate']:'';
				$researcher['ResearcherPatent'][$key]['application_person']					= ( isset($patent['rm_applicationPerson'])					&& is_string($patent['rm_applicationPerson'])					)?$patent['rm_applicationPerson']:'';
				$researcher['ResearcherPatent'][$key]['jglobalid']							= ( isset($patent['rm_jglobalid'])							&& is_string($patent['rm_jglobalid'])							)?$patent['rm_jglobalid']:'';
			}
		}
		
		// socialContribution
		if ( isset($detail['socialContribution']['entry']))
		{
			if ( isset($detail['socialContribution']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['socialContribution']['entry'];
				$detail['socialContribution']['entry'] = array();
				$detail['socialContribution']['entry'][] = $entry;
			}
			foreach ( $detail['socialContribution']['entry'] as $key => $socialContribution )
			{
				$researcher['ResearcherSocialContribution'][$key]['title']					= ( isset($socialContribution['title'])							&& is_string($socialContribution['title'])							)?$socialContribution['title']:'';
				$researcher['ResearcherSocialContribution'][$key]['author']					= ( isset($socialContribution['author']['name'])				&& is_string($socialContribution['author']['name'])					)?$socialContribution['author']['name']:'';
				$researcher['ResearcherSocialContribution'][$key]['link']					= ( isset($socialContribution['link']['@attributes']['href'])	&& is_string($socialContribution['link']['@attributes']['href'])	)?$socialContribution['link']['@attributes']['href']:'';
				$researcher['ResearcherSocialContribution'][$key]['summary']				= ( isset($socialContribution['summary'])						&& is_string($socialContribution['summary'])						)?$socialContribution['summary']:'';
				$researcher['ResearcherSocialContribution'][$key]['role_id']				= ( isset($socialContribution['rm_role']['id'])					&& is_string($socialContribution['rm_role']['id'])					)?$socialContribution['rm_role']['id']:'';
				$researcher['ResearcherSocialContribution'][$key]['role_name']				= ( isset($socialContribution['rm_role']['name'])				&& is_string($socialContribution['rm_role']['name'])				)?$socialContribution['rm_role']['name']:'';
				$researcher['ResearcherSocialContribution'][$key]['promoter']				= ( isset($socialContribution['rm_promoter'])					&& is_string($socialContribution['rm_promoter'])					)?$socialContribution['rm_promoter']:'';
				$researcher['ResearcherSocialContribution'][$key]['event']					= ( isset($socialContribution['rm_event'])						&& is_string($socialContribution['rm_event'])						)?$socialContribution['rm_event']:'';
				$researcher['ResearcherSocialContribution'][$key]['from_date']				= ( isset($socialContribution['rm_fromDate'])					&& is_string($socialContribution['rm_fromDate'])					)?$socialContribution['rm_fromDate']:'';
				$researcher['ResearcherSocialContribution'][$key]['to_date']				= ( isset($socialContribution['rm_toDate'])						&& is_string($socialContribution['rm_toDate'])						)?$socialContribution['rm_toDate']:'';
				$researcher['ResearcherSocialContribution'][$key]['location']				= ( isset($socialContribution['rm_location'])					&& is_string($socialContribution['rm_location'])					)?$socialContribution['rm_location']:'';
				$researcher['ResearcherSocialContribution'][$key]['event_type_id']			= ( isset($socialContribution['rm_eventType']['id'])			&& is_string($socialContribution['rm_eventType']['id'])				)?$socialContribution['rm_eventType']['id']:'';
				$researcher['ResearcherSocialContribution'][$key]['event_type_name']		= ( isset($socialContribution['rm_eventType']['name'])			&& is_string($socialContribution['rm_eventType']['name'])			)?$socialContribution['rm_eventType']['name']:'';
				$researcher['ResearcherSocialContribution'][$key]['target_id']				= ( isset($socialContribution['rm_target']['id'])				&& is_string($socialContribution['rm_target']['id'])				)?$socialContribution['rm_target']['id']:'';
				$researcher['ResearcherSocialContribution'][$key]['target_name']			= ( isset($socialContribution['rm_target']['name'])				&& is_string($socialContribution['rm_target']['name'])				)?$socialContribution['rm_target']['name']:'';
			}
		}
		
		// other
		if ( isset($detail['other']['entry']))
		{
			if ( isset($detail['other']['entry']['@attributes']['rm_type']) )
			{
				$entry = $detail['other']['entry'];
				$detail['other']['entry'] = array();
				$detail['other']['entry'][] = $entry;
			}
			foreach ( $detail['other']['entry'] as $key => $other )
			{
				$researcher['ResearcherOther'][$key]['title']				= ( isset($other['title'])							&& is_string($other['title'])							)?$other['title']:'';
				$researcher['ResearcherOther'][$key]['author']				= ( isset($other['author']['name'])					&& is_string($other['author']['name'])					)?$other['author']['name']:'';
				$researcher['ResearcherOther'][$key]['link']				= ( isset($other['link']['@attributes']['href'])	&& is_string($other['link']['@attributes']['href'])		)?$other['link']['@attributes']['href']:'';
				$researcher['ResearcherOther'][$key]['summary']				= ( isset($other['summary'])						&& is_string($other['summary'])							)?$other['summary']:'';
				$researcher['ResearcherOther'][$key]['publication_date']	= ( isset($other['rm_publicationDate'])				&& is_string($other['rm_publicationDate'])				)?$other['rm_publicationDate']:'';
			}
		}
		
		
		$ret = '';
		$rollback = false;
		
		$this->Researcher->begin();
		
		$researcher['Researcher']['last_rm_date'] = date('Y-m-d H:i:s');
		$save['Researcher'] = $researcher['Researcher'];
		
		if ( $this->Researcher->save($save) )
		{
			
		}
		else
		{
			$rollback = true;
		}
		
		if ( !$rollback )
		{
			// 一旦データ削除
			$this->ResearcherCareer->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherPrize->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherConference->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherBiblio->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherResearchKeyword->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherResearchArea->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherAcademicSociety->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherTeachingExperience->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherPaper->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherCompetitiveFund->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherOther->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherPatent->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherAcademicBackground->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherCommitteeCareer->deleteAll(array('researcher_id' => $researcher_id));
			$this->ResearcherSocialContribution->deleteAll(array('researcher_id' => $researcher_id));
		}
		
		
		// ResearcherResearchKeyword	研究キーワード
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherResearchKeyword']) )
			{
				foreach ( $researcher['ResearcherResearchKeyword'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherResearchKeyword->create();
					if ( !$this->ResearcherResearchKeyword->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherResearchArea	研究分野
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherResearchArea']) )
			{
				foreach ( $researcher['ResearcherResearchArea'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherResearchArea->create();
					if ( !$this->ResearcherResearchArea->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherCareer	経歴
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherCareer']) )
			{
				foreach ( $researcher['ResearcherCareer'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherCareer->create();
					if ( !$this->ResearcherCareer->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherAcademicBackground	学歴
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherAcademicBackground']) )
			{
				foreach ( $researcher['ResearcherAcademicBackground'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherAcademicBackground->create();
					if ( !$this->ResearcherAcademicBackground->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherCommitteeCareer	委員歴
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherCommitteeCareer']) )
			{
				foreach ( $researcher['ResearcherCommitteeCareer'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherCommitteeCareer->create();
					if ( !$this->ResearcherCommitteeCareer->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherPrize	受賞
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherPrize']) )
			{
				foreach ( $researcher['ResearcherPrize'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherPrize->create();
					if ( !$this->ResearcherPrize->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherPaper		論文
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherPaper']) )
			{
				foreach ( $researcher['ResearcherPaper'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherPaper->create();
					if ( !$this->ResearcherPaper->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherBiblio	書籍等出版物
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherBiblio']) )
			{
				foreach ( $researcher['ResearcherBiblio'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherBiblio->create();
					if ( !$this->ResearcherBiblio->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherConference	講演・口頭発表等
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherConference']) )
			{
				foreach ( $researcher['ResearcherConference'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherConference->create();
					if ( !$this->ResearcherConference->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherTeachingExperience			担当経験のある科目
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherTeachingExperience']) )
			{
				foreach ( $researcher['ResearcherTeachingExperience'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherTeachingExperience->create();
					if ( !$this->ResearcherTeachingExperience->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherAcademicSociety	所属学協会
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherAcademicSociety']) )
			{
				foreach ( $researcher['ResearcherAcademicSociety'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherAcademicSociety->create();
					if ( !$this->ResearcherAcademicSociety->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherCompetitiveFund	競争的資金等の研究課題
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherCompetitiveFund']) )
			{
				foreach ( $researcher['ResearcherCompetitiveFund'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherCompetitiveFund->create();
					if ( !$this->ResearcherCompetitiveFund->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherPatent	特許
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherPatent']) )
			{
				foreach ( $researcher['ResearcherPatent'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherPatent->create();
					if ( !$this->ResearcherPatent->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherSocialContribution	社会貢献活動
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherSocialContribution']) )
			{
				foreach ( $researcher['ResearcherSocialContribution'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherSocialContribution->create();
					if ( !$this->ResearcherSocialContribution->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		// ResearcherOther	その他
		if ( !$rollback )
		{
			if ( !empty($researcher['ResearcherOther']) )
			{
				foreach ( $researcher['ResearcherOther'] as $data )
				{
					$data['researcher_id'] = $researcher_id;
					$this->ResearcherOther->create();
					if ( !$this->ResearcherOther->save($data) )
					{
						$rollback = true;
						break;
					}
				}
			}
		}
		
		if ( !$rollback )
		{
			$ret['message'] = '研究者データベースに登録しました。';
			$this->Researcher->commit();
		}
		else
		{
			$ret['message'] = 'データの保存に失敗しました。';
			$this->Researcher->rollback();
		}
		
		echo json_encode($ret);
		die();
	}


	// detailLinksから取れるURLより、researchmapにリクエストを投げてデータ取得
	private function get_rm_detail( $url = '', $type )
	{
		if ( empty($url) )
		{
			return false;
		}
		
		$target = $url . '&appid=' . $this->appid;
		$HttpSocket = new HttpSocket();
		$results = $HttpSocket->get(
			$target,
			array()
		);
		
		$xml = json_decode($this->xml_to_json($results), true);
		
		// Basic以外は複数ページ存在する可能性がある
		if ( $type != 'basic' )
		{
			if ( $xml['opensearch_itemsPerPage'] > 0 )
			{
				$p = $xml['opensearch_totalResults'] / $xml['opensearch_itemsPerPage'];
				$p = ceil($p);
			}
			else
			{
				$p = 1;
			}
			
			// 1ページ以上
			if ($p > 1)
			{
				// 2ページ目移行を取得
				$start = 1;
				for ( $i = 2; $i <= $p; $i++ )
				{
					$start = ( $i - 1 ) * $xml['opensearch_itemsPerPage'] + 1;
					
					$target = $url . '&start=' . $start . '&appid=' . $this->appid;
					$HttpSocket = new HttpSocket();
					$results = $HttpSocket->get(
						$target,
						array()
					);
					$xml2 = json_decode($this->xml_to_json($results), true);
					
					if ( isset($xml2['entry']['@attributes']['rm_type']) )
					{
						// 結果が１件の場合添字がない
						$xml['entry'][] = $xml2['entry'];
					}
					else
					{
						// 結果が複数ある場合
						$xml['entry'] = array_merge($xml['entry'], $xml2['entry']);
					}
				}
			}
		}
		
		
		return $xml;
	}

	//**********************************
	// XML ⇒ JSONに変換する関数
	//**********************************
	public function xml_to_json($xml)
	{
		// コロンをアンダーバーに（名前空間対策）
		$xml = preg_replace("/<([^>]+?):([^>]+?)>/", "<$1_$2>", $xml);
		// プロトコルのは元に戻す
		$xml = preg_replace("/_\/\//", "://", $xml);
		// XML文字列をオブジェクトに変換（CDATAも対象とする）
		$objXml = simplexml_load_string($xml, NULL, LIBXML_NOCDATA);
		// 属性を展開する
		$this->xml_expand_attributes($objXml);
		// JSON形式の文字列に変換
		//$json = json_encode($objXml, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		$json = json_encode($objXml);
		// "\/" ⇒ "/" に置換
		return preg_replace('/\\\\\//', '/', $json);
	}

	//**********************************
	// XMLタグの属性を展開する関数
	//**********************************
	public function xml_expand_attributes($node)
	{
		if($node->count() > 0) {
			foreach($node->children() as $child)
			{
				foreach($child->attributes() as $key => $val) {
					$node->addChild($child->getName()."@".$key, htmlentities($val, ENT_NOQUOTES));
				}
				$this->xml_expand_attributes($child); // 再帰呼出
			}
		}
	}

	/**********************************************************
	 * 関数
	 */	
	private function _get_researcher_detail_type ( $type )
	{
		switch ( $type )
		{
			case 'researchKeyword':
				$ret = 1;
				break;
			case 'researchArea':
				$ret = 2;
				break;
			case 'career':
				$ret = 3;
				break;
			case 'academicBackground':
				$ret = 4;
				break;
			case 'committeeCareer':
				$ret = 5;
				break;
			case 'prize':
				$ret = 6;
				break;
			case 'paper':
				$ret = 7;
				break;
			case 'biblio':
				$ret = 8;
				break;
			case 'conference':
				$ret = 9;
				break;
			case 'teachingExperience':
				$ret = 10;
				break;
			case 'academicSociety':
				$ret = 11;
				break;
			case 'competitiveFund':
				$ret = 12;
				break;
			case 'patent':
				$ret = 13;
				break;
			case 'socialContribution':
				$ret = 14;
				break;
			case 'other':
				$ret = 15;
				break;
			default:
				$ret = 0;
				break;
		}
		return $ret;
	}

	// 1項目でも入力されているか調べる
	private function _is_input( $datas = array() )
	{
		$ret = false;
		foreach ( $datas as $data  )
		{
			if ( !empty($data) )
			{
				$ret = true;
				break;
			}
		}
		return $ret;
	}
}
