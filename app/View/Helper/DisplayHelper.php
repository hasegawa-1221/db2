<?php
App::uses('Helper', 'View');
App::uses('HtmlHelper', 'View/Helper');
class DisplayHelper extends AppHelper {
	
	public $helpers = array('Html');
	
	public function is_true ( $is_true )
	{
		$ret = '';
		if ( $is_true == 1 )
		{
			$ret = '<i class="fa fa-check-circle-o text-success" aria-hidden="true"></i>';
		}
		return $ret;
	}

	public function event_manager_title ( $type )
	{
		switch ( $type )
		{
			case 1:
				$ret = '運営責任者';
				break;
			case 2:
				$ret = 'その他の運営責任者';
				break;
			case 3:
				$ret = '事務担当者';
				break;
			default:
				$ret = '';
		}
		return $ret;
	}

	public function get_event_type ( $type = 0 )
	{
		switch ( $type )
		{
			case 1:
				$ret = '公募';
				break;
			case 2:
				$ret = '日本数学会';
				break;
			case 3:
				$ret = '九州大学';
				break;
			default:
				$ret = '';
		}
		return $ret;
	}

	public function get_event_status ( $status = 0 )
	{
		switch ( $status )
		{
			case 0:
				$ret = '<span class="badge badge-danger">未申請</span>';
				break;
			case 1:
				$ret = '<span class="badge badge-warning">未確定</span>';
				break;
			case 2:
				$ret = '<span class="badge badge-success">確定</span>';
				break;
			default:
				$ret = '<span class="badge badge-secondary">----</span>';
		}
		return $ret;
	}

	public function rm_date_format ( $yyyymmdd = '' )
	{
		if ( strlen($yyyymmdd) != 8 )
		{
			return $yyyymmdd;
		}
		
		$yyyy	= (int)substr($yyyymmdd, 0, 4);
		$mm		= (int)substr($yyyymmdd, 4, 2);
		$dd		= (int)substr($yyyymmdd, 6, 2);
		
		$ret = '';
		if ( !empty($yyyy) )
		{
			if ( $yyyy == 9999 )
			{
				// 9999は現在
				return '';
			}
			$ret .= $yyyy . '年';
		}
		if ( !empty($mm) )
		{
			$ret .= $mm . '月';
		}
		if ( !empty($dd) )
		{
			$ret .= $dd . '日';
		}
		return $ret;
	}
	
	/*
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
	public function reseacher_detail_title($type = null)
	{
		if ( empty($type) )
		{
			return '';
		}
		
		$types = Configure::read('App.researcher_detail_type');
		return $types[$type];
	}
	
	/*
	1 => 'ResearcherResearchKeyword',
	2 => 'ResearcherResearchArea',
	3 => 'ResearcherCareer',
	4 => 'ResearcherAcademicBackground',
	5 => 'ResearcherCommitteeCareer',
	6 => 'ResearcherPrize',
	7 => 'ResearcherPaper',
	8 => 'ResearcherBiblio',
	9 => 'ResearcherConference',
	10 => 'ResearcherTeachingExperience',
	11 => 'ResearcherAcademicSociety',
	12 => 'ResearcherCompetitiveFund',
	13 => 'ResearcherPatent',
	14 => 'ResearcherSocialContribution',
	15 => 'ResearcherOther'
	*/
	public function reseacher_detail_table($type = null)
	{
		if ( empty($type) )
		{
			return '';
		}
		
		$tables = Configure::read('App.researcher_detail_table');
		return $tables[$type];
	}
	
