<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	function begin() {
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		$db->begin($this);
	}

	function commit() {
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		$db->commit($this);
	}

	function rollback() {
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		$db->rollback($this);
	}

	// ��扞�咆�Ƀy�[�W�ԍ����擾����
	// ���f���̃o���f�[�V�����Ŏg�p
	function _get_event_add_page ()
	{
		$page = '1';
		if (isset($_SESSION['page']) && !empty($_SESSION['page']))
		{
			$page = $_SESSION['page'];
		}
		return $page;
	}

	// cake�g���݂�alphaNumeric�o���f�[�V�����ɂ̓o�O������ׁA�����ŃI�[�o�[���C�h
	public function alphaNumeric($check) {
		$value = array_values($check);
		$value = $value[0];
		return preg_match('/^[a-zA-Z0-9]+$/', $value);
	}

	// �N�x���擾
	public function get_fiscal_year()
	{
		$year = date('Y');
		$month = date('n');
		
		// 1,2,3���͌��݂̔N���1������
		switch ($month)
		{
			case 1:
				$ret = $year - 1;
				break;
			case 2:
				$ret = $year - 1;
				break;
			case 3:
				$ret = $year - 1;
				break;
			default:
				$ret = $year;
				break;
		}
		return $ret;
	}
}
