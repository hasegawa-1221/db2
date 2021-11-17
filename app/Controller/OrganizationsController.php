<?php
App::uses('AppController', 'Controller');
class OrganizationsController extends AppController {

	public $uses = array('Affiliation', 'Event', 'Prefecture');

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
			$this->request->data['Search']['keyword']	= '';
		}
		
		$conditions = array();
		$conditions[] = array('Affiliation.is_delete' => 0);
		$conditions[] = array('Affiliation.is_display' => 1);
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions += array('Affiliation.name LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%');
		}
		
		$this->modelClass = "Affiliation";
		$this->paginate = array(
			'contain' => array(
			),
			'conditions' => $conditions,
		);
		$affiliations = $this->paginate();
		$this->set('affiliations', $affiliations);
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
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
