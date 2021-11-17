<?php
App::uses('AppController', 'Controller');
class VenuesController extends AppController {

	public $uses = array('Event', 'User', 'Venue', 'Prefecture');

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
		$conditions[] = array('Venue.is_display' => 1);
		$conditions[] = array('Venue.is_delete' => 0);
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array('Venue.name LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%');
		}
		$venues = $this->Venue->find('all', array(
			'contain' => array(),
			'conditions' => $conditions
		));
		$this->set('venues', $venues);
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// 一覧
	public function venue_list()
	{
		$venues = $this->Venue->find('all', array(
			'contain' => array(
				
			),
			'conditions' => array(
			)
		));
		$this->set('venues', $venues);
		
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
			
			$q = trim($this->request->data['Venue']['city']) . ' ' . trim($this->request->data['Venue']['address']);
			
			$response = $HttpSocket->get(
				'http://www.geocoding.jp/api/',
				array(
					'q' => $q
				)
			);
			
			$xml = simplexml_load_string($response->body);
			$geo = json_decode(json_encode($xml));
			
			$this->request->data['Venue']['lat'] = $geo->coordinate->lat;
			$this->request->data['Venue']['lng'] = $geo->coordinate->lng;
			
			if ( $this->Venue->save($this->request->data) )
			{
				$this->Session->setFlash('研究会場を作成しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('研究会場の作成に失敗しました。', 'Flash/error');
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
				$this->request->data['Venue']['name'] = $event['Event']['place'];
			}
		}
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}

	// edit
	public function edit( $id = null ) {
		$this->Venue->id = $id;
		if ( !$this->Venue->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$venue = $this->Venue->find('first', array(
			'contain' => array(
				
			),
			'conditions' => array(
				'Venue.id' => $id
			)
		));
		$this->set('venue', $venue);
		
		if ($this->request->is('post'))
		{
			if ( isset($this->request->data['take']) )
			{
				// 緯度・経度の更新を行う
				App::uses('HttpSocket', 'Network/Http');
				$HttpSocket = new HttpSocket();
				
				$q = trim($this->request->data['Venue']['city']) . ' ' . trim($this->request->data['Venue']['address']);
				
				$response = $HttpSocket->get(
					'http://www.geocoding.jp/api/',
					array(
						'q' => $q
					)
				);
				
				$xml = simplexml_load_string($response->body);
				$geo = json_decode(json_encode($xml));
				
				$this->request->data['Venue']['lat'] = $geo->coordinate->lat;
				$this->request->data['Venue']['lng'] = $geo->coordinate->lng;
			}
			
			$this->request->data['Venue']['id'] = $id;
			if ( $this->Venue->save($this->request->data) )
			{
				$this->Session->setFlash('研究会場を編集しました。', 'Flash/success');
				$this->redirect(array('action' => 'venue_list'));
			}
			else
			{
				$this->Session->setFlash('研究会場の編集に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $venue;
		}
		
		$prefectures = $this->Prefecture->find('list');
		$prefectures[0] = '------';
		ksort($prefectures);
		$this->set('prefectures', $prefectures);
	}
}
