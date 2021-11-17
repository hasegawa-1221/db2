<?php
App::uses('AppController', 'Controller');
class DashboardsController extends AppController {

	public $uses = array();

	public function index() {
		
	}

	// �N��n���΍��v���Ԃ��Ă����
	public function term_total ($year = ''){
		
		if ( empty($year) )
		{
			$year = date('Y');
		}
		
		// �N�̊J�n�ƏI����
		$start	= $year . '-01-01';
		$end	= $year . '-12-31';
		
		$ret = 0;
		$all = $this->Accident->find('all', array(
			'contain' => array(),
			'fields' => array(
				'SUM(Accident.expense) AS total_expense'
			),
			'conditions' => array(
				'Accident.date >= ?' => $start,
				'Accident.date <= ?' => $end,
			),
			'order' => 'Accident.date ASC'
		));
		
		if ( !empty($all) && is_array($all) && isset($all[0][0]['total_expense']) )
		{
			$ret = $all[0][0]['total_expense'];
		}
		
		return $ret;
	}
}
