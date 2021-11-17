<?php
App::uses('AppController', 'Controller');
class AffiliationsController extends AppController {

	public $uses = array('Affiliation', 'Prefecture', 'Event');

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	// 一覧
	public function index()
	{
		$affiliations = $this->Affiliation->find('all', array(
			'contain' => array(
				
			),
			'conditions' => array(
			)
		));
		$this->set('affiliations', $affiliations);
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	
	// 企画データより追加
	public function add( $event_id = 0 ) {
		
		if ($this->request->is('post'))
		{
			// 緯度・経度の更新を行う
			App::uses('HttpSocket', 'Network/Http');
			$HttpSocket = new HttpSocket();
			
			$q = trim($this->request->data['Affiliation']['city']) . ' ' . trim($this->request->data['Affiliation']['address']);
			
			$response = $HttpSocket->get(
				'http://www.geocoding.jp/api/',
				array(
					'q' => $q
				)
			);
			
			$xml = simplexml_load_string($response->body);
			$geo = json_decode(json_encode($xml));
			
			$this->request->data['Affiliation']['lat'] = $geo->coordinate->lat;
			$this->request->data['Affiliation']['lng'] = $geo->coordinate->lng;
			
			if ( $this->Affiliation->save($this->request->data) )
			{
				$this->Session->setFlash('所属を作成しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('所属の作成に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			if ( !empty($event_id) )
			{
				$event = $this->Event->find('first', array(
					'contain' => array(),
					'conditions' => array(
						'Event.id' => $event_id
					)
				));
				$this->request->data['Affiliation']['name'] = $event['Event']['organization'];
			}
		}
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// edit
	public function edit( $id = null ) {
		$this->Affiliation->id = $id;
		if ( !$this->Affiliation->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$affiliation = $this->Affiliation->find('first', array(
			'contain' => array(
				
			),
			'conditions' => array(
				'Affiliation.id' => $id
			)
		));
		$this->set('affiliation', $affiliation);
		
		if ($this->request->is('post'))
		{
			if ( isset($this->request->data['take']) )
			{
				// 緯度・経度の更新を行う
				App::uses('HttpSocket', 'Network/Http');
				$HttpSocket = new HttpSocket();
				
				$q = trim($this->request->data['Affiliation']['city']) . ' ' . trim($this->request->data['Affiliation']['address']);
				
				$response = $HttpSocket->get(
					'http://www.geocoding.jp/api/',
					array(
						'q' => $q
					)
				);
				
				$xml = simplexml_load_string($response->body);
				$geo = json_decode(json_encode($xml));
				
				$this->request->data['Affiliation']['lat'] = $geo->coordinate->lat;
				$this->request->data['Affiliation']['lng'] = $geo->coordinate->lng;
			}
			
			$this->request->data['Affiliation']['id'] = $id;
			if ( $this->Affiliation->save($this->request->data) )
			{
				$this->Session->setFlash('所属を編集しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('所属の編集に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $affiliation;
		}
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}
}
