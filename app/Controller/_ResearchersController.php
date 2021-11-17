<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
class ResearchersController extends AppController {

	public $uses = array(
		'Researcher', 'ResearcherDetail', 
		'ResearcherCareer',
		'ResearcherPrize',
		'ResearcherConference',
		'ResearcherBiblio',
		'ResearcherResearchKeyword',
		'ResearcherResearchArea',
		'ResearcherAcademicSociety',
		'ResearcherTeachingExperience',
		'ResearcherPaper',
		'ResearcherMisc',
		'ResearcherWork',
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
		$conditions = array();
		$this->paginate = array(
			'contain' => array(
				'ResearcherDetail'
			),
			'conditions' => $conditions,
			'order' => 'Researcher.id DESC',
			'limit' => 20
		);
		
		$researchers = $this->paginate();
		$this->set('researchers', $researchers);
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
		
		$this->appid = Configure::read('App.appid');
		
		$HttpSocket = new HttpSocket();
		$results = $HttpSocket->get(
			'http://api.researchmap.jp/opensearch/search',
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
		
		$researcher['ResearcherDetail']['researchKeyword']		= array();	// 研究キーワード
		$researcher['ResearcherDetail']['researchArea']			= array();	// 研究分野
		$researcher['ResearcherDetail']['career']				= array();	// 経歴
		$researcher['ResearcherDetail']['academicBackground']	= array();	// 学歴
		$researcher['ResearcherDetail']['committeeCareer']		= array();	// 委員歴
		$researcher['ResearcherDetail']['prize']				= array();	// 受賞
		$researcher['ResearcherDetail']['paper']				= array();	// 論文
		$researcher['ResearcherDetail']['biblio']				= array();	// 書籍等出版物
		$researcher['ResearcherDetail']['conference']			= array();	// 講演・口頭発表等
		$researcher['ResearcherDetail']['teachingExperience']	= array();	// 担当経験のある科目
		$researcher['ResearcherDetail']['academicSociety']		= array();	// 所属学協会
		$researcher['ResearcherDetail']['competitiveFund']		= array();	// 競争的資金等の研究課題
		$researcher['ResearcherDetail']['patent']				= array();	// 特許
		$researcher['ResearcherDetail']['socialContribution']	= array();	// 社会貢献活動
		$researcher['ResearcherDetail']['other']				= array();	// その他
		
		
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
				$researcher['ResearcherDetail']['researchKeyword'][0]['title']							= ( isset($detail['researchKeyword']['entry']['title']) )?$detail['researchKeyword']['entry']['title']:'';
			}
			else
			{
				foreach ( $detail['researchKeyword']['entry'] as $key => $keyword )
				{
					$researcher['ResearcherDetail']['researchKeyword'][$key]['title']					= ( isset($keyword['title']) )?$keyword['title']:'';
				}
			}
		}
		
