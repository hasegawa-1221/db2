<?php
App::uses('AppController', 'Controller');
class CasesController extends AppController {

	public $uses = array('ResearchCase');

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
			$this->request->data['Search']['is_display']		= '';
		}
		
		$conditions = array();
		$conditions[] = array('ResearchCase.is_delete' => 0);
		
		
		// キーワード
		if ( isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword']) )
		{
			$conditions[] = array(
				'OR' => array(
					0 => array('ResearchCase.title LIKE'	=> '%' . trim($this->request->data['Search']['keyword']) . '%'),
					1 => array('ResearchCase.body LIKE'			=> '%' . trim($this->request->data['Search']['keyword']) . '%'),
					1 => array('ResearchCase.keyword LIKE'			=> '%' . trim($this->request->data['Search']['keyword']) . '%')
				)
			);
		}
		
		// HPに表示
		if ( isset($this->request->data['Search']['is_display']) && !empty($this->request->data['Search']['is_display']) )
		{
			$conditions[] = array('ResearchCase.is_display'	=> $this->request->data['Search']['is_display']);
		}
		
		$this->modelClass = "ResearchCase";
		$this->paginate = array(
			'contain' => array(
			),
			'conditions' => $conditions,
		);
		$cases = $this->paginate();
		$this->set('cases', $cases);
	}

	// 研究事例データの作成
	public function add ()
	{
		if ( $this->request->is('post') )
		{
			$this->request->data['ResearchCase']['admin_id'] = $this->Auth->user('id');
			$this->request->data['ResearchCase']['latest_admin_id'] = $this->Auth->user('id');
			if ( $this->ResearchCase->save($this->request->data) )
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
			
		}
	}

	// 研究事例データの編集
	public function edit ($id = null)
	{
		$this->ResearchCase->id = $id;
		if ( !$this->ResearchCase->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$case = $this->ResearchCase->find('first', array(
			'contain' => array(
			),
			'conditions' => array(
				'ResearchCase.id' => $id
			)
		));
		$this->set('case', $case);
		
		if ( $this->request->is('post') || $this->request->is('put') )
		{
			$this->request->data['ResearchCase']['id'] = $case['ResearchCase']['id'];
			$this->request->data['ResearchCase']['latest_admin_id'] = $this->Auth->user('id');
			
			if ( $this->ResearchCase->save($this->request->data) )
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
			$this->request->data = $case;
		}
	}
	
	// 添付ファイル削除
	public function file_delete($id = null)
	{
		if ( empty($id) )
		{
			$this->Session->setFlash('Invalid ID', 'Flash/error');
			$this->redirect(array('action' => 'index'));
		}
		
		$case = $this->ResearchCase->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'ResearchCase.id' => $id
			)
		));
		
		$rollback = false;
		$file = WWW_ROOT . 'files' . DS . 'research_case' . DS . 'file' . DS . $case['ResearchCase']['id'] . DS . $case['ResearchCase']['file'];
		
		if ( !@unlink($file) )
		{
			$rollback = true;
			$this->Session->setFlash('添付ファイルの削除に失敗しました。管理者にお問合わせください。', 'Flash/error');
		}
		
		if ( !$rollback )
		{
			$case['ResearchCase']['file']		= null;
			$case['ResearchCase']['file_dir']	= null;
			$case['ResearchCase']['file_org']	= null;
			$case['ResearchCase']['latest_admin_id']	= $this->Auth->user('id');
			$case['ResearchCase']['modified']	= date('Y-m-d H:i:s');
			
			if ( !$this->ResearchCase->save($case) )
			{
				$this->Session->setFlash('添付ファイルの削除に失敗しました。管理者にお問合わせください。', 'Flash/error');
			}
			else
			{
				$this->Session->setFlash('添付ファイルを削除しました。', 'Flash/success');
			}
		}
		
		$this->redirect(array('action' => 'edit', $id));
	}
	
	public function export() {
		
		$conditions = array(
			'ResearchCase.is_display' => 1,
			'ResearchCase.is_delete' => 0,
		);
		$cases = $this->ResearchCase->find('all', array(
			'contain' => array(
				
			),
			'conditions' => $conditions,
		));
		
		$ret = '';
		$ret .= '<table class="list-table">';
		foreach ( $cases as $case )
		{
			$ret .= '<tr>' . "\r\n";
				$ret .= '<td>';
					$ret .= '<strong>' . $case['ResearchCase']['title'] . '</strong><br>';
					$ret .= $case['ResearchCase']['researcher']. '<br>';
					$ret .= 'キーワード:' . $case['ResearchCase']['keyword']. '<br>';
				$ret .= '</td>';
				$ret .= '<td>' . $case['ResearchCase']['body'] . '</td>';
				$ret .= '<td><a href="' . '/db2/app/webroot/files/research_case/file/' . $case['ResearchCase']['file_dir'] . '/' . $case['ResearchCase']['file'] . '" target="_blank">ファイル</a></td>';
			$ret .= '</tr>' . "\r\n";
		}
		$ret .= '</table>';
		
		
		print_a($ret);
		print_a_die($cases);
	}
	
	public function post_case( $id = null ) {
		

		$this->ResearchCase->id = $id;
		if ( !$this->ResearchCase->exists() )
		{
			throw new Exception('Invalid id');
		}
		
		$case = $this->ResearchCase->find('first', array(
			'contain' => array(
			),
			'conditions' => array(
				'ResearchCase.id' => $id
			)
		));
		
		Configure::write('debug', 1);
		
		//$url = 'https://aimap.imi.kyushu-u.ac.jp/wp/wp-json/wp/v2/pages';
		$url = 'https://lab4.kijima-p.co.jp/wp/wp-json/wp/v2/pages';
		
		$username = 'kijima';
		$password = 'DPSQ&*H!Z%h(vJgV';
		$title = '記事タイトル';
		$content = '記事本文';
		
		App::uses( 'HttpSocket', 'Network/Http');
		$HttpSocket = new HttpSocket( array( 'ssl_verify_host' => false ));
		$params = array(
			'headers' => array(
				'Authorization' => 'OAuth ' . base64_encode( $username.':'.$password )
			),
			'body' => array(
				'title'   => $title,
				'status'  => 'draft',
				'content' => $content,
				'slug' => $title
			)
		);
		$jresponse = $HttpSocket->post( $url, $params);
		
		$response = json_decode($jresponse['body']);
		
		print_a($response);
		
		
		die();
		
		$url = 'https://aimap.imi.kyushu-u.ac.jp/wp/v2/pages';
		$username = 'xxxxx';
		$password = 'xxxx xxxx xxxx xxxx xxxx xxxx';

		$title = '記事タイトル';
		$content = '記事本文';
		$category_id = [1];
		$tag_id = [1];
		$slug = 'slug';

		$response = wp_remote_post( $url, array(
		'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( $username.':'.$password )),
			'body' => array(
				'title'   => $title,
				'status'  => 'draft',
				'content' => $content,
				'categories' => $category_id,
				'tags' => $tag_id,
				'slug' => $slug
			)
		));

		$response = $response['body'];
	}
}
