
<?php
App::uses('AppController', 'Controller');
class ItemsController extends AppController {

	public $uses = array('Item');

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	// 一覧
	public function index()
	{
		// 課目を全て取得
		$items = $this->Item->find('all', array(
			'contain'		=> array('Children'),
			'conditions'	=> array(
				'Item.parent_id' => 0
			)
		));
		$this->set('items', $items);
		
		//print_a_die($items);
		
	}

	// 課目追加
	public function add( $id = null ) {
		
		$parent = array();
		if ( !empty($id) )
		{
			$parent = $this->Item->find('first', array('contain' => array(), 'conditions' => array('Item.is_delete' => 0, 'Item.id'=> $id)));
		}
		
		if ($this->request->is('post'))
		{
			if ( $this->Item->save($this->request->data) )
			{
				$this->Session->setFlash('課目を作成しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('課目の作成に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			if ( !empty($parent) )
			{
				$this->request->data['Item']['parent_id'] = $parent['Item']['id'];
			}
		}
		
		
		$parents = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.parent_id' => 0)));
		$parents[0] = 'なし';
		ksort($parents);
		$this->set('parents', $parents);
	}

	// 課目編集
	public function edit($id = null) {
		
		$this->Item->id = $id;
		if ( !$this->Item->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$item = $this->Item->find('first', array(
			'contain' => array(
			),
			'conditions' => array(
				'Item.id' => $id
			)
		));
		$this->set('item', $item);
		
		
		if ($this->request->is('post'))
		{
			$this->request->data['Item']['id'] = $item['Item']['id'];
			if ( $this->Item->save($this->request->data) )
			{
				$this->Session->setFlash('課目を更新しました。', 'Flash/success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash('課目の更新に失敗しました。', 'Flash/error');
			}
		}
		else
		{
			$this->request->data = $item;
		}
		
		$parents = $this->Item->find('list', array('contain' => array(), 'conditions' => array('Item.parent_id' => 0)));
		$parents[0] = 'なし';
		ksort($parents);
		$this->set('parents', $parents);
	}




	
}