	public function researcher_detail_column( $type = null )
	{
		if ( empty($type) )
		{
			return '';
		}
		
		$cols = array();
		
		// 1 : 研究キーワード
		if ( $type == 1 )
		{
			$cols = array(
				'title'			=> 'キーワード',
				'author'		=> '研究者名',
				'link'			=> '研究者検索へリンク',
				'is_delete'		=> '削除フラグ',
			);
		}
		// 2 : 研究分野
		else if ( $type == 2 )
		{
			$cols = array(
				'title'			=> '大分類＋中分類',
				'author'		=> '研究者名',
				'link'			=> '研究者検索へリンク',
				'field_id'		=> '大分類ID',
				'field_name'	=> '大分類名称',
				'subject_id'	=> '中分類ID',
				'subject_name'	=> '中分類名称',
				'is_delete'		=> '削除フラグ',
			);
		}
		// 3 : 経歴
		else if ( $type == 3 )
		{
			$cols = array(
				'title'			=> '所属＋部署＋ 職位・身分',
				'author'		=> '研究者名',
				'link'			=> 'マイポータルリンク',
				'from_date'		=> '年月(From)',
				'to_date'		=> '年月(To)',
				'affiliation'	=> '所属',
				'section'		=> '部署',
				'job'			=> '職位・身分',
				'is_delete'		=> '削除フラグ',
			);
		}
		// 4 : 学歴
		else if ( $type == 4 )
		{
			$cols = array(
				'title'				=> '学校名',
				'author'			=> '研究者名',
				'link'				=> 'マイポータルリンク',
				'department_name'	=> '学部名',
				'subject_name'		=> '学科名',
				'country'			=> '国名',
				'from_date'			=> '年月(From)',
				'to_date'			=> '年月(To)',
				'is_delete'			=> '削除フラグ',
			);
		}
		// 5 : 委員歴
		else if ( $type == 5 )
		{
			$cols = array(
				'title'					=> '委員名',
				'author'				=> '研究者名',
				'link'					=> 'マイポータルリンク',
				'from_date'				=> '期間(From)',
				'to_date'				=> '期間(To)',
				'association'			=> '団体名',
				'committee_type_id'		=> '区分ID',
				'committee_type_name'	=> '区分名称',
				'summary'				=> '特記事項',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 6 : 受賞
		else if ( $type == 6 )
		{
			$cols = array(
				'title'					=> '受賞',
				'author'				=> '研究者名',
				'link'					=> 'マイポータルリンク',
				'summary'				=> '説明',
				'publication_date'		=> '受賞年月',
				'association'			=> '授与機関',
				'subtitle'				=> 'タイトル',
				'partner'				=> '受賞者(グループ)',
				'prize_type_id'			=> '受賞区分コード',
				'prize_type_name'		=> '受賞区分名称',
				'country'				=> '受賞国',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 7 : 論文
		else if ( $type == 7 )
		{
			$cols = array(
				'title'					=> 'タイトル',
				'author'				=> '著者',
				'link'					=> 'リンク',
				'summary'				=> '概要',
				'journal'				=> '誌名',
				'publisher'				=> '出版者',
				'publication_name'		=> '出版物名',
				'volume'				=> '巻',
				'number'				=> '号',
				'starting_page'			=> '開始ページ',
				'ending_page'			=> '終了ページ',
				'publication_date'		=> '出版年月',
				'referee'				=> '査読の有無',
				'invited'				=> '招待論文',
				'language'				=> '記述言語',
				'paper_type_id'			=> '掲載種別値',
				'paper_type_name'		=> '掲載種別名称',
				'issn'					=> 'ISSN',
				'doi'					=> 'ID:DOI',
				'naid'					=> 'ID:NAID(CiNiiのID)',
				'pmid'					=> 'ID:PMID',
				'permalink'				=> 'Permalink',
				'url'					=> 'URL',
				'nrid'					=> '研究者リゾルバーID',
				'jglobalid'				=> 'J-Global ID',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 8 : 書籍等出版物
		else if ( $type == 8 )
		{
			$cols = array(
				'title'					=> 'タイトル',
				'author'				=> '著者',
				'link'					=> 'リンク',
				'summary'				=> '概要',
				'publisher'				=> '出版社',
				'publication_date'		=> '出版年月',
				'total_page_number'		=> '総ページ数',
				'rep_page_number'		=> '担当ページ',
				'amount'				=> '価格',
				'isbn'					=> 'ID:ISBN',
				'asin'					=> 'ID:ASIN',
				'author_type_id'		=> '役割値',
				'author_type_name'		=> '役割名称',
				'part_area'				=> '担当範囲',
				'amazon_url'			=> 'Amazon URL',
				'small_image_url'		=> 'Amazon画像リンク(小)',
				'medium_image_url'		=> 'Amazon画像リンク(中)',
				'large_image_url'		=> 'Amazon画像リンク(大)',
				'language'				=> '記述言語',
				'biblio_type_id'		=> '著書種別値',
				'biblio_type_name'		=> '著書種別名称',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 9 : 講演・口頭発表等
		else if ( $type == 9 )
		{
			$cols = array(
				'title'					=> 'タイトル',
				'author'				=> '講演者',
				'link'					=> 'リンク',
				'summary'				=> '概要',
				'journal'				=> '会議名',
				'publication_date'		=> '開催年月',
				'invited'				=> '招待講演',
				'language'				=> '記述言語',
				'conference_class'		=> '会議区分',
				'conference_type_id'	=> '会議種別値',
				'conference_type_name'	=> '会議種別名称',
				'promoter'				=> '主催者',
				'venue'					=> '開催地',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 10: 担当経験のある科目
		else if ( $type == 10 )
		{
			$cols = array(
				'title'					=> '科目',
				'author'				=> '研究者名',
				'link'					=> 'リンク',
				'affiliation'			=> '機関名',
				'summaryid'				=> '集計ID',
				'count'					=> '登録件数',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 11: 所属学協会
		else if ( $type == 11 )
		{
			$cols = array(
				'title'					=> '所属学協会名',
				'author'				=> '研究者名',
				'link'					=> 'リンク',
				'summaryid'				=> '集計ID',
				'count'					=> '登録件数',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 12: 競争的資金等の研究課題
		else if ( $type == 12 )
		{
			$cols = array(
				'title'					=> 'タイトル',
				'author'				=> '代表者',
				'link'					=> 'リンク',
				'summary'				=> '研究概要',
				'provider'				=> '提供機関',
				'system'				=> '制度名',
				'from_date'				=> '研究期間(From)',
				'to_date'				=> '研究期間(To)',
				'member'				=> '連携研究者',
				'referee_type'			=> '審査区分',
				'field'					=> '研究分野',
				'category'				=> '研究種目',
				'grant_amount_total'	=> '配分額(総額)',
				'grant_amount_direct'	=> '配分額(直接経費)',
				'grant_amount_indirect'	=> '配分額(間接経費)',
				'researchid'			=> '研究課題番号',
				'institution'			=> '研究機関',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 13: 特許
		else if ( $type == 13 )
		{
			$cols = array(
				'title'							=> 'タイトル',
				'author'						=> '発明者',
				'link'							=> 'リンク',
				'summary'						=> '要約',
				'application_id'				=> '出願情報 出願番号',
				'application_application_date'	=> '出願情報 出願日',
				'public_id'						=> '公開情報 公開番号',
				'public_public_date'			=> '公開情報 出願日',
				'translation_id'				=> '公表情報 公表番号',
				'translation_translation_date'	=> '公表情報 公表日',
				'patent_id'						=> '特許情報 特許番号',
				'patent_patent_date'			=> '特許情報 発行日',
				'application_person'			=> '出願人（会社名など）',
				'jglobalid'						=> 'JGlobal ID',
				'is_delete'						=> '削除フラグ',
			);
		}
		// 14: 社会貢献活動
		else if ( $type == 14 )
		{
			$cols = array(
				'title'					=> '委員名',
				'author'				=> '研究者名',
				'link'					=> 'リンク',
				'summary'				=> '概要',
				'role_id'				=> '役割ID',
				'role_name'				=> '役割名称',
				'promoter'				=> '主催者',
				'event'					=> 'イベント名',
				'from_date'				=> '年月日(From)',
				'to_date'				=> '年月日(To)',
				'location'				=> '場所',
				'event_type_id'			=> '種別ID',
				'event_type_name'		=> '種別名称',
				'target_id'				=> '対象ID',
				'target_name'			=> '対象名称',
				'is_delete'				=> '削除フラグ',
			);
		}
		// 15: その他
		else if ( $type == 15 )
		{
			$cols = array(
				'title'					=> '内容',
				'author'				=> '研究者名',
				'link'					=> 'リンク',
				'summary'				=> '内容',
				'publication_date'		=> '実施年月',
				'is_delete'				=> '削除フラグ',
			);
		}

		return $cols;
	}
	
	//　数学カタログ
	public function get_migration_action($type = null)
	{
		if ( empty($type) )
		{
			return '';
		}
		switch ( $type )
		{
			case 1:
				$ret = 'migration_detail';
				break;
			case 2:
				$ret = 'researcher_detail';
				break;
			case 3:
				$ret = 'meeting_detail';
				break;
			case 4:
				$ret = 'report_detail';
				break;
			case 5:
				$ret = 'organization_detail';
				break;
			case 6:
				$ret = 'venue_detail';
				break;
			case 7:
				$ret = 'case_detail';
				break;
			default:
				$ret = 'index';
		}
		return $ret;
	}
	
	public function get_migration_col($type = null)
	{
		if ( empty($type) )
		{
			return '';
		}
		switch ( $type )
		{
			case 1:
				$ret = 'migration_id';
				break;
			case 2:
				$ret = 'researcher_id';
				break;
			case 3:
				$ret = 'event_id';
				break;
			case 4:
				$ret = 'event_program_id';
				break;
			case 5:
				$ret = 'affiliation_id';
				break;
			case 6:
				$ret = 'venue_id';
				break;
			case 7:
				$ret = 'case_id';
				break;
			default:
				$ret = '';
		}
		return $ret;
	}
	
	// Eventテーブル用
	public function file($event_file)
	{
		$ext = pathinfo( $event_file['file'], PATHINFO_EXTENSION );
		$images = array( 'jpg', 'png', 'gif' );
		
		// 拡張子が画像の場合
		if ( in_array( $ext, $images ) )
		{
			$url	= Configure::read('App.site_url') . 'files/event_file/file/' . $event_file['file_dir'] . '/' . $event_file['file'];
			$thumb	= Configure::read('App.site_url') . 'files/event_file/file/' . $event_file['file_dir'] . '/thumb150_' . $event_file['file'];
			
			$ret = '';
			$ret .= $this->Html->image($url, array('class' => 'lightbox'));
		}
		else
		{
			$url = Configure::read('App.site_url') . 'files/event_file/file/' . $event_file['file_dir'] . '/' . $event_file['file'];
			$ret = $this->Html->link($event_file['file_org'], $url, array('target' => '_blank'));
		}
		return $ret;
	}
	
	// 研究集会テーブル用
	public function file2($meeting_file)
	{
		$ext = pathinfo( $meeting_file['file'], PATHINFO_EXTENSION );
		$images = array( 'jpg', 'png', 'gif' );
		
		// 拡張子が画像の場合
		if ( in_array( $ext, $images ) )
		{
			$url	= Configure::read('App.site_url') . 'files/meeting_file/file/' . $meeting_file['file_dir'] . '/' . $meeting_file['file'];
			$thumb	= Configure::read('App.site_url') . 'files/meeting_file/file/' . $meeting_file['file_dir'] . '/thumb150_' . $meeting_file['file'];
			
			$ret = '';
			$ret .= $this->Html->image($url, array('class' => 'lightbox'));
		}
		else
		{
			$url = Configure::read('App.site_url') . 'files/meeting_file/file/' . $meeting_file['file_dir'] . '/' . $meeting_file['file'];
			$ret = $this->Html->link($meeting_file['file_org'], $url, array('target' => '_blank'));
		}
		return $ret;
	}
	
	// 研究事例テーブル用
	public function file3($case)
	{
		$ext = pathinfo( $case['ResearchCase']['file'], PATHINFO_EXTENSION );
		$images = array( 'jpg', 'png', 'gif' );
		
		// 拡張子が画像の場合
		if ( in_array( $ext, $images ) )
		{
			$url	= Configure::read('App.site_url') . 'files/research_case/file/' . $case['ResearchCase']['file_dir'] . '/' . $case['ResearchCase']['file'];
			
			$ret = '';
			$ret .= $this->Html->image($url, array('class' => 'lightbox'));
		}
		else
		{
			$url = Configure::read('App.site_url') . 'files/research_case/file/' . $case['ResearchCase']['file_dir'] . '/' . $case['ResearchCase']['file'];
			$ret = $this->Html->link($case['ResearchCase']['file_org'], $url, array('target' => '_blank'));
		}
		return $ret;
	}
}
