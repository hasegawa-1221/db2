<?php
App::uses('AppController', 'Controller');
class ReportsController extends AppController {

	public $uses = array('EventProgram', 'EventPerformer', 'Event', 'User', 'Affiliation');

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
			// デフォルト
			$this->request->data['Search']['keyword']		= '';
		}
		
		$conditions = array();
		$conditions[] = array('EventProgram.is_delete' => 0);
		//$conditions[] = array('EventProgram.is_display' => 1);
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array(
				'OR' => array(
					0 => array('Event.event_number LIKE'		=> '%' . trim($this->request->data['Search']['keyword']) . '%'),
					1 => array('EventProgram.title LIKE'		=> '%' . trim($this->request->data['Search']['keyword']) . '%')
				)
			);
		}
		
		
		$joins = array();
		$joins[] = array(
			'type'	=> 'LEFT',
			'table'	=> 'meetings',
			'alias'	=> 'Meeting',
			'conditions' => 'EventProgram.event_id = Meeting.event_id',
		);
		
		$this->EventProgram->hasMany['EventPerformer']['conditions'] = array('EventPerformer.is_delete' => 0);
		$this->modelClass = "EventProgram";
		$this->paginate = array(
			'contain' => array(
				'Event',
				'EventPerformer',
			),
			'fields' => array(
				'EventProgram.*',
				'Event.*',
				'Meeting.*',
			),
			'conditions' => $conditions,
			'joins' => $joins,
			'order' => 'Event.id ASC, EventProgram.date ASC, EventProgram.sort ASC'
		);
		$event_programs = $this->paginate();
		$this->set('event_programs', $event_programs);
		
		//種別の一覧
		$this->set('event_type', Configure::read('App.event_type'));
		
		//企画ステータスの一覧
		$event_status = Configure::read('App.event_status');
		$event_status[-1] = '------';
		ksort($event_status);
		$this->set('event_status', $event_status);
	}
	
	public function add ()
	{
		
		if ( $this->request->is('post') )
		{
			$this->request->data['EventProgram']['id'] = $event_program['EventProgram']['id'];
			if ( $this->EventProgram->save($this->request->data) )
			{
				$this->Session->setFlash('データの更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// 失敗時
				$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $event_program;
		}
	}
	
	public function edit ($id = null)
	{
		$this->EventProgram->id = $id;
		if ( !$this->EventProgram->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$this->EventProgram->hasMany['EventPerformer']['conditions'] = array('EventPerformer.is_delete' => 0);
		$event_program = $this->EventProgram->find('first', array(
			'contain' => array(
				'Event',
				'EventPerformer'
			),
			'conditions' => array(
				'EventProgram.id' => $id
			)
		));
		$this->set('event_program', $event_program);
		
		if ( $this->request->is('post') )
		{
			$this->request->data['EventProgram']['id'] = $event_program['EventProgram']['id'];
			if ( $this->EventProgram->save($this->request->data) )
			{
				$this->Session->setFlash('データの更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				// 失敗時
				$this->Session->setFlash('データの保存に失敗しました。管理者にお問合わせください。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $event_program;
		}
	}
}
