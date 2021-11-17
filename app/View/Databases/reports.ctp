<?php
$this->assign('title', '講演課題データベース | 数理技術相談データベース');

// パンくずリスト設定
$this->Html->addCrumb('講演課題データベース', '/databases/reports/');
?>
<h2>講演課題データベース</h2>
<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'reports')); ?>
			<div class="row pb-4">
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">講演課題</div>
						</div>
						<?php echo $this->Form->input('Search.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">講演者所属</div>
						</div>
						<?php echo $this->Form->input('Search.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">講演者名</div>
						</div>
						<?php echo $this->Form->input('Search.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control ', 'placeholder' => '姓')); ?>
						
						<?php echo $this->Form->input('Search.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control ', 'placeholder' => '名')); ?>
					</div>
				</div>
			</div>
			<div class="row pb-4">
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">企画番号</div>
						</div>
						<?php echo $this->Form->input('Search.event_number', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">研究集会</div>
						</div>
						<?php echo $this->Form->input('Search.report_title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-affiliation', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">主催機関</div>
						</div>
						<?php echo $this->Form->input('Search.report_organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-affiliation', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">開催場所</div>
						</div>
						<?php echo $this->Form->input('Search.report_place', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('検索する', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>
<div class="container">
	<?php if ( !empty($event_programs) ): ?>
		<?php echo $this->Element('paginate'); ?>
		<?php foreach ( $event_programs as $event_program ): ?>
			<div class="alert mb-2">
				<div class="row">
					<div class="col-12">
						<h5><?php echo $this->Html->link($event_program['EventProgram']['title'], array('controller' => 'databases', 'action' => 'report_detail', $event_program['EventProgram']['id'])); ?></h5>
					</div>
					<div class="col-12">
						<?php if ( $event_program['EventPerformer'] ): ?>
							<?php $i= 1; ?>
							<?php foreach ( $event_program['EventPerformer'] as $event_performer ): ?>
								<div class="row ml-2 mb-2">
									<div class="col-12">
										講演者<?php echo $i; ?>：
										<?php echo $event_performer['organization']; ?>&nbsp;
										<?php echo $event_performer['role']; ?>&nbsp;
										<?php echo $event_performer['lastname']; ?>&nbsp;
										<?php echo $event_performer['firstname']; ?>
									</div>
								</div>
								<?php $i++; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						<div class="row ml-2 pt-2">
							<div class="col-12">
								<?php if ( isset($event_program['Meeting'] )  && $event_program['EventProgram']['event_id'] > 0 ): ?>
									■研究集会情報<br>
									<?php echo $this->Html->link($event_program['Meeting']['event_number'] . ' ' .$event_program['Meeting']['title'], array('action' => 'meeting_detail', $event_program['Meeting']['id']), array()); ?><br>
									主催機関：<?php echo $event_program['Meeting']['organization']; ?><br>
									開催場所：<?php echo $event_program['Meeting']['place']; ?><br>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<br>
		<?php endforeach; ?>
		<?php echo $this->Element('paginate'); ?>
	<?php else: ?>
		<p class="alert alert-warning">データ見つかりませんでした。</a>
	<?php endif; ?>
</div>