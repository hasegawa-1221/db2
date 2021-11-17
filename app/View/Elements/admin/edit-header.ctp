		
		<h3><?php echo $event['Event']['title']; ?><small>（<?php echo $event['Event']['event_number']; ?>）</small></h3>
		
		
		<div class="row pt-3">
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 管理',			array('action' => 'edit', $event['Event']['id']), array('escape' => false)); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 企画の概要',	array('action' => 'edit1', $event['Event']['id']), array('escape' => false)); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 企画の詳細',	array('action' => 'edit2', $event['Event']['id']), array('escape' => false)); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 経費',			array('action' => 'edit3', $event['Event']['id']), array('escape' => false)); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 参加について',	array('action' => 'edit4', $event['Event']['id']), array('escape' => false)); ?>
			</div>
			<div class="col-12 col-sm-6 col-md-2">
				<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 責任者',		array('action' => 'edit5', $event['Event']['id']), array('escape' => false)); ?>
			</div>
		</div>