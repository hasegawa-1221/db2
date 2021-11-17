<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
class DatabasesController extends AppController {

	public $uses = array(
		'Event',
		'Theme',
		'EventTheme',
		'Expense',
		'EventManager',
		'EventAffair',
		'EventKeyword',
		'User',
		'Affiliation',
		'Item',
		'Prefecture',
		
		// researchmap関連model
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
		'ResearcherSocialContribution',
		'EventProgram',
		'EventPerformer',
		'EventFile',
		
		//
		'Migration',
		'MigrationChapter',
		'MigrationPage',
		'Meeting',
		'ResearchCase',
		'Venue'
	);

	public $layout = "database";

	public $components = array(
		'Session',
	);
	
	public $develop = 0;

	public function beforeFilter()
	{
		parent::beforeFilter();
		
		// ログインなしで閲覧出来る画面
		$this->Auth->allow(
			'redirect_database',
			'index',
			'migrations',
			'researchers',
			'meetings',
			'reports',
			'organizations',
			'venues',
			'cases',
			'migration_detail',
			'researcher_detail',
			'meeting_detail',
			'report_detail',
			'organization_detail',
			'venue_detail',
			'case_detail',
			'add1',
			'add2',
			'add3',
			'add4',
			'add5',
			'add_confirm',
			'add_complete',
			'autocomplete'
		);
		
		// ログイン中の情報をViewへ渡す
		$this->set('auth_user', $this->Auth->user());
	}

	// トップページドロップダウンの遷移先
	private function redirect_database ( $id = null )
	{
		switch ( $id )
		{
			case 1:
				$action = 'migrations';
				break;
			case 2:
				$action = 'researchers';
				break;
			case 3:
				$action = 'meetings';
				break;
			case 4:
				$action = 'reports';
				break;
			case 5:
				$action = 'organizations';
				break;
			case 6:
				$action = 'venues';
				break;
			case 7:
				$action = 'cases';
				break;
			default:
				$action = 'index';
		}
		return $action;
	}

