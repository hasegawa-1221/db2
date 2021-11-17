		
		<h3><?php echo $event['Event']['title']; ?><small>（<?php echo $event['Event']['event_number']; ?>）</small></h3>
		
		
		<div class="row pt-3">
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 管理',			array('action' => 'edit', $event['Event']['id']), array('escape' => false)); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 報告書の概要',	array('action' => 'report_add1', $event['Event']['id']), array('escape' => false)); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 報告書の詳細',	array('action' => 'report_add2', $event['Event']['id']), array('escape' => false)); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 添付ファイル',	array('action' => 'report_add3', $event['Event']['id']), array('escape' => false)); ?>
			</div>
		</div>