		// researchArea
		if ( isset($detail['researchArea']['entry']))
		{
			if ( isset($detail['researchArea']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['researchArea'][0]['field_id']							= ( isset($detail['researchArea']['entry']['rm_field']['id']) )?$detail['researchArea']['entry']['rm_field']['id']:'';
				$researcher['ResearcherDetail']['researchArea'][0]['field_name']						= ( isset($detail['researchArea']['entry']['rm_field']['name']) )?$detail['researchArea']['entry']['rm_field']['name']:'';
				$researcher['ResearcherDetail']['researchArea'][0]['subject_id']						= ( isset($detail['researchArea']['entry']['rm_subject']['id']) )?$detail['researchArea']['entry']['rm_subject']['id']:'';
				$researcher['ResearcherDetail']['researchArea'][0]['subject_name']						= ( isset($detail['researchArea']['entry']['rm_subject']['name']) )?$detail['researchArea']['entry']['rm_subject']['name']:'';
			}
			else
			{
				foreach ( $detail['researchArea']['entry'] as $key => $area )
				{
					$researcher['ResearcherDetail']['researchArea'][$key]['field_id']					= ( isset($area['rm_field']['id']) )?$area['rm_field']['id']:'';
					$researcher['ResearcherDetail']['researchArea'][$key]['field_name']					= ( isset($area['rm_field']['name']) )?$area['rm_field']['name']:'';
					$researcher['ResearcherDetail']['researchArea'][$key]['subject_id']					= ( isset($area['rm_subject']['id']) )?$area['rm_subject']['id']:'';
					$researcher['ResearcherDetail']['researchArea'][$key]['subject_name']				= ( isset($area['rm_subject']['name']) )?$area['rm_subject']['name']:'';
				}
			}
		}
		
		// career
		if ( isset($detail['career']['entry']))
		{
			if ( isset($detail['career']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['career'][0]['title']									= ( isset($detail['career']['entry']['title']) )?$detail['career']['entry']['title']:'';
				$researcher['ResearcherDetail']['career'][0]['fromDate']								= ( isset($detail['career']['entry']['rm_fromDate']) )?$detail['career']['entry']['rm_fromDate']:'';
				$researcher['ResearcherDetail']['career'][0]['toDate']									= ( isset($detail['career']['entry']['rm_toDate']) )?$detail['career']['entry']['rm_toDate']:'';
				$researcher['ResearcherDetail']['career'][0]['affiliation']								= ( isset($detail['career']['entry']['rm_affiliation']) )?$detail['career']['entry']['rm_affiliation']:'';
				$researcher['ResearcherDetail']['career'][0]['section']									= ( isset($detail['career']['entry']['rm_section']) )?$detail['career']['entry']['rm_section']:'';
				$researcher['ResearcherDetail']['career'][0]['job']										= ( isset($detail['career']['entry']['rm_job']) )?$detail['career']['entry']['rm_job']:'';
			}
			else
			{
				foreach ( $detail['career']['entry'] as $key => $career )
				{
					$researcher['ResearcherDetail']['career'][$key]['title']							= ( isset($career['title']) )?$career['title']:'';
					$researcher['ResearcherDetail']['career'][$key]['fromDate']							= ( isset($career['rm_fromDate']) )?$career['rm_fromDate']:'';
					$researcher['ResearcherDetail']['career'][$key]['toDate']							= ( isset($career['rm_toDate']) )?$career['rm_toDate']:'';
					$researcher['ResearcherDetail']['career'][$key]['affiliation']						= ( isset($career['rm_affiliation']) )?$career['rm_affiliation']:'';
					$researcher['ResearcherDetail']['career'][$key]['section']							= ( isset($career['rm_section']) )?$career['rm_section']:'';
					$researcher['ResearcherDetail']['career'][$key]['job']								= ( isset($career['rm_job']) )?$career['rm_job']:'';
				}
			}
		}
		
		// academicBackground
		if ( isset($detail['academicBackground']['entry']))
		{
			if ( isset($detail['academicBackground']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['academicBackground'][0]['title']						= ( isset($detail['academicBackground']['entry']['title']) )?$detail['academicBackground']['entry']['title']:'';
				$researcher['ResearcherDetail']['academicBackground'][0]['departmentName']				= ( isset($detail['academicBackground']['entry']['rm_departmentName']) )?$detail['academicBackground']['entry']['rm_departmentName']:'';
				$researcher['ResearcherDetail']['academicBackground'][0]['subjectName']					= ( isset($detail['academicBackground']['entry']['rm_subjectName']) )?$detail['academicBackground']['entry']['rm_subjectName']:'';
				$researcher['ResearcherDetail']['academicBackground'][0]['country']						= ( isset($detail['academicBackground']['entry']['rm_country']) )?$detail['academicBackground']['entry']['rm_country']:'';
				$researcher['ResearcherDetail']['academicBackground'][0]['fromDate']					= ( isset($detail['academicBackground']['entry']['rm_fromDate']) )?$detail['academicBackground']['entry']['rm_fromDate']:'';
				$researcher['ResearcherDetail']['academicBackground'][0]['toDate']						= ( isset($detail['academicBackground']['entry']['rm_toDate']) )?$detail['academicBackground']['entry']['rm_toDate']:'';
			}
			else
			{
				foreach ( $detail['academicBackground']['entry'] as $key => $academicBackground )
				{
					$researcher['ResearcherDetail']['academicBackground'][$key]['title']				= ( isset($academicBackground['title']) )?$academicBackground['title']:'';
					$researcher['ResearcherDetail']['academicBackground'][$key]['departmentName']		= ( isset($academicBackground['rm_departmentName']) )?$academicBackground['rm_departmentName']:'';
					$researcher['ResearcherDetail']['academicBackground'][$key]['subjectName']			= ( isset($academicBackground['rm_subjectName']) )?$academicBackground['rm_subjectName']:'';
					$researcher['ResearcherDetail']['academicBackground'][$key]['country']				= ( isset($academicBackground['rm_country']) )?$academicBackground['rm_country']:'';
					$researcher['ResearcherDetail']['academicBackground'][$key]['fromDate']				= ( isset($academicBackground['rm_fromDate']) )?$academicBackground['rm_fromDate']:'';
					$researcher['ResearcherDetail']['academicBackground'][$key]['toDate']				= ( isset($academicBackground['rm_toDate']) )?$academicBackground['rm_toDate']:'';
				}
			}
		}
		
		// committeeCareer
		if ( isset($detail['committeeCareer']['entry']))
		{
			if ( isset($detail['committeeCareer']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['committeeCareer'][0]['title']							= ( isset($detail['committeeCareer']['entry']['title']) )?$detail['committeeCareer']['entry']['title']:'';
				$researcher['ResearcherDetail']['committeeCareer'][0]['fromDate']						= ( isset($detail['committeeCareer']['entry']['fromDate']) )?$detail['committeeCareer']['entry']['fromDate']:'';
				$researcher['ResearcherDetail']['committeeCareer'][0]['toDate']							= ( isset($detail['committeeCareer']['entry']['toDate']) )?$detail['committeeCareer']['entry']['toDate']:'';
				$researcher['ResearcherDetail']['committeeCareer'][0]['association']					= ( isset($detail['committeeCareer']['entry']['association']) )?$detail['committeeCareer']['entry']['association']:'';
				$researcher['ResearcherDetail']['committeeCareer'][0]['committeeType_id']				= ( isset($detail['committeeCareer']['entry']['committeeType']['id']) )?$detail['committeeCareer']['entry']['committeeType']['id']:'';
				$researcher['ResearcherDetail']['committeeCareer'][0]['committeeType_name']				= ( isset($detail['committeeCareer']['entry']['committeeType']['name']) )?$detail['committeeCareer']['entry']['committeeType']['name']:'';
				$researcher['ResearcherDetail']['committeeCareer'][0]['summary']						= ( isset($detail['committeeCareer']['entry']['summary']) )?$detail['committeeCareer']['entry']['summary']:'';
			}
			else
			{
				foreach ( $detail['committeeCareer']['entry'] as $key => $committeeCareer )
				{
					$researcher['ResearcherDetail']['committeeCareer'][$key]['title']					= ( isset($committeeCareer['title']) )?$committeeCareer['title']:'';
					$researcher['ResearcherDetail']['committeeCareer'][$key]['fromDate']				= ( isset($committeeCareer['fromDate']) )?$committeeCareer['fromDate']:'';
					$researcher['ResearcherDetail']['committeeCareer'][$key]['toDate']					= ( isset($committeeCareer['toDate']) )?$committeeCareer['toDate']:'';
					$researcher['ResearcherDetail']['committeeCareer'][$key]['association']				= ( isset($committeeCareer['association']) )?$committeeCareer['association']:'';
					$researcher['ResearcherDetail']['committeeCareer'][$key]['committeeType_id']		= ( isset($committeeCareer['committeeType']['id']) )?$committeeCareer['committeeType']['id']:'';
					$researcher['ResearcherDetail']['committeeCareer'][$key]['committeeType_name']		= ( isset($committeeCareer['committeeType']['name']) )?$committeeCareer['committeeType']['name']:'';
					$researcher['ResearcherDetail']['committeeCareer'][$key]['summary']					= ( isset($committeeCareer['summary']) )?$committeeCareer['summary']:'';
				}
			}
		}
		
		// prize
		if ( isset($detail['prize']['entry']))
		{
			if ( isset($detail['prize']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['prize'][0]['title']									= ( isset($detail['prize']['entry']['title']) )?$detail['prize']['entry']['title']:'';
				$researcher['ResearcherDetail']['prize'][0]['summary']									= ( isset($detail['prize']['entry']['summary']) )?$detail['prize']['entry']['summary']:'';
				$researcher['ResearcherDetail']['prize'][0]['publicationDate']							= ( isset($detail['prize']['entry']['rm_publicationDate']) )?$detail['prize']['entry']['rm_publicationDate']:'';
				$researcher['ResearcherDetail']['prize'][0]['association']								= ( isset($detail['prize']['entry']['rm_association']) )?$detail['prize']['entry']['rm_association']:'';
				$researcher['ResearcherDetail']['prize'][0]['subtitle']									= ( isset($detail['prize']['entry']['rm_subtitle']) )?$detail['prize']['entry']['rm_subtitle']:'';
				$researcher['ResearcherDetail']['prize'][0]['partner']									= ( isset($detail['prize']['entry']['rm_partner']) )?$detail['prize']['entry']['rm_partner']:'';
				$researcher['ResearcherDetail']['prize'][0]['prizeType_id']								= ( isset($detail['prize']['entry']['rm_prizeType']['id']) )?$detail['prize']['entry']['rm_prizeType']['id']:'';
				$researcher['ResearcherDetail']['prize'][0]['prizeType_name']							= ( isset($detail['prize']['entry']['rm_prizeType']['name']) )?$detail['prize']['entry']['rm_prizeType']['name']:'';
				$researcher['ResearcherDetail']['prize'][0]['country']									= ( isset($detail['prize']['entry']['rm_country']) )?$detail['prize']['entry']['rm_country']:'';
			}
			else
			{
				foreach ( $detail['prize']['entry'] as $key => $prize )
				{
					$researcher['ResearcherDetail']['prize'][$key]['title']								= ( isset($prize['title']) )?$prize['title']:'';
					$researcher['ResearcherDetail']['prize'][$key]['summary']							= ( isset($prize['summary']) )?$prize['summary']:'';
					$researcher['ResearcherDetail']['prize'][$key]['publicationDate']					= ( isset($prize['rm_publicationDate']) )?$prize['rm_publicationDate']:'';
					$researcher['ResearcherDetail']['prize'][$key]['association']						= ( isset($prize['rm_association']) )?$prize['rm_association']:'';
					$researcher['ResearcherDetail']['prize'][$key]['subtitle']							= ( isset($prize['rm_subtitle']) )?$prize['rm_subtitle']:'';
					$researcher['ResearcherDetail']['prize'][$key]['partner']							= ( isset($prize['rm_partner']) )?$prize['rm_partner']:'';
					$researcher['ResearcherDetail']['prize'][$key]['prizeType_id']						= ( isset($prize['rm_prizeType']['id']) )?$prize['rm_prizeType']['id']:'';
					$researcher['ResearcherDetail']['prize'][$key]['prizeType_name']					= ( isset($prize['rm_prizeType']['name']) )?$prize['rm_prizeType']['name']:'';
					$researcher['ResearcherDetail']['prize'][$key]['country']							= ( isset($prize['rm_country']) )?$prize['rm_country']:'';
				}
			}
		}
		
		// paper
		if ( isset($detail['paper']['entry']))
		{
			if ( isset($detail['paper']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['paper'][0]['title']									= ( isset($detail['paper']['entry']['title']) )?$detail['paper']['entry']['title']:'';
				$researcher['ResearcherDetail']['paper'][0]['summary']									= ( isset($detail['paper']['entry']['summary']) )?$detail['paper']['entry']['summary']:'';
				$researcher['ResearcherDetail']['paper'][0]['journal']									= ( isset($detail['paper']['entry']['rm_journal']) )?$detail['paper']['entry']['rm_journal']:'';
				$researcher['ResearcherDetail']['paper'][0]['publisher']								= ( isset($detail['paper']['entry']['rm_publisher']) )?$detail['paper']['entry']['rm_publisher']:'';
				$researcher['ResearcherDetail']['paper'][0]['publicationName']							= ( isset($detail['paper']['entry']['rm_publicationName']) )?$detail['paper']['entry']['rm_publicationName']:'';
				$researcher['ResearcherDetail']['paper'][0]['volume']									= ( isset($detail['paper']['entry']['rm_volume']) )?$detail['paper']['entry']['rm_volume']:'';
				$researcher['ResearcherDetail']['paper'][0]['number']									= ( isset($detail['paper']['entry']['rm_number']) )?$detail['paper']['entry']['rm_number']:'';
				$researcher['ResearcherDetail']['paper'][0]['startingPage']								= ( isset($detail['paper']['entry']['rm_startingPage']) )?$detail['paper']['entry']['rm_startingPage']:'';
				$researcher['ResearcherDetail']['paper'][0]['endingPage']								= ( isset($detail['paper']['entry']['rm_endingPage']) )?$detail['paper']['entry']['rm_endingPage']:'';
				$researcher['ResearcherDetail']['paper'][0]['publicationDate']							= ( isset($detail['paper']['entry']['rm_publicationDate']) )?$detail['paper']['entry']['rm_publicationDate']:'';
				$researcher['ResearcherDetail']['paper'][0]['referee']									= ( isset($detail['paper']['entry']['rm_referee']) )?$detail['paper']['entry']['rm_referee']:'';
				$researcher['ResearcherDetail']['paper'][0]['invited']									= ( isset($detail['paper']['entry']['rm_invited']) )?$detail['paper']['entry']['rm_invited']:'';
				$researcher['ResearcherDetail']['paper'][0]['language']									= ( isset($detail['paper']['entry']['rm_language']) )?$detail['paper']['entry']['rm_language']:'';
				$researcher['ResearcherDetail']['paper'][0]['paperType_id']								= ( isset($detail['paper']['entry']['rm_paperType']['id']) )?$detail['paper']['entry']['rm_paperType']['id']:'';
				$researcher['ResearcherDetail']['paper'][0]['paperType_name']							= ( isset($detail['paper']['entry']['rm_paperType']['name']) )?$detail['paper']['entry']['rm_paperType']['name']:'';
				$researcher['ResearcherDetail']['paper'][0]['issn']										= ( isset($detail['paper']['entry']['rm_issn']) )?$detail['paper']['entry']['rm_issn']:'';
				$researcher['ResearcherDetail']['paper'][0]['doi']										= ( isset($detail['paper']['entry']['rm_doi']) )?$detail['paper']['entry']['rm_doi']:'';
				$researcher['ResearcherDetail']['paper'][0]['naid']										= ( isset($detail['paper']['entry']['rm_naid']) )?$detail['paper']['entry']['rm_naid']:'';
				$researcher['ResearcherDetail']['paper'][0]['pmid']										= ( isset($detail['paper']['entry']['rm_pmid']) )?$detail['paper']['entry']['rm_pmid']:'';
				$researcher['ResearcherDetail']['paper'][0]['permalink']								= ( isset($detail['paper']['entry']['rm_permalink']) )?$detail['paper']['entry']['rm_permalink']:'';
				$researcher['ResearcherDetail']['paper'][0]['url']										= ( isset($detail['paper']['entry']['rm_url']) )?$detail['paper']['entry']['rm_url']:'';
				$researcher['ResearcherDetail']['paper'][0]['nrid']										= ( isset($detail['paper']['entry']['rm_nrid']) )?$detail['paper']['entry']['rm_nrid']:'';
				$researcher['ResearcherDetail']['paper'][0]['jglobalid']								= ( isset($detail['paper']['entry']['rm_jglobalid']) )?$detail['paper']['entry']['rm_jglobalid']:'';
			}
			else
			{
				foreach ( $detail['paper']['entry'] as $key => $paper )
				{
					$researcher['ResearcherDetail']['paper'][$key]['title']								= ( isset($paper['title']) )?$paper['title']:'';
					$researcher['ResearcherDetail']['paper'][$key]['summary']							= ( isset($paper['summary']) )?$paper['summary']:'';
					$researcher['ResearcherDetail']['paper'][$key]['journal']							= ( isset($paper['rm_journal']) )?$paper['rm_journal']:'';
					$researcher['ResearcherDetail']['paper'][$key]['publisher']							= ( isset($paper['rm_publisher']) )?$paper['rm_publisher']:'';
					$researcher['ResearcherDetail']['paper'][$key]['publicationName']					= ( isset($paper['rm_publicationName']) )?$paper['rm_publicationName']:'';
					$researcher['ResearcherDetail']['paper'][$key]['volume']							= ( isset($paper['rm_volume']) )?$paper['rm_volume']:'';
					$researcher['ResearcherDetail']['paper'][$key]['number']							= ( isset($paper['rm_number']) )?$paper['rm_number']:'';
					$researcher['ResearcherDetail']['paper'][$key]['startingPage']						= ( isset($paper['rm_startingPage']) )?$paper['rm_startingPage']:'';
					$researcher['ResearcherDetail']['paper'][$key]['endingPage']						= ( isset($paper['rm_endingPage']) )?$paper['rm_endingPage']:'';
					$researcher['ResearcherDetail']['paper'][$key]['publicationDate']					= ( isset($paper['rm_publicationDate']) )?$paper['rm_publicationDate']:'';
					$researcher['ResearcherDetail']['paper'][$key]['referee']							= ( isset($paper['rm_referee']) )?$paper['rm_referee']:'';
					$researcher['ResearcherDetail']['paper'][$key]['invited']							= ( isset($paper['rm_invited']) )?$paper['rm_invited']:'';
					$researcher['ResearcherDetail']['paper'][$key]['language']							= ( isset($paper['rm_language']) )?$paper['rm_language']:'';
					$researcher['ResearcherDetail']['paper'][$key]['paperType_id']						= ( isset($paper['rm_paperType']['id']) )?$paper['rm_paperType']['id']:'';
					$researcher['ResearcherDetail']['paper'][$key]['paperType_name']					= ( isset($paper['rm_paperType']['name']) )?$paper['rm_paperType']['name']:'';
					$researcher['ResearcherDetail']['paper'][$key]['issn']								= ( isset($paper['rm_issn']) )?$paper['rm_issn']:'';
					$researcher['ResearcherDetail']['paper'][$key]['doi']								= ( isset($paper['rm_doi']) )?$paper['rm_doi']:'';
					$researcher['ResearcherDetail']['paper'][$key]['naid']								= ( isset($paper['rm_naid']) )?$paper['rm_naid']:'';
					$researcher['ResearcherDetail']['paper'][$key]['pmid']								= ( isset($paper['rm_pmid']) )?$paper['rm_pmid']:'';
					$researcher['ResearcherDetail']['paper'][$key]['permalink']							= ( isset($paper['rm_permalink']) )?$paper['rm_permalink']:'';
					$researcher['ResearcherDetail']['paper'][$key]['url']								= ( isset($paper['rm_url']) )?$paper['rm_url']:'';
					$researcher['ResearcherDetail']['paper'][$key]['nrid']								= ( isset($paper['rm_nrid']) )?$paper['rm_nrid']:'';
					$researcher['ResearcherDetail']['paper'][$key]['jglobalid']							= ( isset($paper['rm_jglobalid']) )?$paper['rm_jglobalid']:'';
				}
			}
		}
		
		// biblio
		if ( isset($detail['biblio']['entry']))
		{
			if ( isset($detail['biblio']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['biblio'][0]['title']									= ( isset($detail['biblio']['entry']['title']) )?$detail['biblio']['entry']['title']:'';
				$researcher['ResearcherDetail']['biblio'][0]['summary']									= ( isset($detail['biblio']['entry']['summary']) )?$detail['biblio']['entry']['summary']:'';
				$researcher['ResearcherDetail']['biblio'][0]['publisher']								= ( isset($detail['biblio']['entry']['rm_publisher']) )?$detail['biblio']['entry']['rm_publisher']:'';
				$researcher['ResearcherDetail']['biblio'][0]['publicationDate']							= ( isset($detail['biblio']['entry']['rm_publicationDate']) )?$detail['biblio']['entry']['rm_publicationDate']:'';
				$researcher['ResearcherDetail']['biblio'][0]['totalPageNumber']							= ( isset($detail['biblio']['entry']['rm_totalPageNumber']) )?$detail['biblio']['entry']['rm_totalPageNumber']:'';
				$researcher['ResearcherDetail']['biblio'][0]['repPageNumber']							= ( isset($detail['biblio']['entry']['rm_repPageNumber']) )?$detail['biblio']['entry']['rm_repPageNumber']:'';
				$researcher['ResearcherDetail']['biblio'][0]['amount']									= ( isset($detail['biblio']['entry']['rm_amount']) )?$detail['biblio']['entry']['rm_amount']:'';
				$researcher['ResearcherDetail']['biblio'][0]['isbn']									= ( isset($detail['biblio']['entry']['rm_isbn']) )?$detail['biblio']['entry']['rm_isbn']:'';
				$researcher['ResearcherDetail']['biblio'][0]['asin']									= ( isset($detail['biblio']['entry']['rm_asin']) )?$detail['biblio']['entry']['rm_asin']:'';
				$researcher['ResearcherDetail']['biblio'][0]['authorType_id']							= ( isset($detail['biblio']['entry']['rm_authorType']['id']) )?$detail['biblio']['entry']['rm_authorType']['id']:'';
				$researcher['ResearcherDetail']['biblio'][0]['authorType_name']							= ( isset($detail['biblio']['entry']['rm_authorType']['name']) )?$detail['biblio']['entry']['rm_authorType']['name']:'';
				$researcher['ResearcherDetail']['biblio'][0]['partArea']								= ( isset($detail['biblio']['entry']['rm_partArea']) )?$detail['biblio']['entry']['rm_partArea']:'';
				$researcher['ResearcherDetail']['biblio'][0]['amazonUrl']								= ( isset($detail['biblio']['entry']['rm_amazonUrl']) )?$detail['biblio']['entry']['rm_amazonUrl']:'';
				$researcher['ResearcherDetail']['biblio'][0]['smallImageUrl']							= ( isset($detail['biblio']['entry']['rm_smallImageUrl']) )?$detail['biblio']['entry']['rm_smallImageUrl']:'';
				$researcher['ResearcherDetail']['biblio'][0]['mediumImageUrl']							= ( isset($detail['biblio']['entry']['rm_mediumImageUrl']) )?$detail['biblio']['entry']['rm_mediumImageUrl']:'';
				$researcher['ResearcherDetail']['biblio'][0]['largeImageUrl']							= ( isset($detail['biblio']['entry']['rm_largeImageUrl']) )?$detail['biblio']['entry']['rm_largeImageUrl']:'';
				$researcher['ResearcherDetail']['biblio'][0]['language']								= ( isset($detail['biblio']['entry']['rm_language']) )?$detail['biblio']['entry']['rm_language']:'';
				$researcher['ResearcherDetail']['biblio'][0]['biblioType_id']							= ( isset($detail['biblio']['entry']['rm_biblioType']['id']) )?$detail['biblio']['entry']['rm_biblioType']['id']:'';
				$researcher['ResearcherDetail']['biblio'][0]['biblioType_name']							= ( isset($detail['biblio']['entry']['rm_biblioType']['name']) )?$detail['biblio']['entry']['rm_biblioType']['name']:'';
			}
			else
			{
				foreach ( $detail['biblio']['entry'] as $key => $biblio )
				{
					$researcher['ResearcherDetail']['biblio'][$key]['title']							= ( isset($biblio['title']) )?$biblio['title']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['summary']							= ( isset($biblio['summary']) )?$biblio['summary']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['publisher']						= ( isset($biblio['rm_publisher']) )?$biblio['rm_publisher']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['publicationDate']					= ( isset($biblio['rm_publicationDate']) )?$biblio['rm_publicationDate']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['totalPageNumber']					= ( isset($biblio['rm_totalPageNumber']) )?$biblio['rm_totalPageNumber']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['repPageNumber']					= ( isset($biblio['rm_repPageNumber']) )?$biblio['rm_repPageNumber']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['amount']							= ( isset($biblio['rm_amount']) )?$biblio['rm_amount']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['isbn']								= ( isset($biblio['rm_isbn']) )?$biblio['rm_isbn']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['asin']								= ( isset($biblio['rm_asin']) )?$biblio['rm_asin']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['authorType_id']					= ( isset($biblio['rm_authorType']['id']) )?$biblio['rm_authorType']['id']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['authorType_name']					= ( isset($biblio['rm_authorType']['name']) )?$biblio['rm_authorType']['name']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['partArea']							= ( isset($biblio['rm_partArea']) )?$biblio['rm_partArea']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['amazonUrl']						= ( isset($biblio['rm_amazonUrl']) )?$biblio['rm_amazonUrl']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['smallImageUrl']					= ( isset($biblio['rm_smallImageUrl']) )?$biblio['rm_smallImageUrl']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['mediumImageUrl']					= ( isset($biblio['rm_mediumImageUrl']) )?$biblio['rm_mediumImageUrl']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['largeImageUrl']					= ( isset($biblio['rm_largeImageUrl']) )?$biblio['rm_largeImageUrl']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['language']							= ( isset($biblio['rm_language']) )?$biblio['rm_language']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['biblioType_id']					= ( isset($biblio['rm_biblioType']['id']) )?$biblio['rm_biblioType']['id']:'';
					$researcher['ResearcherDetail']['biblio'][$key]['biblioType_name']					= ( isset($biblio['rm_biblioType']['name']) )?$biblio['rm_biblioType']['name']:'';
				}
			}
		}
		
		// conference
		if ( isset($detail['conference']['entry']))
		{
			if ( isset($detail['conference']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['conference'][0]['title']								= ( isset($detail['conference']['entry']['title']) )?$detail['conference']['entry']['title']:'';
				$researcher['ResearcherDetail']['conference'][0]['summary']								= ( isset($detail['conference']['entry']['summary']) )?$detail['conference']['entry']['summary']:'';
				$researcher['ResearcherDetail']['conference'][0]['journal']								= ( isset($detail['conference']['entry']['rm_journal']) )?$detail['conference']['entry']['rm_journal']:'';
				$researcher['ResearcherDetail']['conference'][0]['publicationDate']						= ( isset($detail['conference']['entry']['rm_publicationDate']) )?$detail['conference']['entry']['rm_publicationDate']:'';
				$researcher['ResearcherDetail']['conference'][0]['invited']								= ( isset($detail['conference']['entry']['rm_invited']) )?$detail['conference']['entry']['rm_invited']:'';
				$researcher['ResearcherDetail']['conference'][0]['language']							= ( isset($detail['conference']['entry']['rm_language']) )?$detail['conference']['entry']['rm_language']:'';
				$researcher['ResearcherDetail']['conference'][0]['conferenceClass']						= ( isset($detail['conference']['entry']['rm_conferenceClass']) )?$detail['conference']['entry']['rm_conferenceClass']:'';
				$researcher['ResearcherDetail']['conference'][0]['conferenceType_id']					= ( isset($detail['conference']['entry']['rm_conferenceType']['id']) )?$detail['conference']['entry']['rm_conferenceType']['id']:'';
				$researcher['ResearcherDetail']['conference'][0]['conferenceType_name']					= ( isset($detail['conference']['entry']['rm_conferenceType']['name']) )?$detail['conference']['entry']['rm_conferenceType']['name']:'';
				$researcher['ResearcherDetail']['conference'][0]['promoter']							= ( isset($detail['conference']['entry']['rm_promoter']) )?$detail['conference']['entry']['rm_promoter']:'';
				$researcher['ResearcherDetail']['conference'][0]['venue']								= ( isset($detail['conference']['entry']['rm_venue']) )?$detail['conference']['entry']['rm_venue']:'';
			}
			else
			{
				foreach ( $detail['conference']['entry'] as $key => $conference )
				{
					$researcher['ResearcherDetail']['conference'][$key]['title']						= ( isset($conference['title']) )?$conference['title']:'';
					$researcher['ResearcherDetail']['conference'][$key]['summary']						= ( isset($conference['summary']) )?$conference['summary']:'';
					$researcher['ResearcherDetail']['conference'][$key]['journal']						= ( isset($conference['rm_journal']) )?$conference['rm_journal']:'';
					$researcher['ResearcherDetail']['conference'][$key]['publicationDate']				= ( isset($conference['rm_publicationDate']) )?$conference['rm_publicationDate']:'';
					$researcher['ResearcherDetail']['conference'][$key]['invited']						= ( isset($conference['rm_invited']) )?$conference['rm_invited']:'';
					$researcher['ResearcherDetail']['conference'][$key]['language']						= ( isset($conference['rm_language']) )?$conference['rm_language']:'';
					$researcher['ResearcherDetail']['conference'][$key]['conferenceClass']				= ( isset($conference['rm_conferenceClass']) )?$conference['rm_conferenceClass']:'';
					$researcher['ResearcherDetail']['conference'][$key]['conferenceType_id']			= ( isset($conference['rm_conferenceType']['id']) )?$conference['rm_conferenceType']['id']:'';
					$researcher['ResearcherDetail']['conference'][$key]['conferenceType_name']			= ( isset($conference['rm_conferenceType']['name']) )?$conference['rm_conferenceType']['name']:'';
					$researcher['ResearcherDetail']['conference'][$key]['promoter']						= ( isset($conference['rm_promoter']) )?$conference['rm_promoter']:'';
					$researcher['ResearcherDetail']['conference'][$key]['venue']						= ( isset($conference['rm_venue']) )?$conference['rm_venue']:'';
				}
			}
		}
		
		// teachingExperience
		if ( isset($detail['teachingExperience']['entry']))
		{
			if ( isset($detail['teachingExperience']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['teachingExperience'][0]['title']						= ( isset($detail['teachingExperience']['entry']['title']) )?$detail['teachingExperience']['entry']['title']:'';
				$researcher['ResearcherDetail']['teachingExperience'][0]['affiliation']					= ( isset($detail['teachingExperience']['entry']['rm_affiliation']) )?$detail['teachingExperience']['entry']['rm_affiliation']:'';
				$researcher['ResearcherDetail']['teachingExperience'][0]['summaryid']					= ( isset($detail['teachingExperience']['entry']['rm_summary']['rm_summaryid']) )?$detail['teachingExperience']['entry']['rm_summary']['rm_summaryid']:'';
				$researcher['ResearcherDetail']['teachingExperience'][0]['count']						= ( isset($detail['teachingExperience']['entry']['rm_summary']['rm_count']) )?$detail['teachingExperience']['entry']['rm_summary']['rm_count']:'';
			}
			else
			{
				foreach ( $detail['teachingExperience']['entry'] as $key => $teachingExperience )
				{
					$researcher['ResearcherDetail']['teachingExperience'][$key]['title']				= ( isset($teachingExperience['title']) )?$teachingExperience['title']:'';
					$researcher['ResearcherDetail']['teachingExperience'][$key]['affiliation']			= ( isset($teachingExperience['rm_affiliation']) )?$teachingExperience['rm_affiliation']:'';
					$researcher['ResearcherDetail']['teachingExperience'][$key]['summaryid']			= ( isset($teachingExperience['rm_summary']['rm_summaryid']) )?$teachingExperience['rm_summary']['rm_summaryid']:'';
					$researcher['ResearcherDetail']['teachingExperience'][$key]['count']				= ( isset($teachingExperience['rm_summary']['rm_count']) )?$teachingExperience['rm_summary']['rm_count']:'';
				}
			}
		}
		
		// academicSociety
		if ( isset($detail['academicSociety']['entry']))
		{
			if ( isset($detail['academicSociety']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['academicSociety'][0]['title']							= ( isset($detail['academicSociety']['entry']['title']) )?$detail['academicSociety']['entry']['title']:'';
				$researcher['ResearcherDetail']['academicSociety'][0]['summaryid']						= ( isset($detail['academicSociety']['entry']['rm_summary']['rm_summaryid']) )?$detail['academicSociety']['entry']['rm_summary']['rm_summaryid']:'';
				$researcher['ResearcherDetail']['academicSociety'][0]['count']							= ( isset($detail['academicSociety']['entry']['rm_summary']['rm_count']) )?$detail['academicSociety']['entry']['rm_summary']['rm_count']:'';
			}
			else
			{
				foreach ( $detail['academicSociety']['entry'] as $key => $academicSociety )
				{
					$researcher['ResearcherDetail']['academicSociety'][$key]['title']					= ( isset($academicSociety['title']) )?$academicSociety['title']:'';
					$researcher['ResearcherDetail']['academicSociety'][$key]['summaryid']				= ( isset($academicSociety['rm_summary']['rm_summaryid']) )?$academicSociety['rm_summary']['rm_summaryid']:'';
					$researcher['ResearcherDetail']['academicSociety'][$key]['count']					= ( isset($academicSociety['rm_summary']['rm_count']) )?$academicSociety['rm_summary']['rm_count']:'';
				}
			}
		}
		
		// competitiveFund
		if ( isset($detail['competitiveFund']['entry']))
		{
			if ( isset($detail['competitiveFund']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['competitiveFund'][0]['title']							= ( isset($detail['academicSociety']['entry']['title']) )?$detail['academicSociety']['entry']['title']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['summary']						= ( isset($detail['academicSociety']['entry']['summary']) )?$detail['academicSociety']['entry']['summary']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['provider']						= ( isset($detail['academicSociety']['entry']['rm_provider']) )?$detail['academicSociety']['entry']['rm_provider']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['system']							= ( isset($detail['academicSociety']['entry']['rm_system']) )?$detail['academicSociety']['entry']['rm_system']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['fromDate']						= ( isset($detail['academicSociety']['entry']['rm_fromDate']) )?$detail['academicSociety']['entry']['rm_fromDate']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['toDate']							= ( isset($detail['academicSociety']['entry']['rm_toDate']) )?$detail['academicSociety']['entry']['rm_toDate']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['member']							= ( isset($detail['academicSociety']['entry']['rm_member']) )?$detail['academicSociety']['entry']['rm_member']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['refereeType']					= ( isset($detail['academicSociety']['entry']['rm_refereeType']) )?$detail['academicSociety']['entry']['rm_refereeType']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['field']							= ( isset($detail['academicSociety']['entry']['rm_field']) )?$detail['academicSociety']['entry']['rm_field']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['category']						= ( isset($detail['academicSociety']['entry']['rm_category']) )?$detail['academicSociety']['entry']['rm_category']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['grantAmount_total']				= ( isset($detail['academicSociety']['entry']['rm_grantAmount']['rm_total']) )?$detail['academicSociety']['entry']['rm_grantAmount']['rm_total']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['grantAmount_direct']				= ( isset($detail['academicSociety']['entry']['rm_grantAmount']['rm_direct']) )?$detail['academicSociety']['entry']['rm_grantAmount']['rm_direct']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['grantAmount_indirect']			= ( isset($detail['academicSociety']['entry']['rm_grantAmount']['rm_indirect']) )?$detail['academicSociety']['entry']['rm_grantAmount']['rm_indirect']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['researchid']						= ( isset($detail['academicSociety']['entry']['rm_researchid']) )?$detail['academicSociety']['entry']['rm_researchid']:'';
				$researcher['ResearcherDetail']['competitiveFund'][0]['institution']					= ( isset($detail['academicSociety']['entry']['rm_institution']) )?$detail['academicSociety']['entry']['rm_institution']:'';
			}
			else
			{
				foreach ( $detail['competitiveFund']['entry'] as $key => $competitiveFund )
				{
					$researcher['ResearcherDetail']['competitiveFund'][$key]['title']					= ( isset($competitiveFund['title']) )?$competitiveFund['title']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['summary']					= ( isset($competitiveFund['summary']) )?$competitiveFund['summary']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['provider']				= ( isset($competitiveFund['rm_provider']) )?$competitiveFund['rm_provider']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['system']					= ( isset($competitiveFund['rm_system']) )?$competitiveFund['rm_system']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['fromDate']				= ( isset($competitiveFund['rm_fromDate']) )?$competitiveFund['rm_fromDate']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['toDate']					= ( isset($competitiveFund['rm_toDate']) )?$competitiveFund['rm_toDate']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['member']					= ( isset($competitiveFund['rm_member']) )?$competitiveFund['rm_member']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['refereeType']				= ( isset($competitiveFund['rm_refereeType']) )?$competitiveFund['rm_refereeType']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['field']					= ( isset($competitiveFund['rm_field']) )?$competitiveFund['rm_field']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['category']				= ( isset($competitiveFund['rm_category']) )?$competitiveFund['rm_category']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['grantAmount_total']		= ( isset($competitiveFund['rm_grantAmount']['rm_total']) )?$competitiveFund['rm_grantAmount']['rm_total']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['grantAmount_direct']		= ( isset($competitiveFund['rm_grantAmount']['rm_direct']) )?$competitiveFund['rm_grantAmount']['rm_direct']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['grantAmount_indirect']	= ( isset($competitiveFund['rm_grantAmount']['rm_indirect']) )?$competitiveFund['rm_grantAmount']['rm_indirect']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['researchid']				= ( isset($competitiveFund['rm_researchid']) )?$competitiveFund['rm_researchid']:'';
					$researcher['ResearcherDetail']['competitiveFund'][$key]['institution']				= ( isset($competitiveFund['rm_institution']) )?$competitiveFund['rm_institution']:'';
				}
			}
		}
		// patent
		if ( isset($detail['patent']['entry']))
		{
			if ( isset($detail['patent']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['patent'][0]['title']									= ( isset($detail['patent']['entry']['title']) )?$detail['patent']['entry']['title']:'';
				$researcher['ResearcherDetail']['patent'][0]['summary']									= ( isset($detail['patent']['entry']['summary']) )?$detail['patent']['entry']['summary']:'';
				$researcher['ResearcherDetail']['patent'][0]['application_id']							= ( isset($detail['patent']['entry']['rm_application']['id']) )?$detail['patent']['entry']['rm_application']['id']:'';
				$researcher['ResearcherDetail']['patent'][0]['application_applicationDate']				= ( isset($detail['patent']['entry']['rm_application']['rm_applicationDate']) )?$detail['patent']['entry']['rm_application']['rm_applicationDate']:'';
				$researcher['ResearcherDetail']['patent'][0]['public_id']								= ( isset($detail['patent']['entry']['rm_public']['id']) )?$detail['patent']['entry']['rm_public']['id']:'';
				$researcher['ResearcherDetail']['patent'][0]['public_publicDate']						= ( isset($detail['patent']['entry']['rm_public']['rm_publicDate']) )?$detail['patent']['entry']['rm_public']['rm_publicDate']:'';
				$researcher['ResearcherDetail']['patent'][0]['translation_id']							= ( isset($detail['patent']['entry']['rm_translation']['id']) )?$detail['patent']['entry']['rm_translation']['id']:'';
				$researcher['ResearcherDetail']['patent'][0]['translation_translationDate']				= ( isset($detail['patent']['entry']['rm_translation']['rm_translationDate']) )?$detail['patent']['entry']['rm_translation']['rm_translationDate']:'';
				$researcher['ResearcherDetail']['patent'][0]['patent_id']								= ( isset($detail['patent']['entry']['rm_patent']['id']) )?$detail['patent']['entry']['rm_patent']['id']:'';
				$researcher['ResearcherDetail']['patent'][0]['patent_patentDate']						= ( isset($detail['patent']['entry']['rm_patent']['rm_patentDate']) )?$detail['patent']['entry']['rm_patent']['rm_patentDate']:'';
				$researcher['ResearcherDetail']['patent'][0]['applicationPerson']						= ( isset($detail['patent']['entry']['rm_applicationPerson']) )?$detail['patent']['entry']['rm_applicationPerson']:'';
				$researcher['ResearcherDetail']['patent'][0]['jglobalid']								= ( isset($detail['patent']['entry']['rm_jglobalid']) )?$detail['patent']['entry']['rm_jglobalid']:'';
			}
			else
			{
				foreach ( $detail['patent']['entry'] as $key => $patent )
				{
					$researcher['ResearcherDetail']['patent'][$key]['title']							= ( isset($patent['title']) )?$patent['title']:'';
					$researcher['ResearcherDetail']['patent'][$key]['summary']							= ( isset($patent['summary']) )?$patent['summary']:'';
					$researcher['ResearcherDetail']['patent'][$key]['application_id']					= ( isset($patent['rm_application']['id']) )?$patent['rm_application']['id']:'';
					$researcher['ResearcherDetail']['patent'][$key]['application_applicationDate']		= ( isset($patent['rm_application']['rm_applicationDate']) )?$patent['rm_application']['rm_applicationDate']:'';
					$researcher['ResearcherDetail']['patent'][$key]['public_id']						= ( isset($patent['rm_public']['id']) )?$patent['rm_public']['id']:'';
					$researcher['ResearcherDetail']['patent'][$key]['public_publicDate']				= ( isset($patent['rm_public']['rm_publicDate']) )?$patent['rm_public']['rm_publicDate']:'';
					$researcher['ResearcherDetail']['patent'][$key]['translation_id']					= ( isset($patent['rm_translation']['id']) )?$patent['rm_translation']['id']:'';
					$researcher['ResearcherDetail']['patent'][$key]['translation_translationDate']		= ( isset($patent['rm_translation']['rm_translationDate']) )?$patent['rm_translation']['rm_translationDate']:'';
					$researcher['ResearcherDetail']['patent'][$key]['patent_id']						= ( isset($patent['rm_patent']['id']) )?$patent['rm_patent']['id']:'';
					$researcher['ResearcherDetail']['patent'][$key]['patent_patentDate']				= ( isset($patent['rm_patent']['rm_patentDate']) )?$patent['rm_patent']['rm_patentDate']:'';
					$researcher['ResearcherDetail']['patent'][$key]['applicationPerson']				= ( isset($patent['rm_applicationPerson']) )?$patent['rm_applicationPerson']:'';
					$researcher['ResearcherDetail']['patent'][$key]['jglobalid']						= ( isset($patent['rm_jglobalid']) )?$patent['rm_jglobalid']:'';
				}
			}
		}
		
		// socialContribution
		if ( isset($detail['socialContribution']['entry']))
		{
			if ( isset($detail['socialContribution']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['socialContribution'][0]['title']						= ( isset($detail['socialContribution']['entry']['title']) )?$detail['socialContribution']['entry']['title']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['summary']						= ( isset($detail['socialContribution']['entry']['summary']) )?$detail['socialContribution']['entry']['summary']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['role_id']						= ( isset($detail['socialContribution']['entry']['rm_role']['id']) )?$detail['socialContribution']['entry']['rm_role']['id']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['role_name']					= ( isset($detail['socialContribution']['entry']['rm_role']['name']) )?$detail['socialContribution']['entry']['rm_role']['name']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['promoter']					= ( isset($detail['socialContribution']['entry']['rm_promoter']) )?$detail['socialContribution']['entry']['rm_promoter']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['event']						= ( isset($detail['socialContribution']['entry']['rm_event']) )?$detail['socialContribution']['entry']['rm_event']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['fromDate']					= ( isset($detail['socialContribution']['entry']['rm_fromDate']) )?$detail['socialContribution']['entry']['rm_fromDate']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['toDate']						= ( isset($detail['socialContribution']['entry']['rm_toDate']) )?$detail['socialContribution']['entry']['rm_toDate']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['location']					= ( isset($detail['socialContribution']['entry']['rm_location']) )?$detail['socialContribution']['entry']['rm_location']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['eventType_id']				= ( isset($detail['socialContribution']['entry']['rm_eventType']['id']) )?$detail['socialContribution']['entry']['rm_eventType']['id']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['eventType_name']				= ( isset($detail['socialContribution']['entry']['rm_eventType']['name']) )?$detail['socialContribution']['entry']['rm_eventType']['name']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['target_id']					= ( isset($detail['socialContribution']['entry']['rm_target']['id']) )?$detail['socialContribution']['entry']['rm_target']['id']:'';
				$researcher['ResearcherDetail']['socialContribution'][0]['target_name']					= ( isset($detail['socialContribution']['entry']['rm_target']['name']) )?$detail['socialContribution']['entry']['rm_target']['name']:'';
			}
			else
			{
				foreach ( $detail['socialContribution']['entry'] as $key => $socialContribution )
				{
					$researcher['ResearcherDetail']['socialContribution'][$key]['title']				= ( isset($socialContribution['title']) )?$socialContribution['title']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['summary']				= ( isset($socialContribution['summary']) )?$socialContribution['summary']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['role_id']				= ( isset($socialContribution['rm_role']['id']) )?$socialContribution['rm_role']['id']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['role_name']			= ( isset($socialContribution['rm_role']['name']) )?$socialContribution['rm_role']['name']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['promoter']				= ( isset($socialContribution['rm_promoter']) )?$socialContribution['rm_promoter']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['event']				= ( isset($socialContribution['rm_event']) )?$socialContribution['rm_event']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['fromDate']				= ( isset($socialContribution['rm_fromDate']) )?$socialContribution['rm_fromDate']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['toDate']				= ( isset($socialContribution['rm_toDate']) )?$socialContribution['rm_toDate']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['location']				= ( isset($socialContribution['rm_location']) )?$socialContribution['rm_location']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['eventType_id']			= ( isset($socialContribution['rm_eventType']['id']) )?$socialContribution['rm_eventType']['id']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['eventType_name']		= ( isset($socialContribution['rm_eventType']['name']) )?$socialContribution['rm_eventType']['name']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['target_id']			= ( isset($socialContribution['rm_target']['id']) )?$socialContribution['rm_target']['id']:'';
					$researcher['ResearcherDetail']['socialContribution'][$key]['target_name']			= ( isset($socialContribution['rm_target']['name']) )?$socialContribution['rm_target']['name']:'';
				}
			}
		}
		
		// other
		if ( isset($detail['other']['entry']))
		{
			if ( isset($detail['other']['entry']['@attributes']['rm_type']) )
			{
				$researcher['ResearcherDetail']['other'][0]['title']									= ( isset($detail['other']['entry']['title']) )?$detail['other']['entry']['title']:'';
				$researcher['ResearcherDetail']['other'][0]['summary']									= ( isset($detail['other']['entry']['summary']) )?$detail['other']['entry']['summary']:'';
			}
			else
			{
				foreach ( $detail['other']['entry'] as $key => $other )
				{
					$researcher['ResearcherDetail']['other'][$key]['title']								= ( isset($other['title']) )?$other['title']:'';
					$researcher['ResearcherDetail']['other'][$key]['summary']							= ( isset($other['summary']) )?$other['summary']:'';
				}
			}
		}
		
		$ret = '';
		$last_id = 0;
		$rollback = false;
		
		$this->Researcher->begin();
		
		$save['Researcher'] = $researcher['Researcher'];
		
		if ( $this->Researcher->save($save) )
		{
			$last_id = $this->Researcher->getLastInsertID();
		}
		else
		{
			$rollback = true;
		}
		
		if ( !$rollback )
		{
			// 保存用のデータ作成
			$data = array();
			$ix = 0;
			foreach ($researcher['ResearcherDetail'] as $type => $details)
			{
				if ( !empty($details) )
				{
					$type_number = $this->_get_researcher_detail_type ( $type );
					foreach ( $details as $detail )
					{
						// 初期化
						for ( $i=1;$i<=30;$i++ )
						{
							$data[$ix]['col' . $i ] = '';
						}
						for ( $i=1;$i<=30;$i++ )
						{
							$data[$ix]['val' . $i ] = '';
						}
						
						$data[$ix]['researcher_id']	= $last_id;
						$data[$ix]['type']			= $type_number;
						
						$x=1;
						foreach ( $detail as $col => $val )
						{
							$data[$ix]['col' . $x] = $col;
							$data[$ix]['val' . $x] = ( is_string($val) )?$val:'';
							$x++;
						}
						$ix++;
					}
					
				}
				
			}
			
			if ( !empty($data) )
			{
				foreach ( $data as $save )
				{
					$this->ResearcherDetail->create();
					if ( !$this->ResearcherDetail->save( $save ) )
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
}