	// ログイン
	public function login() {
		
		if ( $this->Auth->user() )
		{
			$this->redirect(array('action' => 'mypage'));
		}
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect(array('controller' => 'databases', 'action' => 'mypage'));
			}
			$this->Session->SetFlash('ID か パスワードが間違っています。', 'Flash/error');
		}
	}

	// ログアウト
	public function logout() {
		$this->Auth->logout();
    	$this->Session->delete("Auth.Event");
    	$this->Session->destroy();
		$this->redirect(array('action' => 'login'));
	}

	// ログイン後トップページ
	public function mypage() {
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme'
			),
			'conditions' => array(
				'Event.id' => $this->Auth->user('id'),
			),
		));
		$this->set('event', $event);
	}

	// トップページ
	public function index() {
		
		if ( $this->request->is('post') )
		{
			
			$action = $this->redirect_database($this->request->data['Database']['id']);
			
			if ( $action == 'index' )
			{
				$this->Session->setFlash('データベースを選択して下さい。', 'Flash/error');
			}
			
			// ページ遷移
			$this->redirect( array( 'action' => $action) );
		}
		
		
		// データベースのリスト取得
		$database_list = Configure::read('App.databases');
		$this->set('database_list', $database_list);
	}

	// 1:数学カタログ
	public function migrations ()
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
			// デフォルト
			$this->request->data['Search']['keyword'] = '';
		}
		
		$conditions = array();
		$conditions += array('Migration.is_delete' => 0);
		$conditions += array('Migration.is_display' => 1);
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array(
				'OR' => array(
					'Migration.title LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%',
					'Migration.body LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%'
				)
			);
		}
		
		// 削除されている章・項は取得しない
		$this->Migration->hasMany['MigrationChapter']['conditions'] = array('MigrationChapter.is_delete' => 0);
		$this->MigrationChapter->hasMany['MigratioPage']['conditions'] = array('MigratioPage.is_delete' => 0);
		
		$this->modelClass = 'Migration';
		$this->paginate = array(
			'contain' => array(
				'MigrationChapter' => array(
					'MigrationPage'
				)
			),
			'conditions' => $conditions,
			'order' => 'Migration.sort ASC',
			'limit' => 20
		);
		
		$migrations = $this->paginate();
		$this->set('migrations', $migrations);
	}

	// 1:数学カタログ詳細
	public function migration_detail ( $id = null)
	{
		$this->layout = 'migrations';
		
		// データの存在確認
		$this->Migration->id = $id;
		if ( !$this->Migration->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// 数学カタログデータ取得
		$migration = $this->Migration->find('first', array(
			'contain' => array(
				'MigrationChapter' => array(
					'MigrationPage'
				)
			),
			'conditions' => array(
			'Migration.id' => $id,
			'Migration.is_display' => 1,
			'Migration.is_delete' => 0
			),
		));
		
		if ( empty($migration) )
		{
			$this->Session->setFlash('データが見つかりませんでした。');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('migration', $migration);
	}

	// 2:研究者データベース
	public function researchers ()
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
		$conditions += array('Researcher.is_display' => 1);
		
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

	// 2:研究者データベース（詳細）
	public function researcher_detail ( $id = null )
	{
		// データの存在確認
		$this->Researcher->id = $id;
		if ( !$this->Researcher->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// 研究者データ取得
		$researcher = $this->Researcher->find('first', array(
			'contain' => array(
				'ResearcherResearchKeyword',
				'ResearcherResearchArea',
				'ResearcherCareer',
				'ResearcherAcademicBackground',
				'ResearcherCommitteeCareer',
				'ResearcherPrize',
				'ResearcherPaper',
				'ResearcherBiblio',
				'ResearcherConference',
				'ResearcherTeachingExperience',
				'ResearcherAcademicSociety',
				'ResearcherCompetitiveFund',
				'ResearcherPatent',
				'ResearcherSocialContribution',
				'ResearcherOther'
			),
			'conditions' => array(
				'Researcher.id' => $id,
				'Researcher.is_display' => 1,
				'Researcher.is_delete' => 0
			),
		));
		if ( empty($researcher) )
		{
			$this->Session->setFlash('データが見つかりませんでした。');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('researcher', $researcher);
	}

	// 3:研究集会データベース
	public function meetings ()
	{
		
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
		$conditions += array('Meeting.is_delete' => 0);
		$conditions += array('Meeting.is_display' => 1);
		
		// 研究集会タイトル
		if ( isset($this->request->data['Search']['title']) && !empty($this->request->data['Search']['title']) )
		{
			$conditions += array('Meeting.title LIKE ?'	=> '%' . trim($this->request->data['Search']['title']) . '%');
		}
		
		// 研究分野
		if ( isset($this->request->data['Search']['field']) && !empty($this->request->data['Search']['field']) )
		{
			$conditions += array('Meeting.field LIKE ?'	=> '%' . trim($this->request->data['Search']['field']) . '%');
		}
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions += array('Meeting.keyword LIKE ?'	=> '%' . trim($this->request->data['Search']['keyword']) . '%');
		}
		
		// 主催機関
		if ( isset($this->request->data['Search']['affiliation']) && !empty($this->request->data['Search']['affiliation']) )
		{
			$conditions += array('Meeting.affiliation LIKE ?'	=> '%' . trim($this->request->data['Search']['affiliation']) . '%');
		}
		
		// 開催場所
		if ( isset($this->request->data['Search']['place']) && !empty($this->request->data['Search']['place']) )
		{
			$conditions += array('Meeting.place LIKE ?'	=> '%' . trim($this->request->data['Search']['place']) . '%');
		}
		
		// 開始日
		if ( isset($this->request->data['Search']['start']) && !empty($this->request->data['Search']['start']) )
		{
			$conditions[] = array('Meeting.start >= ?'		=> $this->request->data['Search']['start']);
		}
		
		// 終了日
		if ( isset($this->request->data['Search']['end']) && !empty($this->request->data['Search']['end']) )
		{
			$conditions[] = array('Meeting.end <= ?'		=> $this->request->data['Search']['end']);
		}
		
		$this->modelClass = 'Meeting';
		$this->paginate = array(
			'contain' => array(
			),
			'conditions' => $conditions,
			'order' => 'Meeting.id DESC',
			'limit' => 20
		);
		
		$meetings = $this->paginate();
		$this->set('meetings', $meetings);
	}

	// 3:研究集会データベース詳細
	public function meeting_detail ($id = null)
	{
		// データの存在確認
		$this->Meeting->id = $id;
		if ( !$this->Meeting->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// 企画データ取得
		$meeting = $this->Meeting->find('first', array(
			'contain' => array(
				'Event',
				'MeetingFile'
			),
			'conditions' => array(
				'Meeting.id' => $id,
				'Meeting.is_display' => 1,
				'Meeting.is_delete' => 0,
			),
		));
		if ( empty($meeting) )
		{
			$this->Session->setFlash('データが見つかりませんでした。');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('meeting', $meeting);
	}
	
	// 4:講演課題データベース
	public function reports ()
	{
		
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
		$joins[] = array(
			'type'	=> 'LEFT',
			'table'	=> 'meetings',
			'alias'	=> 'Meeting',
			'conditions' => 'EventProgram.event_id = Meeting.event_id',
		);
		$joins[] = array(
			'type'	=> 'LEFT',
			'table'	=> 'event_performers',
			'alias'	=> 'EventPerformer2',
			'conditions' => 'EventProgram.id = EventPerformer2.event_program_id',
		);
		
		$conditions = array();
		$conditions += array('EventProgram.is_display' => 1);
		$conditions += array('EventProgram.is_delete' => 0);
		
		// EventProgram検索
		// 講演課題
		if ( isset($this->request->data['Search']['title']) && !empty($this->request->data['Search']['title']) )
		{
			$conditions += array('EventProgram.title LIKE ?'	=> '%' . trim($this->request->data['Search']['title']) . '%');
		}
		
		// EventPerformer検索
		// 講演者所属
		if ( isset($this->request->data['Search']['organization']) && !empty($this->request->data['Search']['organization']) )
		{
			$conditions += array('EventPerformer2.organization LIKE ?'	=> '%' . trim($this->request->data['Search']['organization']) . '%');
		}
		
		// 講演者名
		if ( isset($this->request->data['Search']['lastname']) && !empty($this->request->data['Search']['lastname']) )
		{
			$conditions += array('EventPerformer2.lastname LIKE ?'	=> '%' . trim($this->request->data['Search']['lastname']) . '%');
		}
		
		// 講演者名
		if ( isset($this->request->data['Search']['firstname']) && !empty($this->request->data['Search']['firstname']) )
		{
			$conditions += array('EventPerformer2.firstname LIKE ?'	=> '%' . trim($this->request->data['Search']['firstname']) . '%');
		}
		
		// Meetingテーブル検索
		// 企画番号
		if ( isset($this->request->data['Search']['event_number']) && !empty($this->request->data['Search']['event_number']) )
		{
			$conditions += array('Meeting.event_number LIKE ?'	=> '%' . trim($this->request->data['Search']['event_number']) . '%');
		}
		
		// 研究集会
		if ( isset($this->request->data['Search']['report_title']) && !empty($this->request->data['Search']['report_title']) )
		{
			$conditions += array('Meeting.title LIKE ?'	=> '%' . trim($this->request->data['Search']['report_title']) . '%');
		}
		
		// 主催機関
		if ( isset($this->request->data['Search']['report_organization']) && !empty($this->request->data['Search']['report_organization']) )
		{
			$conditions += array('Meeting.organization LIKE ?'	=> '%' . trim($this->request->data['Search']['report_organization']) . '%');
		}
		
		// 開催場所
		if ( isset($this->request->data['Search']['report_place']) && !empty($this->request->data['Search']['report_place']) )
		{
			$conditions += array('Meeting.place LIKE ?'	=> '%' . trim($this->request->data['Search']['report_place']) . '%');
		}
		
		$this->EventProgram->hasMany['EventPerformer']['conditions'] = array('EventPerformer.is_delete' => 0);
		$this->modelClass = 'EventProgram';
		$this->paginate = array(
			'contain' => array(
				'EventPerformer',
			),
			'fields' => array(
				'EventProgram.*'
			),
			'conditions' => $conditions,
			'joins' => $joins,
			'order' => 'EventProgram.id DESC',
			'limit' => 20,
			'group' => 'EventProgram.id'
		);
		
		$event_programs = $this->paginate();
		
		if ( !empty($event_programs) )
		{
			foreach ( $event_programs AS $key =>  $event_program )
			{
				$meeting = $this->Meeting->find('first', array(
					'contain' => array(),
					'conditions' => array('Meeting.event_id' => $event_program['EventProgram']['event_id'], 'Meeting.is_display' => 1, 'Meeting.is_delete' => 0 )
				));
				if ( isset($event_programs[$key]) &&  isset($meeting['Meeting']) )
				{
					$event_programs[$key]['Meeting'] = $meeting['Meeting'];
				}
			}
		}
		
		$this->set('event_programs', $event_programs);
	}

	// 4:講演課題データベース詳細
	public function report_detail ($id = null)
	{
		// データの存在確認
		$this->EventProgram->id = $id;
		if ( !$this->EventProgram->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$joins = array();
		$joins[] = array(
			'type'	=> 'LEFT',
			'table'	=> 'event_performers',
			'alias'	=> 'EventPerformer2',
			'conditions' => 'EventProgram.id = EventPerformer2.event_program_id',
		);
		
		$conditions = array();
		$conditions += array('EventProgram.id' => $id);
		$conditions += array('EventProgram.is_display' => 1);
		$conditions += array('EventProgram.is_delete' => 0);
		$joins = array();
		$joins[] = array(
			'type'	=> 'LEFT',
			'table'	=> 'meetings',
			'alias'	=> 'Meeting',
			'conditions' => 'EventProgram.event_id = Meeting.event_id',
		);
		
		// 講演課題データ取得
		$this->EventProgram->hasMany['EventPerformer']['conditions'] = array('EventPerformer.is_delete' => 0);
		$event_program = $this->EventProgram->find('first', array(
			'contain' => array(
				'EventPerformer',
			),
			'fields' => array(
				'EventProgram.*',
				'Meeting.*'
			),
			'joins' => $joins,
			'conditions' => $conditions,
		));
		
		if ( empty($event_program) )
		{
			$this->Session->setFlash('データが見つかりませんでした。');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('event_program', $event_program);
	}

	// 5:研究機関データベース
	public function organizations ()
	{
		$conditions = array();
		$conditions += array('Affiliation.is_display' => 1);
		$conditions += array('Affiliation.is_delete' => 0);
		
		// EventProgram検索
		// 研究機関名
		if ( isset($this->request->data['Search']['name']) && !empty($this->request->data['Search']['name']) )
		{
			$conditions += array('Affiliation.name LIKE ?'	=> '%' . trim($this->request->data['Search']['name']) . '%');
		}
		// 都道府県
		if ( isset($this->request->data['Search']['prefecture_id']) && !empty($this->request->data['Search']['prefecture_id']) )
		{
			$conditions += array('Affiliation.prefecture_id'	=> $this->request->data['Search']['prefecture_id']);
		}
		
		$this->modelClass = 'Affiliation';
		$this->paginate = array(
			'contain' => array(
			),
			'conditions' => $conditions,
			'order' => 'Affiliation.id DESC',
			'limit' => 20
		);
		
		$affiliations = $this->paginate();
		$this->set('affiliations', $affiliations);
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// 5:研究機関データベース詳細
	public function organization_detail ($id = null)
	{
		// データの存在確認
		$this->Affiliation->id = $id;
		if ( !$this->Affiliation->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$conditions = array();
		$conditions += array('Affiliation.id' => $id);
		$conditions += array('Affiliation.is_display' => 1);
		$conditions += array('Affiliation.is_delete' => 0);
		// 企画データ取得
		$affiliation = $this->Affiliation->find('first', array(
			'contain' => array(
			),
			'conditions' => $conditions,
		));
		
		if ( empty($affiliation) )
		{
			$this->Session->setFlash('データが見つかりませんでした。');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('affiliation', $affiliation);
		
		$prefectures = $this->Prefecture->find('list', array('contain' => array(), 'conditions' => array()));
		$this->set('prefectures', $prefectures);
	}

	// 6:研究会場データベース
	public function venues ()
	{
		$conditions = array();
		$conditions += array('Venue.is_display' => 1);
		$conditions += array('Venue.is_delete' => 0);
		
		// 研究機関名
		if ( isset($this->request->data['Search']['name']) && !empty($this->request->data['Search']['name']) )
		{
			$conditions += array('Venue.name LIKE ?'	=> '%' . trim($this->request->data['Search']['name']) . '%');
		}
		// 都道府県
		if ( isset($this->request->data['Search']['prefecture_id']) && !empty($this->request->data['Search']['prefecture_id']) )
		{
			$conditions += array('Venue.prefecture_id'	=> $this->request->data['Search']['prefecture_id']);
		}
		
		$this->modelClass = 'Venue';
		$this->paginate = array(
			'contain' => array(
			),
			'conditions' => $conditions,
			'order' => 'Venue.id DESC',
			'limit' => 20
		);
		
		$venues = $this->paginate();
		$this->set('venues', $venues);
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// 6:研究会場データベース詳細
	public function venue_detail ($id = null)
	{
		// データの存在確認
		$this->Venue->id = $id;
		if ( !$this->Venue->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// 企画データ取得
		$conditions = array();
		$conditions += array('Venue.id' => $id);
		$conditions += array('Venue.is_display' => 1);
		$conditions += array('Venue.is_delete' => 0);
		
		$venue = $this->Venue->find('first', array(
			'contain' => array(
			),
			'conditions' => $conditions,
		));
		if ( empty($venue) )
		{
			$this->Session->setFlash('データが見つかりませんでした。');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('venue', $venue);
		
		$prefectures = $this->Prefecture->find('list', array('contain' => array(), 'conditions' => array()));
		$this->set('prefectures', $prefectures);
	}

	// 7:研究事例データベース
	public function cases ()
	{
		$conditions = array();
		$conditions += array(
			'ResearchCase.is_display' => 1,
			'ResearchCase.is_delete' => 0,
		);
		
		// タイトル
		if ( isset($this->request->data['Search']['title']) && !empty($this->request->data['Search']['title']) )
		{
			$conditions += array('ResearchCase.title LIKE ?'	=> '%' . trim($this->request->data['Search']['title']) . '%');
		}
		// 研究者名
		if ( isset($this->request->data['Search']['researcher']) && !empty($this->request->data['Search']['researcher']) )
		{
			$conditions += array('ResearchCase.researcher LIKE ?'	=> '%' . trim($this->request->data['Search']['researcher']) . '%');
		}
		// ｷｰﾜｰﾄﾞ
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions += array('ResearchCase.keyword LIKE ?'	=> '%' . trim($this->request->data['Search']['keyword']) . '%');
		}
		
		$this->modelClass = 'ResearchCase';
		$this->paginate = array(
			'contain' => array(
			),
			'conditions' => $conditions,
			'order' => 'ResearchCase.id DESC',
			'limit' => 20
		);
		
		$cases = $this->paginate();
		$this->set('cases', $cases);
	}

	// 7:研究事例データベース詳細
	public function case_detail ($id = null)
	{
		// データの存在確認
		$this->ResearchCase->id = $id;
		if ( !$this->ResearchCase->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		// 企画データ取得
		$case = $this->ResearchCase->find('first', array(
			'contain' => array(
			),
			'conditions' => array(
				'ResearchCase.id' => $id,
				'ResearchCase.is_display' => 1,
				'ResearchCase.is_delete' => 0,
			),
		));
		if ( empty($case) )
		{
			$this->Session->setFlash('データが見つかりませんでした。');
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('case', $case);
	}

	/**********************************************************
	 * 企画新規作成
	 */
	 
	// 1ページ目 企画の概要
	public function add1() {
		
		$this->Session->write('page', 1);
		
		// バリデーションの設定を避難
		$event_validate			= $this->Event->validate;
		$event_keyword_validate	= $this->EventKeyword->validate;
		$event_theme_validate	= $this->EventTheme->validate;
		
		// 途中保存時はバリデーションを外す
		$this->Event->validate = array();
		$this->EventKeyword->validate = array();
		$this->EventTheme->validate = array();
		
		if( $this->request->is('post') )
		{
				
			
			
			if ( isset($this->request->data['update']) )
			{
				
				$rollback = false;
				$this->Event->begin();
				
				//print_a_die($this->request->data);
				
				if ( !$this->Event->save( $this->request->data['Event'] ) )
				{
					$rollback = true;
				}
				else
				{
					$last_id = $this->Event->getLastInsertID();
				}
				
				// EventThemeテーブル挿入用
				$event_theme['EventTheme'] = $this->request->data['EventTheme'];
				if ( !$rollback )
				{
					foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
					{
						if( $theme['id'] == 1)
						{
							$save = array();
							$save['EventTheme']['event_id'] = $last_id;
							$save['EventTheme']['theme_id'] = $theme_id;
							$this->EventTheme->create();
							if ( !$this->EventTheme->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				// キーワード
				$event_keyword['EventKeyword'] = $this->request->data['EventKeyword'];
				if ( !$rollback )
				{
					foreach ( $event_keyword['EventKeyword'] as  $event_keyword )
					{
						if( !empty($event_keyword['title']) )
						{
							$save = array();
							$save['EventKeyword']['event_id'] = $last_id;
							$save['EventKeyword']['title'] = $event_keyword['title'];
							$this->EventKeyword->create();
							if ( !$this->EventKeyword->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				
				if ( !$rollback )
				{
					$this->Event->commit();
					
					$this->Auth->logout();
			    	$this->Session->delete("Auth.Event");
			    	$this->Session->destroy();
					
					if ( $this->Auth->login() )
					{
						if ( isset($this->request->data['update']) )
						{
							$this->Session->setFlash('一時保存しました。', 'Flash/success');
							$this->redirect(array('action' => 'edit1'));
						}
						else
						{
							$this->Session->write('Event.Edit1', $this->request->data);
							$this->redirect(array('action' => 'edit2'));
						}
					}
				}
				else
				{
					$this->Event->rollback();
				}
			}
			else
			{
				// バリデーションを戻す
				$this->Event->validate			= $event_validate;
				$this->EventKeyword->validate	= $event_keyword_validate;
				$this->EventTheme->validate		= $event_theme_validate;
				
				$rollback = false;
				$this->Event->begin();
				
				if ( !$this->Event->save( $this->request->data['Event'] ) )
				{
					$rollback = true;
				}
				else
				{
					$last_id = $this->Event->getLastInsertID();
				}
				
				// EventThemeテーブル挿入用
				$checked_count = 0;
				$event_theme['EventTheme'] = $this->request->data['EventTheme'];
				foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
				{
					if( $theme['id'] == 1)
					{
						$checked_count++;
					}
				}
				if ( $checked_count == 0 )
				{
					$rollback = true;
					$this->EventTheme->validationErrors['id'][] = '該当する集会等のタイプをお選び下さい。';
				}
				
				if ( !$rollback )
				{
					foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
					{
						if( $theme['id'] == 1)
						{
							$checked_count++;
							$save = array();
							$save['EventTheme']['event_id'] = $last_id;
							$save['EventTheme']['theme_id'] = $theme_id;
							$this->EventTheme->create();
							if ( !$this->EventTheme->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				
				// キーワード
				$input_count = 0;
				$event_keyword['EventKeyword'] = $this->request->data['EventKeyword'];
				foreach ( $event_keyword['EventKeyword'] as  $ek )
				{
					if( !empty($ek['title']) )
					{
						$input_count++;
					}
				}
				
				if ( $input_count == 0 )
				{
					$rollback = true;
					$this->EventKeyword->validationErrors[0]['title'][] = 'キーワードを入力して下さい。';
				}
				
				
				if ( !$rollback )
				{
					foreach ( $event_keyword['EventKeyword'] as  $event_keyword )
					{
						if( !empty($event_keyword['title']) )
						{
							$input_count++;
							$save = array();
							$save['EventKeyword']['event_id'] = $last_id;
							$save['EventKeyword']['title'] = $event_keyword['title'];
							$this->EventKeyword->create();
							if ( !$this->EventKeyword->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
					
					$this->Auth->logout();
			    	$this->Session->delete("Auth.Event");
			    	$this->Session->destroy();
					
					if ( $this->Auth->login() )
					{
						if ( isset($this->request->data['update']) )
						{
							$this->Session->setFlash('一時保存しました。', 'Flash/success');
							$this->redirect(array('action' => 'edit1'));
						}
						else
						{
							$this->Session->write('Event.Edit1', $this->request->data);
							$this->redirect(array('action' => 'edit2'));
						}
					}
				}
				else
				{
					$this->Event->rollback();
				}
			}
		}
		else
		{
			
		}
		
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
	}

	// 2ページ目 企画の詳細
	public function add2() {
		
		$this->Session->write('page', 2);
		
		if( $this->request->is('post') )
		{
			$this->Session->write('Event.Add2', $this->request->data);
			
			// 保存する
			if ( isset($this->request->data['update']) )
			{
				$this->Session->write('Event.Add2', $this->request->data);
				$this->Session->setFlash('セッションに保存しました。', 'Flash/success');
			}
			// 次へ
			else
			{
				$this->Event->set($this->request->data);
				if ($this->Event->validates() )
				{
					// 支援アリの場合
					if ( $this->request->data['Event']['is_support'] == 1 )
					{
						if ( empty($this->request->data['Event']['support']) )
						{
							$this->Event->validationErrors['support'][] = '支援元を入力してください。';
						}
					}
					
					if ( empty($this->Event->validationErrors) )
					{
						$this->redirect(array('action' => 'add3'));
					}
				}
			}
		}
		else
		{
			$this->request->data = $this->Session->read('Event.Add2');
			if ( !isset($this->request->data['Event']['is_support']) || 
				isset($this->request->data['Event']['is_support']) && $this->request->data['Event']['is_support'] == '' )
			{
				$this->request->data['Event']['is_support'] = 0;
			}
		}
		$options1 = array(
			'1' => '有',
			'0' => '無',
		);
		$this->set('options1', $options1);
	}

	// 3ページ目 経費
	public function add3() {
		
		$this->Session->write('page', 3);
		
		// 課目ドロップダウン用データ
		$items = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0, 'Item.parent_id <>' => 0)));
		$items[0] = '----';
		ksort($items);
		$this->set('items', $items);
		
		if( $this->request->is('post') )
		{
			// 保存する
			if ( isset($this->request->data['update']) )
			{
				$this->Session->write('Event.Add3', $this->request->data);
				$this->Session->setFlash('セッションに保存しました。', 'Flash/success');
			}
			else
			{
				$this->Session->write('Event.Add3', $this->request->data);
				
				$errors = array();
				foreach ( $this->request->data['Expense'] as $key1 => $expenses )
				{
					foreach ( $expenses as $key2 => $expense )
					{
						//print_a_die($expense);
						
						$this->Expense->set($expense);
						if ( !$this->Expense->validates() )
						{
							$errors[$key1][$key2] = $this->Expense->validationErrors;
						}
						
					}
				}
				
				$this->Expense->validationErrors = $errors;
				
				if ( empty($errors) )
				{
					$this->redirect(array('action' => 'add4'));
				}
			}
		}
		else
		{
			$this->request->data = $this->Session->read('Event.Add3');
			
			if ( empty($this->request->data) )
			{
				$this->request->data = array(
					'Expense' => array(
						'1' => array(
							'1' => array(
								'affiliation' => '',
								'job' => '',
								'lastname' => '',
								'firstname' => '',
								'title' => '',
								'request_price' => '',
								'note' => '',
							)
						),
						'2' => array(
							'1' => array(
								'affiliation' => '',
								'job' => '',
								'lastname' => '',
								'firstname' => '',
								'title' => '',
								'request_price' => '',
								'note' => '',
							)
						),
						'3' => array(
							'1' => array(
								'title' => '',
								'count' => '',
								'price' => '',
								'request_price' => '',
								'note' => '',
							)
						),
						'4' => array(
							'1' => array(
								'item_id' => '',
								'title' => '',
								'count' => '',
								'price' => '',
								'request_price' => '',
								'note' => '',
							)
						)
					)
				);
			}
		}
		
	}

	// 4ページ目 参加について
	public function add4() {
		
		$this->Session->write('page', 4);
		
		if( $this->request->is('post') )
		{
			$this->Session->write('Event.Add4', $this->request->data);
			// 保存する
			if ( isset($this->request->data['update']) )
			{
				$this->Session->write('Event.Add4', $this->request->data);
				$this->Session->setFlash('セッションに保存しました。', 'Flash/success');
			}
			else
			{
				$this->Event->set($this->request->data);
				if ($this->Event->validates() )
				{
					
					// 参加制限アリの場合
					if ( $this->request->data['Event']['qualification'] == 1 )
					{
						if ( empty($this->request->data['Event']['qualification_other']) )
						{
							$this->Event->validationErrors['qualification_other'][] = '有の場合は参加資格を入力してください。';
						}
					}
					
					if ( empty($this->Event->validationErrors) )
					{
						$this->redirect(array('action' => 'add5'));
					}
				}
			}
		}
		else
		{
			$this->request->data = $this->Session->read('Event.Add4');
			if ( !isset($this->request->data['Event']['qualification']) || 
				isset($this->request->data['Event']['qualification']) && $this->request->data['Event']['qualification'] == '' )
			{
				$this->request->data['Event']['qualification'] = 0;
			}
			if ( !isset($this->request->data['Event']['qualification_apply']) ||
				 isset($this->request->data['Event']['qualification_apply']) && $this->request->data['Event']['qualification_apply'] == '' )
			{
				$this->request->data['Event']['qualification_apply'] = 0;
			}
		}
		
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		$this->set('options3', array('1' => '有', '0' => '無'));
	}

	// 5ページ目 責任者
	public function add5() {
		
		// バリデーションの設定を避難
		$event_manager_validate	= $this->EventManager->validate;
		$event_affair_validate	= $this->EventAffair->validate;
		
		// 途中保存時はバリデーションを外す
		$this->EventManager->validate = array();
		$this->EventAffair->validate = array();
		
		if( $this->request->is('post') )
		{
			if ( isset($this->request->data['update']) )
			{
				foreach ( $this->request->data['EventManager'] as $key =>  $event_manager )
				{
					if ( isset($event_manager['is_delete']) && $event_manager['is_delete'] == 1 )
					{
						// DBに存在せず、セッションのみ存在するデータに削除チェックされた場合
						unset($this->request->data['EventManager'][$key]);
					}
				}
				
				foreach ( $this->request->data['EventAffair'] as $key => $event_affair )
				{
					if ( isset($event_affair['is_delete']) && $event_affair['is_delete'] == 1 )
					{
						// DBに存在せず、セッションのみ存在するデータに削除チェックされた場合
						unset($this->request->data['EventAffair'][$key]);
					}
				}
				$this->Session->setFlash('セッションに保存しました。', 'Flash/success');
				$this->Session->write('Event.Add5', $this->request->data);
			}
			// 運営責任者を増やすボタン
			else if ( isset($this->request->data['manager']) )
			{
				if ( !empty($this->request->data['EventManager']) )
				{
					$next = count($this->request->data['EventManager']);
					
					$this->request->data['EventManager'][$next] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				else
				{
					// 通常ありえないが万が一、一つもない場合
					$this->request->data['EventManager'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
			}
			// 事務担当者を増やすボタン押下時
			else if ( isset($this->request->data['affair']) )
			{
				if ( !empty($this->request->data['EventAffair']) )
				{
					$next = count($this->request->data['EventAffair']);
					
					$this->request->data['EventAffair'][$next] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				else
				{
					// 通常ありえないが万が一、一つもない場合
					$this->request->data['EventAffair'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
			}
			else
			{
				$this->Session->write('Event.Add5', $this->request->data);
				
				// バリデーション設定を元に戻す
				$this->EventManager->validate	= $event_manager_validate;
				$this->EventAffair->validate	= $event_affair_validate;
				
				$is_error = false;
				$is_checked = false;
				
				// 責任者の入チェック
				$managers_errors = array();
				foreach ( $this->request->data['EventManager'] as $key => $manager )
				{
					$this->EventManager->set( $manager );
					if ( !$this->EventManager->validates() )
					{
						$managers_errors[$key] = $this->EventManager->validationErrors;
					}
				}
				
				// 入力チェックの結果を変数に格納
				if ( !empty($managers_errors) )
				{
					$is_error = true;
					$this->EventManager->validationErrors = $managers_errors;
				}
				
				// 事務担当者の入チェック
				$affairs_errors = array();
				foreach ( $this->request->data['EventAffair'] as $key => $affairs )
				{
					$this->EventAffair->set( $affairs );
					if ( !$this->EventAffair->validates() )
					{
						$affairs_errors[$key] = $this->EventAffair->validationErrors;
					}
				}
				// 入力チェックの結果を変数に格納
				if ( !empty($affairs_errors) )
				{
					$is_error = true;
					$this->EventAffair->validationErrors = $affairs_errors;
				}
				
				// エラーがない場合
				if (!$is_error)
				{
					// ページ間でデータを持ち回すためセッションに保存
					$this->Session->write('Event.Add5', $this->request->data);
					
					// エラーがなければ次ページへ移動
					$this->redirect(array('action' => 'add_confirm'));
				}
				
				$this->Session->setFlash('入力内容に不備があります。', 'Flash/error');
				
				// バリデーションの設定を避難
				$event_manager_validate	= $this->EventManager->validate;
				$event_affair_validate	= $this->EventAffair->validate;
				
				// 途中保存時はバリデーションを外す
				$this->EventManager->validate = array();
				$this->EventAffair->validate = array();
			}
		}
		else
		{
			// 初期表示
			$session_data = $this->Session->read('Event.Add5');
			if ( empty($session_data) )
			{
				// 空の入力枠を１つ用意
				$event['EventManager'][0] = array(
					'id'				=> '',
					'email'				=> '',
					'lastname'			=> '',
					'firstname'			=> '',
					'lastname_kana'		=> '',
					'firstname_kana'	=> '',
					'organization'		=> '',
					'department'		=> '',
					'job_title'			=> '',
					'url'				=> '',
					'zip'				=> '',
					'prefecture_id'		=> 0,
					'city'				=> '',
					'address'			=> '',
					'tel'				=> '',
					'fax'				=> ''
				);
				$event['EventAffair'][0] = array(
					'id'				=> '',
					'email'				=> '',
					'lastname'			=> '',
					'firstname'			=> '',
					'lastname_kana'		=> '',
					'firstname_kana'	=> '',
					'organization'		=> '',
					'department'		=> '',
					'job_title'			=> '',
					'url'				=> '',
					'zip'				=> '',
					'prefecture_id'		=> 0,
					'city'				=> '',
					'address'			=> '',
					'tel'				=> '',
					'fax'				=> ''
				);
				$this->request->data = $event;
			}
			else
			{
				$this->request->data = $session_data;
			}
		}
		
		$this->set(compact('is_valid1', 'is_valid2', 'is_valid3'));
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// 6ページ目 入力内容確認画面
	public function add_confirm() {
		
		if ( $this->request->is('post') )
		{
			$data = $this->Session->read('Event');
			if ( !empty($data) )
			{
				$this->redirect(array('action' => 'add_complete'));
			}
			else
			{
				// セッション切れ
				$this->Session->setFlash('入力データが存在しませんでした。', 'Flash/error');
				$this->redirect(array('action' => 'add1'));
			}
		}
		else
		{
			$this->request->data = $this->Session->read('Event');
			
			// 経費から不要なデータ削除
			$expense_arr = array();
			if ( !empty($this->request->data['Add3']['Expense']) )
			{
				foreach ( $this->request->data['Add3']['Expense'] as $key1 => $expenses )
				{
					foreach ( $expenses as $key2 => $expense )
					{
						if ( $this->_is_input($expense) )
						{
							$expense_arr['Expense'][$key1][$key2] = $expense;
						}
					}
				}
			}
			
			if ( !empty($expense_arr) )
			{
				$this->request->data['Add3'] = $expense_arr;
			}
			
			// 上記フローで不要なものを省いたデータでセッション上書き
			$this->Session->write('Event', $this->request->data);
		}
		
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
		
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		$this->set('options3', array('1' => '有', '0' => '無'));
		
		// 都道府県ドロップダウン用データ
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
		
		// 課目ドロップダウン用データ
		$items = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0, 'Item.parent_id <>' => 0)));
		$items[0] = '----';
		ksort($items);
		$this->set('items', $items);
	}

	// 7ページ目 応募完了画面
	public function add_complete() {
		
		$data = $this->Session->read('Event');
		
		if ( !empty($data) )
		{
			$rollback = false;
			$last_id = 0;
			$this->Event->begin();
			
			// Eventテーブル挿入用
			$event['Event'] = $data['Add1']['Event'];
			$event['Event'] += $data['Add2']['Event'];
			$event['Event'] += $data['Add4']['Event'];
			
			// 現在の年度
			$fiscal_year					= $this->Event->get_fiscal_year();
			
			// 企画種別ごとのシリアル
			$event_serial					= $this->Event->get_next_serial($this->Event->get_fiscal_year(), 1);
			
			// 企画番号
			$event_number					= $this->Event->get_event_number(1);
			
			$event['Event']['type']			= 1;				// 企画種別
			$event['Event']['fiscal_year']	= $fiscal_year;	
			$event['Event']['event_serial']	= $event_serial;
			$event['Event']['event_number']	= $event_number;
			
			if ( !$this->Event->save($event) )
			{
				$rollback = true;
			}
			else
			{
				$last_id = $this->Event->getLastInsertID();
			}
			
			// EventThemeテーブル挿入用
			$event_theme['EventTheme'] = $data['Add1']['EventTheme'];
			if ( !$rollback )
			{
				foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
				{
					if( $theme['id'] == 1)
					{
						$save = array();
						$save['EventTheme']['event_id'] = $last_id;
						$save['EventTheme']['theme_id'] = $theme_id;
						$this->EventTheme->create();
						if ( !$this->EventTheme->save($save) )
						{
							$rollback = true;
							break;
						}
					}
				}
			}
			
			// キーワード
			$event_keyword['EventKeyword'] = $data['Add1']['EventKeyword'];
			if ( !$rollback )
			{
				foreach ( $event_keyword['EventKeyword'] as  $event_keyword )
				{
					if( !empty($event_keyword['title']) )
					{
						$save = array();
						$save['EventKeyword']['event_id'] = $last_id;
						$save['EventKeyword']['title'] = $event_keyword['title'];
						$this->EventKeyword->create();
						if ( !$this->EventKeyword->save($save) )
						{
							$rollback = true;
							break;
						}
					}
				}
			}
			
			// Expenseテーブル挿入用
			$expense['Expense'] = $data['Add3']['Expense'];
			if ( !$rollback )
			{
				foreach ( $expense['Expense'] as $type => $expenses )
				{
					foreach ( $expenses as $expense )
					{
						if ( $this->_is_input($expense))
						{
							if ( isset($expense['id']) )
							{
								unset($expense['id']);
							}
							if ( $type == 4 )
							{
								$expense['item_id'] = 0;
							}
							else
							{
								$expense['item_id'] = $type;
							}
							
							$expense['event_id']	= $last_id;
							$expense['type']		= $type;
							
							$save = array();
							$save = $expense;
							$this->Expense->create();
							if ( !$this->Expense->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
			}
			
			// EventManagerテーブル挿入用
			// 運営責任者
			$event_manager['EventManager'] = $data['Add5']['EventManager'];
			
			// EventManagerテーブルに挿入
			if ( !$rollback )
			{
				if ( !empty($event_manager) )
				{
					foreach ( $event_manager['EventManager'] as $manager )
					{
						$manager['event_id'] = $last_id;
						
						// ここではバリデーションしない
						$this->EventManager->validate = array();
						$this->EventManager->create();
						if ( !$this->EventManager->save($manager) )
						{
							$rollback = true;
							break;
						}
					}
				}
			}
			
			// 事務担当者
			$event_affair['EventAffair'] = $data['Add5']['EventAffair'];
			
			// EventAffairテーブルに挿入
			if ( !$rollback )
			{
				if ( !empty($event_affair) )
				{
					foreach ( $event_affair['EventAffair'] as $affair )
					{
						$affair['event_id'] = $last_id;
						
						// ここではバリデーションしない
						$this->EventAffair->validate = array();
						$this->EventAffair->create();
						if ( !$this->EventAffair->save($affair) )
						{
							$rollback = true;
							break;
						}
					}
				}
			}
			
			// 入力内容をメールで送る
			$mail_send = array();
			$mail_error = array();
			if ( !$rollback )
			{
				$themes = $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0)));
				$options1 = array('1' => '必要', '0' => '不要');
				$options2 = array('1' => '参加費あり', '0' => '参加費なし');
				
				$prefectures = $this->Prefecture->find('list');
				$prefectures[0] = '------';
				ksort($prefectures);
				
				$items = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0, 'Item.parent_id <>' => 0)));
				$items[0] = '----';
				ksort($items);
				
				// 選択したテーマをメール用に整形
				$event_themes_buff = "";
				foreach ( $data['Add1']['EventTheme'] as $theme_id => $event_theme )
				{
					if ( $event_theme["id"] == 1)
					{
						$event_themes_buff .= "\r\n・" . $themes[$theme_id];
					}
				}
				
				$expenses_buff = "";
				$total_price = 0;
				if ( !empty($data['Add3']['Expense']) )
				{
					foreach ( $data['Add3']['Expense'] as $type => $expenses )
					{
						$subtotal = 0;	
						if ( $type == 1 )
						{
							$expenses_buff .= "旅費" . "\r\n";
							foreach ( $expenses as $expense )
							{
								$expenses_buff .= "・";
								foreach ( $expense as $k => $val )
								{
									if ( $k == 'item_id' && !empty($val) )
									{
										$expenses_buff .= $items[$val] . " ";
									}
									if ( $k == 'affiliation' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'job' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'lastname' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'firstname' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'title' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'date_start' )
									{
										$expenses_buff .= $val . "～";
									}
									else if ( $k == 'date_end' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'count' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'price' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'request_price' )
									{
										if ( empty($val) )
										{
											$val = 0;
										}
										
										$total_price = $total_price + $val;
										$subtotal = $subtotal + $val;
										
										$expenses_buff .= number_format($val) . "円 ";
									}
									else if ( $k == 'note' )
									{
										$expenses_buff .= $val . " ";
									}
									else
									{
										continue;
									}
									
								}
								$expenses_buff .= "\r\n";
							}
							$expenses_buff .= "小計：" . number_format($subtotal) . "円" . "\r\n";
							$expenses_buff .= "\r\n";
						}
						else if ( $type == 2 )
						{
							$expenses_buff .= "諸謝金" . "\r\n";
							foreach ( $expenses as $expense )
							{
								$expenses_buff .= "・";
								foreach ( $expense as $k => $val )
								{
									if ( $k == 'item_id' && !empty($val) )
									{
										$expenses_buff .= $items[$val] . " ";
									}
									if ( $k == 'affiliation' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'job' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'lastname' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'firstname' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'title' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'count' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'price' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'request_price' )
									{
										if ( empty($val) )
										{
											$val = 0;
										}
										
										$total_price = $total_price + $val;
										$subtotal = $subtotal + $val;
										
										$expenses_buff .= number_format($val) . "円 ";
									}
									else if ( $k == 'note' )
									{
										$expenses_buff .= $val . " ";
									}
									else
									{
										continue;
									}
									
								}
								$expenses_buff .= "\r\n";
							}
							$expenses_buff .= "小計：" . number_format($subtotal) . "円" . "\r\n";
							$expenses_buff .= "\r\n";
						}
						else if ( $type == 3 )
						{
							$expenses_buff .= "印刷製本費" . "\r\n";
							foreach ( $expenses as $expense )
							{
								$expenses_buff .= "・";
								foreach ( $expense as $k => $val )
								{
									if ( $k == 'item_id' && !empty($val) )
									{
										$expenses_buff .= $items[$val] . " ";
									}
									if ( $k == 'affiliation' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'job' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'lastname' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'firstname' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'title' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'count' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'price' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'request_price' )
									{
										if ( empty($val) )
										{
											$val = 0;
										}
										
										$total_price = $total_price + $val;
										$subtotal = $subtotal + $val;
										
										$expenses_buff .= number_format($val) . "円 ";
									}
									else if ( $k == 'note' )
									{
										$expenses_buff .= $val . " ";
									}
									else
									{
										continue;
									}
									
								}
								$expenses_buff .= "\r\n";
							}
							$expenses_buff .= "小計：" . number_format($subtotal) . "円" . "\r\n";
							$expenses_buff .= "\r\n";
						}
						else if ( $type == 4 )
						{
							$expenses_buff .= "その他" . "\r\n";
							foreach ( $expenses as $expense )
							{
								$expenses_buff .= "・";
								foreach ( $expense as $k => $val )
								{
									if ( $k == 'item_id' && !empty($val) )
									{
										$expenses_buff .= $items[$val] . " ";
									}
									if ( $k == 'affiliation' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'job' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'lastname' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'firstname' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'title' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'count' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'price' )
									{
										$expenses_buff .= $val . " ";
									}
									else if ( $k == 'request_price' )
									{
										if ( empty($val) )
										{
											$val = 0;
										}
										
										$total_price = $total_price + $val;
										$subtotal = $subtotal + $val;
										
										$expenses_buff .= number_format($val) . "円 ";
									}
									else if ( $k == 'note' )
									{
										$expenses_buff .= $val . " ";
									}
									else
									{
										continue;
									}
									
								}
								$expenses_buff .= "\r\n";
							}
							$expenses_buff .= "小計：" . number_format($subtotal) . "円" . "\r\n";
							$expenses_buff .= "\r\n";
						}
					}
				}
				$total_price = number_format($total_price);
				
				$event_manager_text = "";
				if ( !empty($event_manager) )
				{
					foreach ( $event_manager['EventManager'] as $manager )
					{
						$event_manager_text .= '■運営責任者' . "\r\n";
						$event_manager_text .= '参加者ID（メールアドレス）:' . $manager['email'] . "\r\n";
						$event_manager_text .= '姓名:' . $manager['lastname'] . ' ' . $manager['firstname'] . "\r\n";
						$event_manager_text .= 'フリガナ:' . $manager['lastname_kana'] . ' ' . $manager['firstname_kana'] . "\r\n";
						$event_manager_text .= '所属機関:' . $manager['organization'] . "\r\n";
						$event_manager_text .= '所属部局:' . $manager['department'] . "\r\n";
						$event_manager_text .= '職名:' . $manager['job_title'] . "\r\n";
						$event_manager_text .= '郵便番号及びZIP CODE:' . $manager['zip'] . "\r\n";
						$event_manager_text .= '住所:' . $prefectures[$manager['prefecture_id']] . $manager['city'].$manager['address'] . "\r\n";
						$event_manager_text .= 'TEL:' . $manager['tel'] . "\r\n";
						$event_manager_text .= 'FAX:' . $manager['fax'] . "\r\n";
						$event_manager_text .= 'URL:' . $manager['url'] . "\r\n";
					}
				}
				
				if ( !empty($event_affair) )
				{
					foreach ( $event_affair['EventAffair'] as $manager )
					{
						$event_manager_text .= '■事務担当者' . "\r\n";
						$event_manager_text .= '参加者ID（メールアドレス）:' . $manager['email'] . "\r\n";
						$event_manager_text .= '姓名:' . $manager['lastname'] . ' ' . $manager['firstname'] . "\r\n";
						$event_manager_text .= 'フリガナ:' . $manager['lastname_kana'] . ' ' . $manager['firstname_kana'] . "\r\n";
						$event_manager_text .= '所属機関:' . $manager['organization'] . "\r\n";
						$event_manager_text .= '所属部局:' . $manager['department'] . "\r\n";
						$event_manager_text .= '職名:' . $manager['job_title'] . "\r\n";
						$event_manager_text .= '郵便番号及びZIP CODE:' . $manager['zip'] . "\r\n";
						$event_manager_text .= '住所:' . $prefectures[$manager['prefecture_id']] . $manager['city'].$manager['address'] . "\r\n";
						$event_manager_text .= 'TEL:' . $manager['tel'] . "\r\n";
						$event_manager_text .= 'FAX:' . $manager['fax'] . "\r\n";
						$event_manager_text .= 'URL:' . $manager['url'] . "\r\n";
					}
				}
				
				
				$mail_data = array();
				$name					= $data['Add5']['EventManager'][0]['lastname'] . ' ' . $data['Add5']['EventManager'][0]['firstname']; //企画運営責任者名
				$event_number			= $event_number;													//企画番号
				
				$username				= $data['Add1']['Event']['username'];								//ログインID
				$password				= 'セキュリティの為、表示しておりません。';							//パスワード
				$title					= $data['Add1']['Event']['title'];									//イベント名称
				$event_themes			= $event_themes_buff;												//該当する重点テーマ
				$field					= $data['Add1']['Event']['field'];									//連携分野
				
				$keywords = array();
				foreach ( $data['Add1']['EventKeyword'] as $event_keyword )
				{
					if ( !empty($event_keyword['title']) )
					{
						$keywords[] = $event_keyword['title'];
					}
				}
				$keyword				= implode('、', $keywords);
				
				$organization			= $data['Add1']['Event']['organization'];							//主催機関
				$start					= date('Y年m月d月', strtotime($data['Add1']['Event']['start']));	//開催時期（開始）
				$end					= date('Y年m月d月', strtotime($data['Add1']['Event']['end']));		//開催時期（終了）
				
				$program				= $data['Add2']['Event']['program'];				//プログラム
				$purpose				= $data['Add2']['Event']['purpose'];				//趣旨・目的
				$subject				= $data['Add2']['Event']['subject'];				//解決すべき課題
				$approach				= $data['Add2']['Event']['approach'];				//考えられる数学・数理科学的アプローチ
				$follow					= $data['Add2']['Event']['follow'];					//会議終了後に考えられるフォローアップ
				$prepare				= $data['Add2']['Event']['prepare'];				//これまでの準備
				
				$support_option = array('0' => '無', '1' => '有');
				
				$is_support				= $support_option[$data['Add2']['Event']['is_support']];				//他からの支援
				$support				= $data['Add2']['Event']['support'];				//他からの支援
				
				$expenses_text			= $expenses_buff;
				
				$qualification_option1 = array('0' => '無', '1' => '有');
				$qualification_option2 = array('0' => '不要', '1' => '要');
				
				$qualification			= '無';						//参加資格
				if ( isset($data['Add4']['Event']['qualification']) )
				{
					$qualification			= $qualification_option1[$data['Add4']['Event']['qualification']];						//参加資格
				}
				$qualification_other	= '';				//アリの場合は参加資格
				if ( isset($data['Add4']['Event']['qualification_other']) )
				{
					$qualification_other	= $data['Add4']['Event']['qualification_other'];				//アリの場合は参加資格
				}
				
				$qualification_apply = '不要';
				if ( isset($data['Add4']['Event']['qualification_apply']) )
				{
					$qualification_apply	= $qualification_option2[$data['Add4']['Event']['qualification_apply']];		//参加申込みの要不要
				}
				
				//$is_qualification_cost	= $options2[$data['Add4']['Event']['is_qualification_cost']];	//参加費の有無
				//$qualification_cost		= $data['Add4']['Event']['qualification_cost'];					//参加費の詳細
				
				$login_url				= Configure::read('App.site_url') . 'databases/login';	// ログインURL
				
				$mail_data['body'] = "";
				
				$name = $data['Add5']['EventManager'][0]['lastname'].$data['Add5']['EventManager'][0]['firstname'];
				
$mail_data['body'] .= <<< EOM
{$name} 様

この度は企画をご応募頂き誠にありがとうございます。

下記の内容で応募を承りました。
企画の内容を編集する場合は、
下記のURLよりログインし、企画を編集して下さい。

ログインURL：
{$login_url}

ログインID：{$username}
パスワード：{$password}

【企画の概要】
企画番号：{$event_number}
名称：{$title}
集会等のタイプ:{$event_themes}
連携相手の分野・業界：{$field}
キーワード：{$keyword}
主催機関：{$organization}
開催時期：{$start}～{$end}

【企画の詳細】
プログラム（未定の場合その旨を明記）：
{$program}
趣旨・目的：
{$purpose}
取り扱うテーマ・トピックや解決すべき課題：
{$subject}
考えられる数学・数理科学的アプローチ：
{$approach}
これまでの準備：
{$prepare}
終了後のフォローアップの計画：
{$follow}
他機関からの支援：
{$support}

【経費】
{$expenses_text}
合計金額：{$total_price}円

【参加について】
参加資格：{$qualification}
有の場合は参加資格：{$qualification_other}
参加費の詳細：{$qualification_apply}

【責任者】
{$event_manager_text}

EOM;

				
				if ( !$this->develop )
				{
					if ( isset($data['Add5']['EventManager']) && !empty($data['Add5']['EventManager']) )
					{
						foreach ( $data['Add5']['EventManager'] as $em )
						{
							$email = new CakeEmail();
							$email->config('default');
							$email->from(array('aimap@imi.kyushu-u.ac.jp' => '数理技術相談データベース'));
							$email->to(trim($em['email']));
							$email->template('notification');
							$email->subject('企画応募完了通知');
							$email->viewVars($mail_data);

							if ( !$email->send() )
							{
								$mail_error[] = $em['email'];
							}
							else
							{
								$mail_send[] = $em['email'];
							}
						}
					}
					
					if ( isset($data['Add5']['EventAffair']) && !empty($data['Add5']['EventAffair']) )
					{
						foreach ( $data['Add5']['EventAffair'] as $ea )
						{
							$email = new CakeEmail();
							$email->config('default');
							$email->from(array('aimap@imi.kyushu-u.ac.jp' => '数理技術相談データベース'));
							$email->to(trim($ea['email']));
							$email->template('notification');
							$email->subject('企画応募完了通知');
							$email->viewVars($mail_data);

							if ( !$email->send() )
							{
								$mail_error[] = $ea['email'];
							}
							else
							{
								$mail_send[] = $ea['email'];
							}
						}
					}
				}
			}
			
			$this->set('mail_send', $mail_send);
			$this->set('mail_error', $mail_error);
			
			//print_a($mail_data);
			
			if ( !$rollback )
			{
				// 成功時
				// ここでコミットする
				$this->Event->commit();
				
				// セッションから入力データを削除
				$this->Session->destroy('Event');
			}
			else
			{
				// 失敗時
				$this->Event->rollback();
				$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				$this->redirect(array('action' => 'add1'));
			}
		}
	}

	/**********************************************************
	 * 企画編集
	 */
	// 1ページ目 企画の概要
	public function edit1() {
		
		// バリデーションの設定を避難
		$event_validate			= $this->Event->validate;
		$event_keyword_validate	= $this->EventKeyword->validate;
		$event_theme_validate	= $this->EventTheme->validate;
		
		// 途中保存時はバリデーションを外す
		$this->Event->validate = array();
		$this->EventKeyword->validate = array();
		$this->EventTheme->validate = array();
		
		$this->Session->write('page', 1);
		
		if( $this->request->is('post') )
		{
			// 保存する
			if ( isset($this->request->data['update']) )
			{
				$rollback = false;
				$this->Event->begin();
				$last_id = $this->Auth->user('id');
				
				$this->request->data['Event']['id'] = $this->Auth->user('id');
				
				if ( !$this->Event->save($this->request->data) )
				{
					$rollback = true;
				}
				
				// EventThemeテーブル挿入用
				$event_theme['EventTheme'] = $this->request->data['EventTheme'];
				if ( !$rollback )
				{
					// updateではなく全削除、全挿入する
					// 既存データから該当するデータを削除
					if( !$this->EventTheme->deleteAll(array('EventTheme.event_id' => $last_id), false) )
					{
						$rollback = true;
					}
					
					if( !$rollback )
					{
						foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
						{
							if( $theme['id'] == 1)
							{
								$save = array();
								$save['EventTheme']['event_id'] = $last_id;
								$save['EventTheme']['theme_id'] = $theme_id;
								$this->EventTheme->create();
								if ( !$this->EventTheme->save($save) )
								{
									$rollback = true;
									break;
								}
							}
						}
					}
				}
				
				$event_keyword['EventKeyword'] = $this->request->data['EventKeyword'];
				if ( !$rollback )
				{
					foreach ( $event_keyword['EventKeyword'] as  $keyword )
					{
						if( !empty($keyword['title']) )
						{
							if ( !empty($keyword['id']) )
							{
								// 更新
								$save = array();
								$save['EventKeyword']['id'] = $keyword['id'];
								$save['EventKeyword']['title'] = $keyword['title'];
								$this->EventKeyword->set($save);
								if ( !$this->EventKeyword->save($save) )
								{
									$rollback = true;
									break;
								}
							}
							else
							{
								// 新規
								$save = array();
								$save['EventKeyword']['event_id'] = $this->request->data['Event']['id'];
								$save['EventKeyword']['title'] = $keyword['title'];
								$this->EventKeyword->create();
								if ( !$this->EventKeyword->save($save) )
								{
									$rollback = true;
									break;
								}
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
					$this->Session->setFlash('一時保存しました。', 'Flash/success');
				}
				else
				{
					$this->Event->rollback();
				}
			}
			// 次へ
			else
			{
				// バリデーションを戻す
				$this->Event->validate			= $event_validate;
				$this->EventKeyword->validate	= $event_keyword_validate;
				$this->EventTheme->validate		= $event_theme_validate;
				
				$rollback = false;
				$this->Event->begin();
				$last_id = $this->Auth->user('id');
				
				$this->request->data['Event']['id'] = $this->Auth->user('id');
				
				if ( !$this->Event->save($this->request->data) )
				{
					$rollback = true;
				}
				
				
				// EventThemeテーブル挿入用
				$checked_count = 0;
				$event_theme['EventTheme'] = $this->request->data['EventTheme'];
				foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
				{
					if( $theme['id'] == 1)
					{
						$checked_count++;
					}
				}
				if ( $checked_count == 0 )
				{
					$rollback = true;
					$this->EventTheme->validationErrors['id'][] = '該当する集会等のタイプをお選び下さい。';
				}
				
				if ( !$rollback )
				{
					// updateではなく全削除、全挿入する
					// 既存データから該当するデータを削除
					if( !$this->EventTheme->deleteAll(array('EventTheme.event_id' => $last_id), false) )
					{
						$rollback = true;
					}
					
					if( !$rollback )
					{
						foreach ( $event_theme['EventTheme'] as $theme_id => $theme )
						{
							if( $theme['id'] == 1)
							{
								$save = array();
								$save['EventTheme']['event_id'] = $last_id;
								$save['EventTheme']['theme_id'] = $theme_id;
								$this->EventTheme->create();
								if ( !$this->EventTheme->save($save) )
								{
									$rollback = true;
									break;
								}
							}
						}
					}
				}
				
				
				// キーワード
				$input_count = 0;
				$event_keyword['EventKeyword'] = $this->request->data['EventKeyword'];
				foreach ( $event_keyword['EventKeyword'] as  $ek )
				{
					if( !empty($ek['title']) )
					{
						$input_count++;
					}
				}
				
				if ( $input_count == 0 )
				{
					$rollback = true;
					$this->EventKeyword->validationErrors[0]['title'][] = 'キーワードを入力して下さい。';
				}
				
				if ( !$rollback )
				{
					foreach ( $event_keyword['EventKeyword'] as  $keyword )
					{
						if( !empty($keyword['title']) )
						{
							if ( !empty($keyword['id']) )
							{
								// 更新
								$save = array();
								$save['EventKeyword']['id'] = $keyword['id'];
								$save['EventKeyword']['title'] = $keyword['title'];
								$this->EventKeyword->set($save);
								if ( !$this->EventKeyword->save($save) )
								{
									$rollback = true;
									break;
								}
							}
							else
							{
								// 新規
								$save = array();
								$save['EventKeyword']['event_id'] = $this->request->data['Event']['id'];
								$save['EventKeyword']['title'] = $keyword['title'];
								$this->EventKeyword->create();
								if ( !$this->EventKeyword->save($save) )
								{
									$rollback = true;
									break;
								}
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
					$this->redirect(array('action' => 'edit2'));
				}
				else
				{
					$this->Event->rollback();
				}
			}
		}
		else
		{
			$event = $this->Event->find('first', array(
				'contain' => array(
					'EventTheme',
					'EventKeyword'
				),
				'conditions' => array(
					'Event.id' => $this->Auth->user('id'),
				),
			));
			
			$this->request->data = array();
			$this->request->data['Event']['id']			= $event['Event']['id'];
			$this->request->data['Event']['username']		= $event['Event']['username'];
			$this->request->data['Event']['password'] 		= '';
			$this->request->data['Event']['title']			= $event['Event']['title'];
			$this->request->data['Event']['field']			= $event['Event']['field'];
			$this->request->data['Event']['organization']	= $event['Event']['organization'];
			$this->request->data['Event']['start']			= $event['Event']['start'];
			$this->request->data['Event']['end']			= $event['Event']['end'];
			$this->request->data['Event']['place']			= $event['Event']['place'];
			
			foreach ( $event['EventTheme'] as $theme )
			{
				$this->request->data['EventTheme'][$theme['theme_id']]['id'] = 1;
			}
			$this->request->data['EventKeyword']			= $event['EventKeyword'];
			
		}
		
		$ev = $this->Event->read(null, $this->Auth->user('id'));
		
		$this->set('event', $ev);
		
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
	}

	// 2ページ目 企画の詳細
	public function edit2() {
		
		$this->Session->write('page', 2);
		
		if( $this->request->is('post') )
		{
			if ( isset($this->request->data['update']) )
			{
				
				$rollback = false;
				$this->Event->begin();
				$last_id = $this->Auth->user('id');
				
				$this->request->data['Event']['id'] = $this->Auth->user('id');
				
				if ( !$this->Event->save($this->request->data) )
				{
					$rollback = true;
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
					$this->Session->setFlash('一時保存しました。', 'Flash/success');
				}
				else
				{
					$this->Event->rollback();
				}
				
			}
			else
			{
				$rollback = false;
				$this->Event->begin();
				$last_id = $this->Auth->user('id');
				
				$this->request->data['Event']['id'] = $this->Auth->user('id');
				
				if ( !$this->Event->save($this->request->data) )
				{
					$rollback = true;
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
					$this->redirect(array('action' => 'edit3'));
				}
				else
				{
					$this->Event->rollback();
				}
			}
		}
		else
		{
			$event = $this->Event->find('first', array(
				'contain' => array(
				),
				'conditions' => array(
					'Event.id' => $this->Auth->user('id'),
				),
			));
			
			$this->request->data['Event']['program']		= $event['Event']['program'];
			$this->request->data['Event']['purpose']		= $event['Event']['purpose'];
			$this->request->data['Event']['subject']		= $event['Event']['subject'];
			$this->request->data['Event']['approach']		= $event['Event']['approach'];
			$this->request->data['Event']['follow']		= $event['Event']['follow'];
			$this->request->data['Event']['is_support']	= $event['Event']['is_support'];
			$this->request->data['Event']['support']		= $event['Event']['support'];
			$this->request->data['Event']['prepare']		= $event['Event']['prepare'];
			
		}
		$options1 = array(
			'1' => '有',
			'0' => '無',
		);
		$this->set('options1', $options1);
	}

	// 3ページ目 経費
	public function edit3() {
		
		$this->Session->write('page', 3);
		
		// バリデーションルールを非難
		$expense_validate	= $this->Expense->validate;
		
		// 途中保存時はバリデーションを外す
		$this->Expense->validate = array();
		
		
		// 課目ドロップダウン用データ
		$items = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0, 'Item.parent_id <>' => 0)));
		$items[0] = '----';
		ksort($items);
		$this->set('items', $items);
		
		if( $this->request->is('post') )
		{
			
			if ( isset($this->request->data['update']) )
			{
				
				$rollback = false;
				$this->Event->begin();
				$last_id = $this->Auth->user('id');
				
				$this->request->data['Event']['id'] = $this->Auth->user('id');
				
				if ( isset($this->request->data['Expense']) && !empty($this->request->data['Expense']) )
				{
					// Expenseテーブル挿入用
					$expense_data['Expense'] = $this->request->data['Expense'];
					
					$this->Event->hasMany['Expense']['conditions'] = array('Expense.is_delete' => 0);
					$_event = $this->Event->find('first', array(
						'contain' => array(
							'Expense'
						),
						'conditions' => array(
							'Event.id' => $last_id,
						),
					));
					
					// DB上のデータ
					$event_expense_ids = array();
					foreach ( $_event['Expense'] as $_expense )
					{
						$event_expense_ids[] = $_expense['id'];
					}
					
					//postされたデータ
					$post_expense_ids = array();
					if ( !empty($expense_data['Expense']) )
					{
						foreach ( $expense_data['Expense'] as $_expenses )
						{
							foreach ( $_expenses as $_expense )
							{
								$post_expense_ids[] = $_expense['id'];
							}
						}
					}
					
					// 削除されたデータのID、あとで削除フラグを立てる
					$diffs = array_diff($event_expense_ids, $post_expense_ids);
					
					$updates = array();
					$inserts = array();
					if ( !empty($expense_data['Expense']) )
					{
						foreach ( $expense_data['Expense'] as $type => $expenses )
						{
							foreach ( $expenses as $expense )
							{
								if ( $type != 4 )
								{
									$expense['item_id'] = $type;
								}
								if ( $expense['id'] > 0 )
								{
									// 更新
									$expense['event_id']	= $this->request->data['Event']['id'];
									$expense['type']		= $type;
									$updates[] = $expense;
								}
								else
								{
									// 新規作成
									unset($expense['id']);
									$expense_buff = $expense;
									if ( isset($expense_buff['item_id']) )
									{
										unset($expense_buff['item_id']);
									}
									if ( $this->_is_input($expense_buff) )
									{
										$expense['event_id']	= $this->request->data['Event']['id'];
										$expense['type']		= $type;
										$inserts[] = $expense;
									}
								}
							}
						}
						
						if ( !empty($updates) )
						{
							foreach ( $updates as $update )
							{
								$this->Expense->set($update);
								if ( !$this->Expense->save($update) )
								{
									$rollback = true;
									break;
								}
							}
						}
						
						if ( !empty($inserts) )
						{
							foreach ( $inserts as $insert )
							{
								$this->Expense->create();
								if ( !$this->Expense->save($insert) )
								{
									$rollback = true;
									break;
								}
							}
						}
					}
					
					if ( !empty($diffs) )
					{
						// 差分を削除
						foreach ( $diffs as $diff )
						{
							if ( !$this->Expense->delete($diff) )
							{
								$rollback = true;
								break;
							}
							
						}
					}
					
					if ( !$rollback )
					{
						$this->Event->commit();
						$this->Session->setFlash('一時保存しました。', 'Flash/success');
						$this->redirect(array('action' => 'edit3'));
					}
					else
					{
						$this->Event->rollback();
					}
				}
			}
			else
			{
				// 
				$this->Expense->validate = $expense_validate;
				
				if ( !isset($this->request->data['Expense']) )
				{
					$this->request->data['Expense'] = array();
				}
				
				$errors = array();
				foreach ( $this->request->data['Expense'] as $key1 => $expenses )
				{
					
					foreach ( $expenses as $key2 => $expense )
					{
						$this->Expense->set($expense);
						if ( !$this->Expense->validates() )
						{
							$errors[$key1][$key2] = $this->Expense->validationErrors;
						}
					}
				}
				
				$this->Expense->validationErrors = $errors;
				
				if ( empty($errors) )
				{
					
					$rollback = false;
					$this->Event->begin();
					$last_id = $this->Auth->user('id');
					
					$this->request->data['Event']['id'] = $this->Auth->user('id');
					
					if ( isset($this->request->data['Expense']) && !empty($this->request->data['Expense']) )
					{
						// Expenseテーブル挿入用
						$expense_data['Expense'] = $this->request->data['Expense'];
						
						$this->Event->hasMany['Expense']['conditions'] = array('Expense.is_delete' => 0);
						$_event = $this->Event->find('first', array(
							'contain' => array(
								'Expense'
							),
							'conditions' => array(
								'Event.id' => $last_id,
							),
						));
						
						
						// DB上のデータ
						$event_expense_ids = array();
						foreach ( $_event['Expense'] as $_expense )
						{
							$event_expense_ids[] = $_expense['id'];
						}
						
						//postされたデータ
						$post_expense_ids = array();
						if ( !empty($expense_data['Expense']) )
						{
							foreach ( $expense_data['Expense'] as $_expenses )
							{
								foreach ( $_expenses as $_expense )
								{
									$post_expense_ids[] = $_expense['id'];
								}
							}
						}
						
						// 削除されたデータのID、あとで削除フラグを立てる
						$diffs = array_diff($event_expense_ids, $post_expense_ids);
						
						$updates = array();
						$inserts = array();
						if ( !empty($expense_data['Expense']) )
						{
							foreach ( $expense_data['Expense'] as $type => $expenses )
							{
								foreach ( $expenses as $expense )
								{
									if ( $type != 4 )
									{
										$expense['item_id'] = $type;
									}
									if ( $expense['id'] > 0 )
									{
										// 更新
										$expense['event_id']	= $this->request->data['Event']['id'];
										$expense['type']		= $type;
										$updates[] = $expense;
									}
									else
									{
										// 新規作成
										unset($expense['id']);
										if ( $this->_is_input($expense) )
										{
											$expense['event_id']	= $this->request->data['Event']['id'];
											$expense['type']		= $type;
											$inserts[] = $expense;
										}
									}
								}
							}
							
							//print_a_die($inserts);
							
							if ( !empty($updates) )
							{
								foreach ( $updates as $update )
								{
									if ( empty($update['request_price']) )
									{
										$update['request_price'] = 0;
									}
									$this->Expense->set($update);
									if ( !$this->Expense->save($update) )
									{
										$rollback = true;
										break;
									}
								}
							}
							
							if ( !empty($inserts) )
							{
								foreach ( $inserts as $insert )
								{
									if ( empty($insert['request_price']) )
									{
										$insert['request_price'] = 0;
									}
									$this->Expense->create();
									if ( !$this->Expense->save($insert) )
									{
										$rollback = true;
										break;
									}
								}
							}
						}
						
						if ( !empty($diffs) )
						{
							// 差分を削除
							foreach ( $diffs as $diff )
							{
								if ( !$this->Expense->delete($diff) )
								{
									$rollback = true;
									break;
								}
								
							}
						}
						
						if ( !$rollback )
						{
							$this->Event->commit();
							$this->redirect(array('action' => 'edit4'));
						}
						else
						{
							$this->Event->rollback();
						}
					}
					else
					{
						//$this->redirect(array('action' => 'edit4'));
					}
				}
			}
		}
		else
		{
			if (isset($data['update']))
			{
				unset($data['update']);
			}
			
			$this->Event->hasMany['Expense']['conditions'] = array('Expense.is_delete' => 0);
			$event = $this->Event->find('first', array(
				'contain' => array(
					'Expense',
				),
				'conditions' => array(
					'Event.id' => $this->Auth->user('id'),
				),
			));
			
			$this->request->data = array();
			if ( !empty($event) )
			{
				foreach ( $event['Expense'] as $expense )
				{
					$this->request->data['Expense'][$expense['type']][] = $expense;
				}
			}
			
			if ( empty($this->request->data['Expense']) )
			{
				$this->request->data['Expense'] = array(
					'1' => array(
						'1' => array(
							'affiliation' => '',
							'job' => '',
							'lastname' => '',
							'firstname' => '',
							'title' => '',
							'request_price' => '',
							'note' => '',
						)
					),
					'2' => array(
						'1' => array(
							'affiliation' => '',
							'job' => '',
							'lastname' => '',
							'firstname' => '',
							'title' => '',
							'request_price' => '',
							'note' => '',
						)
					),
					'3' => array(
						'1' => array(
							'title' => '',
							'count' => '',
							'price' => '',
							'request_price' => '',
							'note' => '',
						)
					),
					'4' => array(
						'1' => array(
							'item_id' => '',
							'title' => '',
							'count' => '',
							'price' => '',
							'request_price' => '',
							'note' => '',
						)
					)
				);
			}
			
		}
		
	}

	// 4ページ目 参加について
	public function edit4() {
		
		$this->Session->write('page', 4);
		
		if( $this->request->is('post') )
		{
			if ( isset($this->request->data['update']) )
			{
				
				$rollback = false;
				$this->Event->begin();
				$last_id = $this->Auth->user('id');
				
				$this->request->data['Event']['id'] = $this->Auth->user('id');
				
				if ( !$this->Event->save($this->request->data) )
				{
					$rollback = true;
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
					$this->Session->setFlash('一時保存しました。', 'Flash/success');
				}
				else
				{
					$this->Event->rollback();
				}
			}
			else
			{
				$rollback = false;
				$this->Event->begin();
				$last_id = $this->Auth->user('id');
				
				$this->request->data['Event']['id'] = $this->Auth->user('id');
				
				if ( !$this->Event->save($this->request->data) )
				{
					$rollback = true;
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
					$this->redirect(array('action' => 'edit5'));
				}
				else
				{
					$this->Event->rollback();
				}
			}
		}
		else
		{
			$event = $this->Event->find('first', array(
				'contain' => array(
				),
				'conditions' => array(
					'Event.id' => $this->Auth->user('id'),
				),
			));
			
			$this->request->data = array();
			$this->request->data['Event']['qualification']				= $event['Event']['qualification'];
			$this->request->data['Event']['qualification_other']		= $event['Event']['qualification_other'];
			$this->request->data['Event']['qualification_apply']		= $event['Event']['qualification_apply'];
			
		}
		
		//$this->set('options1', array('1' => '必要', '0' => '不要'));
		//$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		
		
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		$this->set('options3', array('1' => '有', '0' => '無'));
	}

	// 5ページ目 責任者
	public function edit5() {
		
		// バリデーションの設定を避難
		$event_manager_validate	= $this->EventManager->validate;
		$event_affair_validate	= $this->EventAffair->validate;
		
		// 途中保存時はバリデーションを外す
		$this->EventManager->validate = array();
		$this->EventAffair->validate = array();
		
		$this->Event->hasMany['EventManager']['conditions'] = array('EventManager.is_delete' => 0);
		$this->Event->hasMany['EventAffair']['conditions'] = array('EventAffair.is_delete' => 0);
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventManager',
				'EventAffair',
			),
			'conditions' => array(
				'Event.id' => $this->Auth->user('id'),
			),
		));
		
		if ( empty($event['EventManager']) || empty($event['EventAffair']))
		{
			if ( empty($event['EventManager']) )
			{
				$save = array();
				$save['EventManager']['event_id'] = $this->Auth->user('id');
				if ( $this->EventManager->save($save) )
				{
					
				}
			}
			
			if ( empty($event['EventAffair']) )
			{
				$save = array();
				$save['EventAffair']['event_id'] = $this->Auth->user('id');
				if ( $this->EventAffair->save($save) )
				{
					
				}
			}
			$this->redirect(array('action' => 'edit5'));
		}
		
		if( $this->request->is('post') )
		{
			if ( isset($this->request->data['update']) )
			{
				$rollback = false;
				$this->Event->begin();
				
				$last_id = $this->Auth->user('id');
				
				$this->request->data['Event']['id'] = $this->Auth->user('id');
				
				// EventManagerテーブルに挿入
				// 運営責任者
				if ( !$rollback )
				{
					if ( !empty($this->request->data['EventManager']) )
					{
						$this->EventManager->validate = array();
						foreach ( $this->request->data['EventManager'] as $manager )
						{
							$save = array();
							$save['EventManager'] = $manager;
							$save['EventManager']['event_id'] = $last_id;
							
						//	print_a($save);
							
							$this->EventManager->set($save);
							if ( !$this->EventManager->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					// 事務担当者
					if ( !$rollback )
					{
						if ( !empty($this->request->data['EventAffair']) )
						{
							$this->EventAffair->validate = array();
							foreach ( $this->request->data['EventAffair'] as $affair )
							{
								$save = array();
								$save['EventAffair'] = $affair;
								$save['EventAffair']['event_id'] = $last_id;
								
								
							//	print_a($save);
								
								$this->EventAffair->set($save);
								if ( !$this->EventAffair->save($save) )
								{
									$rollback = true;
									break;
								}
							}
						}
					}
				}
				
			//	print_a($rollback);
			//	$this->Event->rollback();
			//	die();
				
				if ( !$rollback )
				{
					$this->Event->commit();
					$this->Session->setFlash('一時保存しました。', 'Flash/success');
					$this->redirect(array('action' => 'edit5'));
				}
				else
				{
					$this->Session->setFlash('一時保存に失敗しました。', 'Flash/error');
					$this->Event->rollback();
				}
			}
			// 運営責任者を増やすボタン
			else if ( isset($this->request->data['manager']) )
			{
				
				$rollback = false;
				$this->Event->begin();
				
				// 既存データを保存しておく
				foreach ( $this->request->data['EventManager'] as $em )
				{
					if ( isset($em['id']) && !empty($em['id']) )
					{
						$save = array();
						$save = $em;
						$this->EventManager->set($save);
						if ( !$this->EventManager->save($save) )
						{
							$rollback = true;
							break;
						}
					}
				}
				
				if ( !$rollback )
				{
					foreach ( $this->request->data['EventAffair'] as $ea )
					{
						if ( isset($ea['id']) && !empty($ea['id']) )
						{
							$save = array();
							$save = $ea;
							$this->EventAffair->set($save);
							if ( !$this->EventAffair->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$save = array();
					$save['EventManager']['event_id'] = $this->Auth->user('id');
					$this->EventManager->create();
					if ( !$this->EventManager->save($save) )
					{
						$rollback = true;
					}
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
				}
				else
				{
					$this->Event->rollback();
				}
				
				$this->redirect(array('action' => 'edit5'));
			}
			// 事務担当者を増やすボタン押下時
			else if ( isset($this->request->data['affair']) )
			{
				
				$rollback = false;
				$this->Event->begin();
				
				// 既存データを保存しておく
				foreach ( $this->request->data['EventManager'] as $em )
				{
					if ( isset($em['id']) && !empty($em['id']) )
					{
						$save = array();
						$save = $em;
						$this->EventManager->set($save);
						if ( !$this->EventManager->save($save) )
						{
							$rollback = true;
							break;
						}
					}
				}
				
				if ( !$rollback )
				{
					foreach ( $this->request->data['EventAffair'] as $ea )
					{
						if ( isset($ea['id']) && !empty($ea['id']) )
						{
							$save = array();
							$save = $ea;
							$this->EventAffair->set($save);
							if ( !$this->EventAffair->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
				}
				
				if ( !$rollback )
				{
					$save = array();
					$save['EventAffair']['event_id'] = $this->Auth->user('id');
					$this->EventAffair->create();
					if ( !$this->EventAffair->save($save) )
					{
						$rollback = true;
					}
				}
				
				if ( !$rollback )
				{
					$this->Event->commit();
				}
				else
				{
					$this->Event->rollback();
				}
				$this->redirect(array('action' => 'edit5'));
			}
			else
			{
				// バリデーション設定を元に戻す
				$this->EventManager->validate	= $event_manager_validate;
				$this->EventAffair->validate	= $event_affair_validate;
				
				$is_error = false;
				$is_checked = false;
				
				// 責任者の入チェック
				$managers_errors = array();
				foreach ( $this->request->data['EventManager'] as $key => $manager )
				{
					$this->EventManager->set( $manager );
					if ( !$this->EventManager->validates() )
					{
						$managers_errors[$key] = $this->EventManager->validationErrors;
					}
				}
				
				// 入力チェックの結果を変数に格納
				if ( !empty($managers_errors) )
				{
					$is_error = true;
					$this->EventManager->validationErrors = $managers_errors;
				}
				
				// 事務担当者の入チェック
				$affairs_errors = array();
				foreach ( $this->request->data['EventAffair'] as $key => $affairs )
				{
					$this->EventAffair->set( $affairs );
					if ( !$this->EventAffair->validates() )
					{
						$affairs_errors[$key] = $this->EventAffair->validationErrors;
					}
				}
				// 入力チェックの結果を変数に格納
				if ( !empty($affairs_errors) )
				{
					$is_error = true;
					$this->EventAffair->validationErrors = $affairs_errors;
				}
				
				// エラーがない場合
				if (!$is_error)
				{
					
					$rollback = false;
					$this->Event->begin();
					$last_id = $this->Auth->user('id');
					
					
					$this->request->data['Event']['id'] = $this->Auth->user('id');
					
					
					// EventManagerテーブルに挿入
					// 運営責任者
					if ( !empty($this->request->data['EventManager']) )
					{
						$this->EventManager->validate = array();
						foreach ( $this->request->data['EventManager'] as $manager )
						{
							$event_manager['EventManager'][] = $manager;
							
							$save = array();
							$save['EventManager'] = $manager;
							$save['EventManager']['event_id'] = $last_id;
							if ( isset($event_manager['is_delete']) && $event_manager['is_delete'] == 1 )
							{
								$save['EventManager']['is_delete'] = 1;
							}
							
							if ( empty($save['EventManager']['id']) )
							{
								$save['EventManager']['id'] = 0;
							}
							
							if ( $save['EventManager']['id'] > 0 )
							{
								$this->EventManager->set($save);
							}
							else
							{
								$this->EventManager->create();
							}
							if ( !$this->EventManager->save($save) )
							{
								$rollback = true;
								break;
							}
						}
					}
					
					// 事務担当者
					if ( !$rollback )
					{
						if ( !empty($this->request->data['EventAffair']) )
						{
							$this->EventAffair->validate = array();
							foreach ( $this->request->data['EventAffair'] as $manager )
							{
								$event_manager['EventAffair'][] = $manager;
								
								$save = array();
								$save['EventAffair'] = $manager;
								$save['EventAffair']['event_id'] = $last_id;
								if ( isset($event_manager['is_delete']) && $event_manager['is_delete'] == 1 )
								{
									$save['EventAffair']['is_delete'] = 1;
								}
								
								if ( empty($save['EventAffair']['id']) )
								{
									$save['EventAffair']['id'] = 0;
								}
								
								if ( $save['EventAffair']['id'] > 0 )
								{
									$this->EventAffair->set($save);
								}
								else
								{
									$this->EventAffair->create();
								}
								if ( !$this->EventAffair->save($save) )
								{
									$rollback = true;
									break;
								}
							}
						}
					}
					
					if ( !$rollback )
					{
						$this->Event->commit();
					}
					else
					{
						$this->Event->rollback();
					}
					
					// エラーがなければ次ページへ移動
					$this->redirect(array('action' => 'edit_confirm'));
				}
				
				$this->Session->setFlash('入力内容に不備があります。', 'Flash/error');
				
				// バリデーションの設定を避難
				$event_manager_validate	= $this->EventManager->validate;
				$event_affair_validate	= $this->EventAffair->validate;
				
				// 途中保存時はバリデーションを外す
				$this->EventManager->validate = array();
				$this->EventAffair->validate = array();
				
			}
		}
		else
		{
			$this->request->data['Event'] = $event['Event'];
			if ( isset($event['EventManager']) && !empty($event['EventManager']) )
			{
				foreach ( $event['EventManager'] as $em )
				{
					$this->request->data['EventManager'][$em['id']] = $em;
				}
			}
			if ( isset($event['EventAffair']) && !empty($event['EventAffair']) )
			{
				foreach ( $event['EventAffair'] as $ea )
				{
					$this->request->data['EventAffair'][$ea['id']] = $ea;
				}
			}
		}
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
		$this->set('event', $event);
	}

	public function manager_del( $id = null )
	{
		$data = $this->EventManager->find('first', array('contain' => array(), 'conditions' => array('EventManager.id' => $id, 'EventManager.event_id' => $this->Auth->user('id'))));
		if ( empty($data) )
		{
			$this->Session->setFlash('データが見つかりませんでした。', 'Flash/error');
			$this->redirect(array('action' => 'edit5'));
		}
		
		$save['EventManager']['id']			= $data['EventManager']['id'];
		$save['EventManager']['is_delete']	= 1;
		$this->EventManager->set($save);
		if ( $this->EventManager->save($save) )
		{
			
		}
		$this->redirect(array('action' => 'edit5'));
	}

	public function affair_del( $id = null )
	{
		$data = $this->EventAffair->find('first', array('contain' => array(), 'conditions' => array('EventAffair.id' => $id, 'EventAffair.event_id' => $this->Auth->user('id'))));
		if ( empty($data) )
		{
			$this->Session->setFlash('データが見つかりませんでした。', 'Flash/error');
			$this->redirect(array('action' => 'edit5'));
		}
		
		$save['EventAffair']['id']			= $data['EventAffair']['id'];
		$save['EventAffair']['is_delete']	= 1;
		$this->EventAffair->set($save);
		if ( $this->EventAffair->save($save) )
		{
			
		}
		$this->redirect(array('action' => 'edit5'));
	}


	// 6ページ目 入力内容確認画面
	public function edit_confirm() {
		
		if ( $this->request->is('post') )
		{
			$this->redirect(array('action' => 'edit_complete'));
		}
		else
		{
			$this->Event->hasMany['EventManager']['conditions'] = array('EventManager.is_delete' => 0);
			$this->Event->hasMany['EventAffair']['conditions'] = array('EventAffair.is_delete' => 0);
			$event = $this->Event->find('first', array(
				'contain' => array(
					'EventTheme',
					'EventKeyword',
					'EventManager',
					'EventAffair',
					'Expense'
				),
				'conditions' => array(
					'Event.id' => $this->Auth->user('id')
				)
			));
			
			$this->request->data = $event;
			
			$ex_arr = array();
			if ( !empty($event['Expense']) )
			{
				foreach ( $event['Expense'] as $ex )
				{
					$ex_arr[$ex['type']][] = $ex;
				}
				$this->request->data['Expense'] = $ex_arr;
			}
			
			
			
			//print_a_die($this->request->data);
		}
		
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
		
		
		
		$this->set('options1', array('1' => '必要', '0' => '不要'));
		$this->set('options2', array('1' => '参加費あり', '0' => '参加費なし'));
		$this->set('options3', array('1' => '有', '0' => '無'));
		
		
		// 都道府県ドロップダウン用データ
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
		
		// 課目ドロップダウン用データ
		$items = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0, 'Item.parent_id <>' => 0)));
		$items[0] = '----';
		ksort($items);
		$this->set('items', $items);
	}

	// 7ページ目 応募完了画面
	public function edit_complete() {
		
		$this->Event->hasMany['EventManager']['conditions'] = array('EventManager.is_delete' => 0);
		$this->Event->hasMany['EventAffair']['conditions'] = array('EventAffair.is_delete' => 0);
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme',
				'EventKeyword',
				'EventManager',
				'EventAffair',
				'Expense'
			),
			'conditions' => array(
				'Event.id' => $this->Auth->user('id')
			)
		));
		
		$last_id = $this->Auth->user('id');
		
		$save['Event']['id']			= $event['Event']['id'];
		$save['Event']['is_finished']	= 1;
		if ( $this->Event->save($save) )
		{
			
		}
		
		// EventManagerテーブルに挿入
		// 運営責任者
		if ( !empty($event['EventManager']) )
		{
			$this->EventManager->validate = array();
			foreach ( $event['EventManager'] as $manager )
			{
				$event_manager['EventManager'][] = $manager;
			}
		}
		
		// 事務担当者
		if ( !empty($event['EventAffair']) )
		{
			$this->EventAffair->validate = array();
			foreach ( $event['EventAffair'] as $manager )
			{
				$event_manager['EventAffair'][] = $manager;
				
			}
		}

		// 入力内容をメールで送る
		$mail_send = array();
		$mail_error = array();

		$themes = $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0)));
		$options1 = array('1' => '必要', '0' => '不要');
		$options2 = array('1' => '参加費あり', '0' => '参加費なし');
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		
		$items = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0)));
		$items[0] = '----';
		ksort($items);
		
		// 選択したテーマをメール用に整形
		$event_themes_buff = "";
		foreach ( $event['EventTheme'] as $event_theme )
		{
			$event_themes_buff .= "\r\n・" . $themes[$event_theme['theme_id']];
		}
		
		$expenses_buff = "";
		$total_price = 0;
		if ( !empty($event['Expense']) )
		{
			$ex_arr = array();
			foreach ( $event['Expense'] as $type => $expense )
			{
				$ex_arr[$expense['type']][] = $expense;
			}
			
			foreach ( $ex_arr as $type => $expense )
			{
				$subtotal = 0;
				if ( $type == 1 )
				{
					$expenses_buff .= "旅費" . "\r\n";
					
					foreach ( $expense as $k => $value )
					{
						$expenses_buff .= "・";
						foreach ( $value as $k => $val )
						{
							if ( $k == 'item_id' && !empty($val) )
							{
								$expenses_buff .= $items[$val] . " ";
							}
							if ( $k == 'affiliation' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'job' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'lastname' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'firstname' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'title' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'date_start' )
							{
								$expenses_buff .= $val . "～";
							}
							else if ( $k == 'date_end' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'count' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'price' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'request_price' )
							{
								if ( empty($val) )
								{
									$val = 0;
								}
								
								$total_price = $total_price + $val;
								$subtotal = $subtotal + $val;
								
								$expenses_buff .= number_format($val) . "円 ";
							}
							else if ( $k == 'note' )
							{
								$expenses_buff .= $val . " ";
							}
							else
							{
								continue;
							}
							
						}
						$expenses_buff .= "\r\n";
					}
					
					$expenses_buff .= "小計：" . number_format($subtotal) . "円" . "\r\n";
					$expenses_buff .= "\r\n";
				}
				else if ( $type == 2 )
				{
					$expenses_buff .= "諸謝金" . "\r\n";
					foreach ( $expense as $k => $value )
					{
						$expenses_buff .= "・";
					
						foreach ( $value as $k => $val )
						{
							if ( $k == 'item_id' && !empty($val) )
							{
								$expenses_buff .= $items[$val] . " ";
							}
							if ( $k == 'affiliation' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'job' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'lastname' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'firstname' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'title' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'count' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'price' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'request_price' )
							{
								if ( empty($val) )
								{
									$val = 0;
								}
								
								$total_price = $total_price + $val;
								$subtotal = $subtotal + $val;
								
								$expenses_buff .= number_format($val) . "円 ";
							}
							else if ( $k == 'note' )
							{
								$expenses_buff .= $val . " ";
							}
							else
							{
								continue;
							}
							
						}
						$expenses_buff .= "\r\n";
					}
					$expenses_buff .= "小計：" . number_format($subtotal) . "円" . "\r\n";
					$expenses_buff .= "\r\n";
				}
				else if ( $type == 3 )
				{
					$expenses_buff .= "印刷製本費" . "\r\n";
					foreach ( $expense as $k => $value )
					{
						$expenses_buff .= "・";
					
						foreach ( $value as $k => $val )
						{
							if ( $k == 'item_id' && !empty($val) )
							{
								//$expenses_buff .= $items[$val] . " ";
							}
							if ( $k == 'affiliation' )
							{
								//$expenses_buff .= $val . " ";
							}
							else if ( $k == 'job' )
							{
								//$expenses_buff .= $val . " ";
							}
							else if ( $k == 'lastname' )
							{
								//$expenses_buff .= $val . " ";
							}
							else if ( $k == 'firstname' )
							{
								//$expenses_buff .= $val . " ";
							}
							else if ( $k == 'title' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'count' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'price' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'request_price' )
							{
								if ( empty($val) )
								{
									$val = 0;
								}
								
								$total_price = $total_price + $val;
								$subtotal = $subtotal + $val;
								
								$expenses_buff .= number_format($val) . "円 ";
							}
							else if ( $k == 'note' )
							{
								$expenses_buff .= $val . " ";
							}
							else
							{
								continue;
							}
							
						}
						$expenses_buff .= "\r\n";
					}
					$expenses_buff .= "小計：" . number_format($subtotal) . "円" . "\r\n";
					$expenses_buff .= "\r\n";
				}
				else if ( $type == 4 )
				{
					$expenses_buff .= "その他" . "\r\n";
					foreach ( $expense as $k => $value )
					{
						$expenses_buff .= "・";
					
						foreach ( $value as $k => $val )
						{
							if ( $k == 'item_id' && !empty($val) )
							{
								//$expenses_buff .= $items[$k] . " ";
							}
							if ( $k == 'affiliation' )
							{
								//$expenses_buff .= $val . " ";
							}
							else if ( $k == 'job' )
							{
								//$expenses_buff .= $val . " ";
							}
							else if ( $k == 'lastname' )
							{
								//$expenses_buff .= $val . " ";
							}
							else if ( $k == 'firstname' )
							{
								//$expenses_buff .= $val . " ";
							}
							else if ( $k == 'title' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'count' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'price' )
							{
								$expenses_buff .= $val . " ";
							}
							else if ( $k == 'request_price' )
							{
								if ( empty($val) )
								{
									$val = 0;
								}
								
								$total_price = $total_price + $val;
								$subtotal = $subtotal + $val;
								
								$expenses_buff .= number_format($val) . "円 ";
							}
							else if ( $k == 'note' )
							{
								$expenses_buff .= $val . " ";
							}
							else
							{
								continue;
							}
							
						}
						$expenses_buff .= "\r\n";
					}
					$expenses_buff .= "小計：" . number_format($subtotal) . "円" . "\r\n";
					$expenses_buff .= "\r\n";
				}
			}
		}
		$total_price = number_format($total_price);
		
		$event_manager_text = "";
		if ( !empty($event_manager) )
		{
			foreach ( $event_manager['EventManager'] as $manager )
			{
				$event_manager_text .= '■運営責任者' . "\r\n";
				$event_manager_text .= '参加者ID（メールアドレス）:' . $manager['email'] . "\r\n";
				$event_manager_text .= '姓名:' . $manager['lastname'] . ' ' . $manager['firstname'] . "\r\n";
				$event_manager_text .= 'フリガナ:' . $manager['lastname_kana'] . ' ' . $manager['firstname_kana'] . "\r\n";
				$event_manager_text .= '所属機関:' . $manager['organization'] . "\r\n";
				$event_manager_text .= '所属部局:' . $manager['department'] . "\r\n";
				$event_manager_text .= '職名:' . $manager['job_title'] . "\r\n";
				$event_manager_text .= '郵便番号及びZIP CODE:' . $manager['zip'] . "\r\n";
				$event_manager_text .= '住所:' . $prefectures[$manager['prefecture_id']] . $manager['city'].$manager['address'] . "\r\n";
				$event_manager_text .= 'TEL:' . $manager['tel'] . "\r\n";
				$event_manager_text .= 'FAX:' . $manager['fax'] . "\r\n";
				$event_manager_text .= 'URL:' . $manager['url'] . "\r\n";
			}
		}
		
		if ( !empty($event_manager) )
		{
			foreach ( $event_manager['EventAffair'] as $manager )
			{
				$event_manager_text .= '■事務担当者' . "\r\n";
				$event_manager_text .= '参加者ID（メールアドレス）:' . $manager['email'] . "\r\n";
				$event_manager_text .= '姓名:' . $manager['lastname'] . ' ' . $manager['firstname'] . "\r\n";
				$event_manager_text .= 'フリガナ:' . $manager['lastname_kana'] . ' ' . $manager['firstname_kana'] . "\r\n";
				$event_manager_text .= '所属機関:' . $manager['organization'] . "\r\n";
				$event_manager_text .= '所属部局:' . $manager['department'] . "\r\n";
				$event_manager_text .= '職名:' . $manager['job_title'] . "\r\n";
				$event_manager_text .= '郵便番号及びZIP CODE:' . $manager['zip'] . "\r\n";
				$event_manager_text .= '住所:' . $prefectures[$manager['prefecture_id']] . $manager['city'].$manager['address'] . "\r\n";
				$event_manager_text .= 'TEL:' . $manager['tel'] . "\r\n";
				$event_manager_text .= 'FAX:' . $manager['fax'] . "\r\n";
				$event_manager_text .= 'URL:' . $manager['url'] . "\r\n";
			}
		}
		
		// 
		$ev = $this->Event->find('first', array('contain' => array(), 'conditions' => array('Event.id' => $this->Auth->user('id'))));
		
		$mail_data = array();
		$name					= $event['EventManager'][0]['lastname'] . ' ' . $event['EventManager'][0]['firstname']; //企画運営責任者名
		//$event_number			= $ev['Event']['event_number'];		//企画番号
		$event_number			= $ev['Event']['id'];				//応募受付番号に変更
		
		
		$username				= $ev['Event']['username'];											//ログインID
		$password				= 'セキュリティの為、表示しておりません。';							//パスワード
		$title					= $event['Event']['title'];									//イベント名称
		$event_themes			= $event_themes_buff;												//該当する重点テーマ
		$field					= $event['Event']['field'];									//連携分野
		
		$keywords = array();
		foreach ( $event['EventKeyword'] as $event_keyword )
		{
			if ( !empty($event_keyword['title']) )
			{
				$keywords[] = $event_keyword['title'];
			}
		}
		$keyword				= implode('、', $keywords);
		
		$organization			= $event['Event']['organization'];							//主催機関
		$start					= date('Y年m月d日', strtotime($event['Event']['start']));	//開催時期（開始）
		$end					= date('Y年m月d日', strtotime($event['Event']['end']));		//開催時期（終了）
		$place					= $event['Event']['place'];
		
		$program				= $event['Event']['program'];				//プログラム
		$purpose				= $event['Event']['purpose'];				//趣旨・目的
		$subject				= $event['Event']['subject'];				//解決すべき課題
		$approach				= $event['Event']['approach'];				//考えられる数学・数理科学的アプローチ
		$follow					= $event['Event']['follow'];					//会議終了後に考えられるフォローアップ
		$prepare				= $event['Event']['prepare'];				//これまでの準備
		
		$is_support = '無';
		if ( isset($event['Event']['is_support']))
		{
			if ( $event['Event']['is_support'] == 1 )
			{
				$is_support				= '有';	//有の場合は支援元：
			}
		}
		
		$support				= $event['Event']['support'];		//他からの支援
		
		$expenses_text			= $expenses_buff;
		
		
		$qualification_option1 = array('0' => '無', '1' => '有');
		$qualification_option2 = array('0' => '不要', '1' => '要');
		
		$qualification			= '無';						//参加資格
		if ( isset($event['Event']['qualification']) )
		{
			$qualification			= $qualification_option1[$event['Event']['qualification']];						//参加資格
		}
		$qualification_other	= '';				//アリの場合は参加資格
		if ( isset($event['Event']['qualification_other']) )
		{
			$qualification_other	= $event['Event']['qualification_other'];				//アリの場合は参加資格
		}
		
		$qualification_apply = '不要';
		if ( isset($event['Event']['qualification_apply']) )
		{
			$qualification_apply	= $qualification_option2[$event['Event']['qualification_apply']];		//参加申込みの要不要
		}
		
		
		
		//$is_qualification_cost	= $options2[$event['Event']['is_qualification_cost']];	//参加費の有無
		//$qualification_cost		= $event['Event']['qualification_cost'];					//参加費の詳細
		
		$login_url				= Configure::read('App.site_url') . 'databases/login';	// ログインURL
		
		$name = $event['EventManager'][0]['lastname'].$event['EventManager'][0]['firstname'];
		
		$mail_data['body'] = "";
$mail_data['body'] .= <<< EOM
{$name} 様

この度は企画をご応募頂き誠にありがとうございます。

下記の内容で応募内容の申請を承りました。 
企画の内容を編集する場合は、
下記のURLよりログインし、企画を編集して下さい。

ログインURL：
{$login_url}

ログインID：{$username}
パスワード：{$password}

【企画の概要】
応募受付番号：{$event_number}
名称：{$title}
集会等のタイプ:{$event_themes}
連携相手の分野・業界：{$field}
キーワード：{$keyword}
主催機関：{$organization}
開催時期：{$start}～{$end}
開催場所：{$place}

【企画の詳細】
プログラム（未定の場合その旨を明記）：
{$program}
趣旨・目的：
{$purpose}
取り扱うテーマ・トピックや解決すべき課題：
{$subject}
考えられる数学・数理科学的アプローチ：
{$approach}
これまでの準備状況：
{$prepare}
終了後のフォローアップの計画：
{$follow}
他機関からの支援：{$is_support}
有の場合は支援元：
{$support}

【経費】
{$expenses_text}
合計金額：{$total_price}円

【参加について】
参加制限：{$qualification}
有の場合は参加資格：{$qualification_other}
参加申込：{$qualification_apply}

【責任者】
{$event_manager_text}

EOM;
		
		/*
		header("Content-type: text/html; charset=utf-8");
		print_a_die($mail_data['body']);
		die();
		*/
		
		if ( !$this->develop )
		{
			
			if (isset($event['EventManager']) && !empty($event['EventManager']))
			{
				foreach ( $event['EventManager'] as $em )
				{
					$email = new CakeEmail();
					$email->config('default');
					$email->from(array('aimap@imi.kyushu-u.ac.jp' => 'AIMaP事務局（九大IMI）'));
					$email->to(trim( $em['email']));
					$email->template('notification');
					$email->subject('2019年度AIMaP企画応募完了通知');
					$email->viewVars($mail_data);

					if ( !$email->send() )
					{
						$mail_error[] = $em['email'];
					}
					else
					{
						$mail_send[] = $em['email'];
					}
				}
			}
			
			if (isset($event['EventAffair']) && !empty($event['EventAffair']))
			{
				foreach ( $event['EventAffair'] as $ea )
				{
					$email = new CakeEmail();
					$email->config('default');
					$email->from(array('aimap@imi.kyushu-u.ac.jp' => 'AIMaP事務局（九大IMI）'));
					$email->to(trim( $ea['email']));
					$email->template('notification');
					$email->subject('2019年度AIMaP企画応募完了通知');
					$email->viewVars($mail_data);

					if ( !$email->send() )
					{
						$mail_error[] = $ea['email'];
					}
					else
					{
						$mail_send[] = $ea['email'];
					}
				}
			}
			
			$email = new CakeEmail();
			$email->config('default');
			$email->from(array('aimap@imi.kyushu-u.ac.jp' => 'AIMaP事務局（九大IMI）'));
			$email->to('aimap@imi.kyushu-u.ac.jp');
			$email->template('notification');
			$email->subject('2019年度AIMaP企画応募完了通知');
			$email->viewVars($mail_data);
			if ( !$email->send() )
			{
				
			}
		}
		
		$this->set('mail_send', $mail_send);
		$this->set('mail_error', $mail_error);
		
		// 成功時
		// セッションから入力データを削除
		//$this->Session->destroy('Event.Edit1');
		//$this->Session->destroy('Event.Edit2');
		//$this->Session->destroy('Event.Edit3');
		//$this->Session->destroy('Event.Edit4');
		//$this->Session->destroy('Event.Edit5');
		
	}

	
	/**********************************************************
	 * 報告書
	 */
	// 1ページ目 報告書の概要
	public function report_add1() {
		$event_id = $this->Auth->user('id');
		 
		// 企画データの取得
		$this->Event->hasMany['EventTheme']['conditions']	= array('EventTheme.is_delete' => 0);
		$this->Event->hasMany['EventManager']['conditions']	= array('EventManager.is_delete' => 0);
		$this->Event->hasMany['EventAffair']['conditions']	= array('EventAffair.is_delete' => 0);
		
		// バリデーションの設定を避難
		$event_manager_validate	= $this->EventManager->validate;
		$event_affair_validate	= $this->EventAffair->validate;
		
		// 途中保存時はバリデーションを外す
		$this->EventManager->validate = array();
		$this->EventAffair->validate = array();
		
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme',
				'EventKeyword',
				'EventManager',
				'EventAffair',
			),
			'conditions' => array(
				'Event.is_delete' => 0,
				'Event.id' => $event_id
			)
		));
		$this->set('event', $event);
		
		// POST時
		if( $this->request->is('post') )
		{
			/// 一時保存ボタン押下時
			if ( isset($this->request->data['save']) )
			{
				
				$rollback = false;
				$this->Event->begin();
				
				// Eventテーブルの保存
				$this->request->data['Event']['id'] = $event_id;
				if ( !$this->Event->event_update($this->request->data)) 
				{
					// 保存失敗ロールバックフラグを立て、処理を止める
					$rollback = true;
				}
				
				// 保存失敗していなければ
				if ( !$rollback )
				{
					// EventeThemeの更新
					
					// 既存DBのIDのみ集める
					$event_theme_ids = array();
					foreach ( $event['EventTheme'] as $event_theme )
					{
						$event_theme_ids[] = $event_theme['theme_id'];
					}
					
					
					// POSTされたテーマのIDを集める
					$post_theme_ids_buff = array();
					foreach ( $this->request->data['EventTheme'] as $theme_id => $post_theme )
					{
						// チェックが付いている場合のみ
						if ( $post_theme['id'] == 1 )
						{
							$post_theme_ids_buff[] = $theme_id;
						}
					}
					
					$post_theme_ids = array();
					if ( !empty($post_theme_ids_buff) )
					{
						foreach ( $post_theme_ids_buff as $post_theme_id )
						{
							$theme = $this->Theme->find('first', array(
								'contain' => array(),
								'conditions' => array(
									'Theme.is_delete' => 0,
									'Theme.id' => $post_theme_id
								)
							));
							$post_theme_ids[] = $theme['Theme']['id'];
						}
					}
					
					// チェックを外された差分（削除フラグを立てる）
					$event_theme_deletes = array_diff($event_theme_ids, $post_theme_ids);
					
					// 新たにチェックをつけた差分（挿入する）
					$event_theme_inserts = array_diff( $post_theme_ids, $event_theme_ids);
					
					
					// EventThemeの削除処理
					if ( !empty($event_theme_deletes) )
					{
						foreach ( $event_theme_deletes as $event_theme_id )
						{
							$save = array();
							$save['EventTheme']['id']			= $event_theme_id;
							$save['EventTheme']['is_delete']	= 1;
							
							// ループ中にupdateを行う場合は、setを使い、必ずModelにデータを渡すこと
							$this->EventTheme->set($save);
							if ( !$this->EventTheme->save($save) )
							{
								// 保存失敗ロールバックフラグを立て、処理を止める
								$rollback = true;
								break;
							}
						}
					}
					
					
					// EventTheme挿入処理
					if ( !empty($event_theme_inserts) )
					{
						foreach ( $event_theme_inserts as $event_theme_id )
						{
							$save = array();
							$save['EventTheme']['event_id']		= $event_id;
							$save['EventTheme']['theme_id']		= $event_theme_id;
							
							// ループ中にinsertを行う場合は、createを使いDBのIDを作成する
							$this->EventTheme->create();
							if ( !$this->EventTheme->save($save) )
							{
								// 保存失敗ロールバックフラグを立て、処理を止める
								$rollback = true;
								break;
							}
						}
					}
				}
				
				// EventKeywordの更新
				if ( !$rollback )
				{
					if ( !empty($this->request->data['EventKeyword']) )
					{
						foreach ( $this->request->data['EventKeyword'] as $event_keyword )
						{
							// キーワードが入力されている場合のみ挿入 or 更新
							if ( !empty($event_keyword['title']) )
							{
								$save = array();
								$save['EventKeyword']['event_id']	= $event_id;
								$save['EventKeyword']['title']		= trim($event_keyword['title']);
								if ( !empty($event_keyword['id']) )
								{
									// IDが存在すれば既存データと見なしupdate
									$save['EventKeyword']['id'] = $event_keyword['id'];
									$this->EventKeyword->set($save);
								}
								else
								{
									// IDが存在しなければ新規データと見なしinsert
									$this->EventKeyword->create();
								}
								
								if ( !$this->EventKeyword->save($save) )
								{
									// 保存失敗ロールバックフラグを立て、処理を止める
									$rollback = true;
									break;
								}
							}
						}
					}
				}
				
				// EventManagerの更新
				if ( !$rollback )
				{
					foreach ( $this->request->data['EventManager'] as $key =>  $event_manager )
					{
						$event_manager['event_id'] = $event_id;
						if ( empty($event_manager['id']) )
						{
							// IDが空であればinsert
							
							if ( isset($event_manager['is_delete']) && $event_manager['is_delete'] == 1 )
							{
								// DBに存在せず、セッションのみ存在するデータに削除チェックされた場合
								unset($this->request->data['EventManager'][$key]);
							}
							else
							{
								$this->EventManager->create();
								if ( !$this->EventManager->save($event_manager) )
								{
									// 保存失敗
									// 処理を止めてrollback フラグを立てる
									$rollback = true;
									break;
								}
							}
						}
						else
						{
							// IDが空でなければupdate
							$this->EventManager->set($event_manager);
							if ( !$this->EventManager->save($event_manager) )
							{
								// 保存失敗
								// 処理を止めてrollback フラグを立てる
								$rollback = true;
								break;
							}
						}
					}
				}
				
				// EventAffairの更新
				if ( !$rollback )
				{
					foreach ( $this->request->data['EventAffair'] as $key => $event_affair )
					{
						$event_affair['event_id'] = $event_id;
						if ( empty($event_affair['id']) )
						{
							// IDが空であればinsert
							
							if ( isset($event_affair['is_delete']) && $event_affair['is_delete'] == 1 )
							{
								// DBに存在せず、セッションのみ存在するデータに削除チェックされた場合
								unset($this->request->data['EventAffair'][$key]);
							}
							else
							{
								$this->EventManager->create();
								if ( !$this->EventAffair->save($event_affair) )
								{
									// 保存失敗
									// 処理を止めてrollback フラグを立てる
									$rollback = true;
									break;
								}
							}
						}
						else
						{
							// IDが空でなければupdate
							$this->EventAffair->set($event_affair);
							if ( !$this->EventAffair->save($event_affair) )
							{
								// 保存失敗
								// 処理を止めてrollback フラグを立てる
								$rollback = true;
								break;
							}
						}
					}
				}
			
				if ( !$rollback )
				{
					// 成功時
					// ここでコミットする
					$this->Event->commit();
					$this->Session->write('Report.Add', $this->request->data);
					$this->Session->setFlash('入力データを一時保存しました。', 'Flash/success');
					$this->redirect(array('action' => 'report_add1'));
				}
				else
				{
					// 失敗時
					$this->Event->rollback();
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			// 運営責任者を増やすボタン押下時
			else if ( isset($this->request->data['manager']) )
			{
				if ( !empty($this->request->data['EventManager']) )
				{
					$next = count($this->request->data['EventManager']);
					
					$this->request->data['EventManager'][$next] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				else
				{
					// 通常ありえないが万が一、一つもない場合
					$this->request->data['EventManager'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
			}
			// 事務担当者を増やすボタン押下時
			else if ( isset($this->request->data['affair']) )
			{
				if ( !empty($this->request->data['EventAffair']) )
				{
					$next = count($this->request->data['EventAffair']);
					
					$this->request->data['EventAffair'][$next] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				else
				{
					// 通常ありえないが万が一、一つもない場合
					$this->request->data['EventAffair'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
			}
			// 次へボタン押下時
			else if ( isset($this->request->data['confirm']) )
			{
				// バリデーション設定を元に戻す
				$this->EventManager->validate	= $event_manager_validate;
				$this->EventAffair->validate	= $event_affair_validate;
				
				$is_error = false;
				$is_checked = false;
				// テーマの入力チェック
				foreach ( $this->request->data['EventTheme'] as $theme )
				{
					if ( $theme['id'] == 1 )
					{
						$is_checked = true;
						break;
					}
				}
				if ( !$is_checked )
				{
					$this->EventTheme->validationErrors['id'][] = '該当する重点テーマをお選び下さい。';
				}
				
				// 責任者の入チェック
				$managers_errors = array();
				foreach ( $this->request->data['EventManager'] as $key => $manager )
				{
					$this->EventManager->set( $manager );
					if ( !$this->EventManager->validates() )
					{
						$managers_errors[$key] = $this->EventManager->validationErrors;
					}
				}
				
				// 入力チェックの結果を変数に格納
				if ( !empty($managers_errors) )
				{
					$is_error = true;
					$this->EventManager->validationErrors = $managers_errors;
				}
				
				// 事務担当者の入チェック
				$affairs_errors = array();
				foreach ( $this->request->data['EventAffair'] as $key => $affairs )
				{
					$this->EventAffair->set( $affairs );
					if ( !$this->EventAffair->validates() )
					{
						$affairs_errors[$key] = $this->EventAffair->validationErrors;
					}
				}
				// 入力チェックの結果を変数に格納
				if ( !empty($affairs_errors) )
				{
					$is_error = true;
					$this->EventAffair->validationErrors = $affairs_errors;
				}
				
				// エラーがない場合
				if ( $is_checked && !$is_error)
				{
					// Modelにて設定のバリデーションチェック
					$this->Event->set($this->request->data);
					if ( $this->Event->validates() )
					{
						// ページ間でデータを持ち回すためセッションに保存
						$session_data = $this->Session->read('Report.Add');
						if ( !empty($session_data) )
						{
							$session_data += $this->request->data;
						}
						else
						{
							$session_data = $this->request->data;
						}
						$this->Session->write('Report.Add', $session_data);
						
						// エラーがなければ次ページへ移動
						$this->redirect(array('action' => 'report_add2'));
					}
				}
				
				$this->Session->setFlash('入力内容に不備があります。', 'Flash/error');
				
				
				// バリデーションの設定を避難
				$event_manager_validate	= $this->EventManager->validate;
				$event_affair_validate	= $this->EventAffair->validate;
				
				// 途中保存時はバリデーションを外す
				$this->EventManager->validate = array();
				$this->EventAffair->validate = array();
			}
		}
		else
		{
			// 初期表示
			$session_data = $this->Session->read('Report.Add');
			if ( empty($session_data) )
			{
				// EventTheme
				if ( !empty($event['EventTheme']) )
				{
					foreach ( $event['EventTheme'] as $event_theme )
					{
						$event['EventTheme'][$event_theme['theme_id']]['id'] = 1;
					}
				}
				
				// 空の入力枠を１つ用意
				if ( empty($event['EventManager']) )
				{
					$event['EventManager'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				
				// 空の入力枠を１つ用意
				if ( empty($event['EventAffair']) )
				{
					$event['EventAffair'][0] = array(
						'id'				=> '',
						'email'				=> '',
						'lastname'			=> '',
						'firstname'			=> '',
						'lastname_kana'		=> '',
						'firstname_kana'	=> '',
						'organization'		=> '',
						'department'		=> '',
						'job_title'			=> '',
						'url'				=> '',
						'zip'				=> '',
						'prefecture_id'		=> 0,
						'city'				=> '',
						'address'			=> '',
						'tel'				=> '',
						'fax'				=> ''
					);
				}
				$this->request->data = $event;
			}
			else
			{
				$this->request->data = $session_data;
			}
		}
		
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// 2ページ目 報告書のプログラム
	public function report_add2()
	{
		$event_id = $this->Auth->user('id');
		
		$event = $this->Event->find('first', array(
			'contain' => array(
			),
			'conditions' => array(
				'Event.id' => $event_id,
			)
		));
		$this->set('event', $event);
		
		if ( $this->request->is('post') )
		{
			// 一時保存ボタン押下時
			if ( isset($this->request->data['save']) )
			{
				$this->request->data['Event']['id'] = $event['Event']['id'];
				
				if ( $this->Event->save($this->request->data) )
				{
					$this->Session->write('Report.Add.EventProgram', $this->request->data['Event']);
					$this->Session->setFlash('入力データを一時保存しました。', 'Flash/success');
					$this->redirect(array('action' => 'report_add2'));
				}
				else
				{
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			// 次へボタン押下時
			else if ( isset($this->request->data['confirm']) )
			{
				$errors = array();
				if ( empty($errors) )
				{
					// ページ間でデータを持ち回すためセッションに保存
					$this->Session->write('Report.Add.EventProgram', $this->request->data['Event']);
					
					// エラーがなければ次ページへ移動
					$this->redirect(array('action' => 'report_add3'));
				}
			}
			else
			{
				// 一時保存以外のボタンを押下時
				foreach ($this->request->data['EventProgram']  as $key1 => $event_programs )
				{
					foreach ( $event_programs as $key2 => $event_program )
					{
						// 講演を追加の場合
						if ( isset($event_program['add-program']) )
						{
							$program_count = count($this->request->data['EventProgram'][$key1]) + 1;
							$this->request->data['EventProgram'][$key1][] = array(
								'id'		=> 0,
								'event_id'	=> $event_id,
								'title'		=> '',
								'date'		=> date('Y-m-d',  strtotime($key1)),
								'sort'		=> $program_count,
								'EventPerformer' => array(
									0 => array(
										'id'				=> 0,
										'event_program_id'	=> 0,
										'organization'		=> '',
										'role'				=> '',
										'lastname'			=> '',
										'firstname'			=> '',
										'is_delete'			=> 0
									)
								)
							);
						}
						// 講演者を追加の場合
						else if ( isset($event_program['add-performer']) )
						{
							 $this->request->data['EventProgram'][$key1][$key2]['EventPerformer'][] = array(
								'id'				=> 0,
								'event_program_id'	=> 0,
								'organization'		=> '',
								'role'				=> '',
								'lastname'			=> '',
								'firstname'			=> '',
								'is_delete'			=> 0
							);
						}
						// 講演を削除の場合
						if ( isset($event_program['delete-program']) )
						{
							// DBに保存済み
							if ( !empty($event_program['id']) && is_numeric($event_program['id']) )
							{
								$event_program['is_delete']  = 1;
								if ( $this->EventProgram->save($event_program) )
								{
									
								}
							}
							unset($this->request->data['EventProgram'][$key1][$key2]);
						}
						else
						{
							foreach ( $event_program['EventPerformer'] as $key3 => $event_performer )
							{
								// 講演者を削除の場合
								if ( isset($event_performer['delete-performer']) )
								{
									// DBに保存済み
									if ( !empty($event_performer['id']) && is_numeric($event_performer['id']) )
									{
										$event_performer['is_delete']  = 1;
										if ( $this->EventPerformer->save($event_performer) )
										{
											
										}
									}
									unset($this->request->data['EventProgram'][$key1][$key2]['EventPerformer'][$key3]);
								}
							}
						}
					}
				}
				
				$this->Session->write('Report.Add.EventProgram', $this->request->data['EventProgram']);
				$this->redirect(array('action' => 'report_add2'));
			}
		}
		else
		{
			// 初期表示
			// セッションより全ページの入力内容を引き継ぐ
			$buff = $this->Session->read('Report.Add');
			if ( !empty($buff['EventProgram']) )
			{
				$report_programs_buff = $buff['EventProgram'];
			}
			else
			{
				if ( !empty($event['EventProgram']) )
				{
					$i=0;
					$report_programs_buff = array();
					foreach ( $event['EventProgram'] as $key => $report_program )
					{
						$date = date('Ymd', strtotime($report_program['date']));
						$report_programs_buff[$date][$i] = $report_program;
						$i++;
					}
				}
				else
				{
					// 開始日と終了日から開催期間を取得し、その日数分ループ用の配列を作成
					$diff = ((strtotime($event['Event']['end']) - strtotime($event['Event']['start'])) / 60 / 60 / 24) + 1;
					$report_programs_buff = array();
					for ( $i = 0; $i < $diff; $i++ )
					{
						$day = date('Ymd', strtotime($event['Event']['start']) + ($i * 86400));
						$report_programs_buff[$day] = array();
					}
					
					foreach ( $report_programs_buff as $key1 =>  $date )
					{
						$report_programs_buff[$key1][] = array(
							'id'		=> 0,
							'event_id'	=> $event_id,
							'title'		=> '',
							'date'		=> date('Y-m-d',  strtotime($key1)),
							'sort'		=> 1,
							'EventPerformer' => array(
								0 => array(
									'id'				=> 0,
									'event_program_id'	=> 0,
									'organization'		=> '',
									'role'				=> '',
									'lastname'			=> '',
									'firstname'			=> '',
									'is_delete'			=> 0
								)
							)
						);
					}
				}
			}
			
			$event['EventProgram'] = $report_programs_buff;
			$buff = $event;
			
			$this->request->data = $buff;
		}
		
		$this->set('op', array(
			'1' => '1人',
			'2' => '2人',
			'3' => '3人',
			'4' => '4人',
			'5' => '5人',
		));
	}

	// 3ページ目　添付ファイル
	function report_add3 ()
	{
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventFile'
			),
			'conditions' => array(
				'Event.id' => $this->Auth->user('id'),
			)
		));
		$this->set('event', $event);
		$event_id = $event['Event']['id'];
		
		if ( $this->request->is('post') || $this->request->is('put') )
		{
			//一時保存 or 確認画面
			if ( isset($this->request->data['save']) || isset($this->request->data['confirm']) )
			{
				$rollback = false;
				$this->EventFile->begin();
				
				foreach ( $this->request->data['EventFile'] as $event_file  )
				{
					if ( isset($event_file['file']['name']) && !empty($event_file['file']['name']) )
					{
						if ( !empty($event_file['id']) && is_numeric($event_file['id']) )
						{
							// 更新
							$this->EventFile->set($event_file);
						}
						else
						{
							// 新規
							$this->EventFile->create();
						}
						if ( !$this->EventFile->save($event_file) )
						{
							$rollback = true;
						 	break;
						}
					}
				}
				
				if ( !$rollback )
				{
					$this->EventFile->commit();
					
					// ページ間でデータを持ち回すためセッションに保存
					$this->Session->write('Report.Add.EventFile', $this->request->data['EventFile']);
					
					//一時保存
					if ( isset($this->request->data['save']) )
					{
						$this->Session->setFlash('入力データを一時保存しました。', 'Flash/success');
						$this->redirect(array('action' => 'report_add3'));
					}
					// 確認画面
					else if ( isset($this->request->data['confirm']) )
					{
						$this->Session->write('Report.Add.EventFile', $this->request->data['EventFile']);
						$this->redirect(array('action' => 'report_confirm'));
					}
				}
				else
				{
					$this->EventFile->rollback();
					$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				}
			}
			else if (isset($this->request->data['add_event_file']))
			{
				$this->request->data['EventFile'][] = array(
					'id'		=> 0,
					'event_id'	=> $event_id,
					'name'		=> '',
					'file'		=> '',
					'file_org'	=> '',
				);
			}
		}
		else
		{
			// 初期表示
			$buff = $this->Session->read('Report.Add');
			if ( !empty($event['EventFile']) )
			{
				$diff = 3 - count($event['EventFile']);
				
				if ( $diff > 0 )
				{
					for ( $i = 1; $i <= $diff; $i++ )
					{
						$event['EventFile'][] = array(
							'id'		=> 0,
							'event_id'	=> $event_id,
							'name'		=> '',
							'file'		=> '',
							'file_org'	=> '',
						);
					}
				}
				$buff['EventFile'] = $event['EventFile'];
			}
			else
			{
				$buff['EventFile'] = array(
					0 => array(
						'id'		=> 0,
						'event_id'	=> $event_id,
						'name'		=> '',
						'file'		=> '',
						'file_org'	=> '',
					),
					1 => array(
						'id'		=> 0,
						'event_id'	=> $event_id,
						'name'		=> '',
						'file'		=> '',
						'file_org'	=> '',
					),
					2 => array(
						'id'		=> 0,
						'event_id'	=> $event_id,
						'name'		=> '',
						'file'		=> '',
						'file_org'	=> '',
					),
				);
			}
			$this->request->data = $buff;
			//print_a_die($this->request->data);
		}
	}

	// 入力内容確認
	public function report_confirm()
	{
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme' => array(
					'Theme'
				),
				'EventFile'
			),
			'conditions' => array(
				'Event.id' => $this->Auth->user('id'),
			)
		));
		$this->set('event2', $event);
		
		if ( $this->request->is('post') )
		{
			$this->redirect(array('action' => 'complete'));
		}
		else
		{
			$event = $this->Session->read('Report.Add');
			if (empty($event))
			{
				$this->Session->setFlash('セッションが切れました。', 'Flash/error');
				$this->redirect(array('action' => 'report_add1'));
			}
			$this->set('event', $event);
			$this->request->data = $event;
		}
		$this->set('themes', $this->Theme->find('list', array('conditions' => array('Theme.is_delete' => 0))));
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);

	}

	// 入力内容登録
	public function report_complete()
	{
		$event_id	= $this->Auth->user('id');
		
		$event = $this->Event->find('first', array(
			'contain' => array(
				'EventTheme',
				'EventKeyword',
				'EventManager',
				'EventAffair',
				'EventProgram' => array(
					'EventPerformer'
				),
				'EventFile'
			),
			'conditions' => array(
				'Event.is_delete' => 0,
				'Event.id' => $event_id
			)
		));
		
		$event_files = $this->request->data;
		$this->request->data = $this->Session->read('Report.Add');
		if ( !empty($event_files) )
		{
			$this->request->data['EventFile'] = $event_files;
		}
		
		if ( !empty($this->request->data) )
		{
			$rollback = false;
			$last_id = 0;
			$this->Event->begin();
			
			$this->request->data['Event']['id']		= $event_id;
			$this->request->data['Event']['status']	= 4; // 報告書承認中（提出済み）
			$this->request->data['Event']['program']	= $this->request->data['EventProgram']['program'];
			
			
			if ( !$this->Event->save($this->request->data['Event']) )
			{
				$rollback = true;
			}
			
			// 保存失敗していなければ
			if ( !$rollback )
			{
				// EventeThemeの更新
				
				// 既存DBのIDのみ集める
				$event_theme_ids = array();
				foreach ( $event['EventTheme'] as $event_theme )
				{
					$event_theme_ids[] = $event_theme['theme_id'];
				}
				
				// POSTされたテーマのIDを集める
				$post_theme_ids_buff = array();
				foreach ( $this->request->data['EventTheme'] as $theme_id => $post_theme )
				{
					// チェックが付いている場合のみ
					if ( $post_theme['id'] == 1 )
					{
						$post_theme_ids_buff[] = $theme_id;
					}
				}
				
				$post_theme_ids = array();
				if ( !empty($post_theme_ids_buff) )
				{
					foreach ( $post_theme_ids_buff as $post_theme_id )
					{
						$theme = $this->Theme->find('first', array(
							'contain' => array(),
							'conditions' => array(
								'Theme.is_delete' => 0,
								'Theme.id' => $post_theme_id
							)
						));
						$post_theme_ids[] = $theme['Theme']['id'];
					}
				}
				
				// チェックを外された差分（削除フラグを立てる）
				$event_theme_deletes = array_diff($event_theme_ids, $post_theme_ids);
				
				// 新たにチェックをつけた差分（挿入する）
				$event_theme_inserts = array_diff( $post_theme_ids, $event_theme_ids);
				
				
				// EventThemeの削除処理
				if ( !empty($event_theme_deletes) )
				{
					foreach ( $event_theme_deletes as $event_theme_id )
					{
						$save = array();
						$save['EventTheme']['id']			= $event_theme_id;
						$save['EventTheme']['is_delete']	= 1;
						
						// ループ中にupdateを行う場合は、setを使い、必ずModelにデータを渡すこと
						$this->EventTheme->set($save);
						if ( !$this->EventTheme->save($save) )
						{
							// 保存失敗ロールバックフラグを立て、処理を止める
							$rollback = true;
							break;
						}
					}
				}
				
				
				// EventTheme挿入処理
				if ( !empty($event_theme_inserts) )
				{
					foreach ( $event_theme_inserts as $event_theme_id )
					{
						$save = array();
						$save['EventTheme']['event_id']		= $event_id;
						$save['EventTheme']['theme_id']		= $event_theme_id;
						
						// ループ中にinsertを行う場合は、createを使いDBのIDを作成する
						$this->EventTheme->create();
						if ( !$this->EventTheme->save($save) )
						{
							// 保存失敗ロールバックフラグを立て、処理を止める
							$rollback = true;
							break;
						}
					}
				}
			}
			
			// EventKeywordの更新
			if ( !$rollback )
			{
				if ( !empty($this->request->data['EventKeyword']) )
				{
					foreach ( $this->request->data['EventKeyword'] as $event_keyword )
					{
						// キーワードが入力されている場合のみ挿入 or 更新
						if ( !empty($event_keyword['title']) )
						{
							$save = array();
							$save['EventKeyword']['event_id']	= $event_id;
							$save['EventKeyword']['title']		= trim($event_keyword['title']);
							if ( !empty($event_keyword['id']) )
							{
								// IDが存在すれば既存データと見なしupdate
								$save['EventKeyword']['id'] = $event_keyword['id'];
								$this->EventKeyword->set($save);
							}
							else
							{
								// IDが存在しなければ新規データと見なしinsert
								$this->EventKeyword->create();
							}
							
							if ( !$this->EventKeyword->save($save) )
							{
								// 保存失敗ロールバックフラグを立て、処理を止める
								$rollback = true;
								break;
							}
						}
					}
				}
			}
			
			// EventManagerの更新
			if ( !$rollback )
			{
				foreach ( $this->request->data['EventManager'] as $key =>  $event_manager )
				{
					$event_manager['event_id'] = $event_id;
					if ( empty($event_manager['id']) )
					{
						// IDが空であればinsert
						
						if ( $event_manager['is_delete'] == 1 )
						{
							// DBに存在せず、セッションのみ存在するデータに削除チェックされた場合
							unset($this->request->data['EventManager'][$key]);
						}
						else
						{
							$this->EventManager->create();
							if ( !$this->EventManager->save($event_manager) )
							{
								// 保存失敗
								// 処理を止めてrollback フラグを立てる
								$rollback = true;
								break;
							}
						}
					}
					else
					{
						// IDが空でなければupdate
						$this->EventManager->set($event_manager);
						if ( !$this->EventManager->save($event_manager) )
						{
							// 保存失敗
							// 処理を止めてrollback フラグを立てる
							$rollback = true;
							break;
						}
					}
				}
			}
			
			// EventAffairの更新
			if ( !$rollback )
			{
				foreach ( $this->request->data['EventAffair'] as $key => $event_affair )
				{
					$event_affair['event_id'] = $event_id;
					if ( empty($event_affair['id']) )
					{
						// IDが空であればinsert
						
						if ( isset($event_affair['is_delete']) && $event_affair['is_delete'] == 1 )
						{
							// DBに存在せず、セッションのみ存在するデータに削除チェックされた場合
							unset($this->request->data['EventAffair'][$key]);
						}
						else
						{
							$this->EventManager->create();
							if ( !$this->EventAffair->save($event_affair) )
							{
								// 保存失敗
								// 処理を止めてrollback フラグを立てる
								$rollback = true;
								break;
							}
						}
					}
					else
					{
						// IDが空でなければupdate
						$this->EventAffair->set($event_affair);
						if ( !$this->EventAffair->save($event_affair) )
						{
							// 保存失敗
							// 処理を止めてrollback フラグを立てる
							$rollback = true;
							break;
						}
					}
				}
			}
			
			if ( !$rollback )
			{
				// 成功時
				// ここでコミットする
				$this->Event->commit();
				
				// セッションから入力データを削除
				$this->Session->delete('Report.Add');
			}
			else
			{
				// 失敗時
				$this->Event->rollback();
				$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
				$this->redirect(array('action' => 'report_add1'));
			}
		}
	}
	
	// 添付ファイル削除
	public function file_delete($id = null)
	{
		if ( empty($id) )
		{
			$this->Session->setFlash('Invalid ID', 'Flash/error');
			$this->redirect(array('action' => 'report_add3'));
		}
		
		$event_file_count = $this->EventFile->find('count', array(
			'contain' => array(),
			'conditions' => array(
				'EventFile.id' => $id
			)
		));
		
		if ( !$this->EventFile->delete($id) )
		{
			$this->Session->setFlash('添付ファイルの削除に失敗しました。管理者にお問合わせください。', 'Flash/error');
		}
		else
		{
			$this->Session->setFlash('添付ファイルを削除しました。', 'Flash/success');
		}
		$this->redirect(array('action' => 'report_add3'));
	}
	
	/**********************************************************
	 * API
	 */
	public function autocomplete($keyword = null)
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$ret = array();
		if ( !empty($keyword) )
		{
			$affiliations = $this->Affiliation->find('all', array(
				'contain' => array(),
				'conditions' => array(
					'Affiliation.name LIKE ?' => '%' . $keyword . '%',
					'Affiliation.is_delete' => 0
				)
			));
			
			foreach ( $affiliations as $affiliation )
			{
				$ret[] = $affiliation['Affiliation']['name'];
			}
		}
		
		echo json_encode($ret);
		die();
	}

	public function autocomplete_keyword($str = null)
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$ret = array();
		if ( !empty($str) )
		{
			$keywords = $this->ResearcherResearchKeyword->find('all', array(
				'contain' => array(),
				'conditions' => array(
					'ResearcherResearchKeyword.title LIKE ?' => '%' . $str . '%',
				),
	 			'group' => array('ResearcherResearchKeyword.title'),
	 			'order' => array('ResearcherResearchKeyword.title ASC')
			));
			
			foreach ( $keywords as $keyword )
			{
				$ret[$keyword['ResearcherResearchKeyword']['id']] = $keyword['ResearcherResearchKeyword']['title'];
			}
		}
		
		echo json_encode($ret);
		die();
	}

	public function delete_program($id = null)
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$data = $this->EventProgram->find('first', array(
			'contain' => array(
				
			),
			'conditions' => array(
				'EventProgram.id' => $id
			),
			
		));
		
		$data['EventProgram']['is_delete'] = 1;
		$ret['message'] = 'データを削除しました。';
		if ( !$this->EventProgram->save($data) )
		{
			$ret['message'] = 'データの削除に失敗しました。';
		}
		
		echo json_encode($ret);
		die();
	}

	public function delete_performer($id = null)
	{
		$this->layout = false;
		$this->autoRender = false;
		
		$data = $this->EventPerformer->find('first', array(
			'contain' => array(
				
			),
			'conditions' => array(
				'EventPerformer.id' => $id
			),
			
		));
		
		$data['EventPerformer']['is_delete'] = 1;
		$ret['message'] = 'データを削除しました。';
		if ( !$this->EventPerformer->save($data) )
		{
			$ret['message'] = 'データの削除に失敗しました。';
		}
		
		echo json_encode($ret);
		die();
	}

	/**********************************************************
	 * 関数
	 */
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
	
	
	
	
	
	public function test()
	{
		$this->set('event', $this->Event->find('first', array('contain' => array('EventTheme' => array('Theme')), 'conditions' => array('Event.id' => 9))));$report = $this->Report->find('first', array(
			'contain' => array(
				'ReportKeyword',
				'EventProgram',
			),
			'conditions' => array(
				'Report.event_id' => $this->Auth->user('id')
			)
		));
		
		if ( $this->request->is('post') )
		{
			
			unset($this->request->data['EventProgramBuff']);
			
			foreach ( $this->request->data['EventProgram'] as $program )
			{
				
				
				
				
				
			}
			
			
			
			
			//print_a_die($this->request->data);
			
		}
		else
		{
		
			// 初期表示
			$this->request->data = $this->Session->read('ReportAdd');
			if ( empty($this->request->data) )
			{
				$this->request->data['EventProgram'][1] = array(
					'id' => '',
					'time' => '',
					'title' => '',
					'org' => '',
					'lastname' => '',
					'firstname' => '',
					'abstract' => ''
				);
			}
		}

		$keywords = $this->ResearcherResearchKeyword->find('list', array(
			'contain'		=> array(),
			'conditions'	=> array(
				'ResearcherResearchKeyword.is_delete' => 0,
			),
			'group' => array('ResearcherResearchKeyword.title'),
			'order' => array('ResearcherResearchKeyword.title ASC')
		));
		$this->set('keywords', $keywords);
		
		$this->set('op', array(
			'1' => '1人',
			'2' => '2人',
			'3' => '3人',
			'4' => '4人',
			'5' => '5人',
		));
	}
	
	
	
}
