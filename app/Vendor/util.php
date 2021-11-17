<?php
class Util {
	
	public function guid()
	{
		if (function_exists('com_create_guid') === true)
		{
			return trim(com_create_guid(), '{}');
		}
		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
	
	// ドロップダウンリスト用のデータにデフォルトの選択肢を追加
	// @data array ドロップダウン用データ array(1 => 'あああ', 2 => 'いいい')
	// @str string 表示する文言
	public function set_dropdown_default($data = array(), $str = ''){
		// 表示文言の指定がない場合
		if ( empty($str) )
		{
			$str = '選択して下さい';
		}
		array_unshift($data, $str);
		return $data;
	}
}
?>