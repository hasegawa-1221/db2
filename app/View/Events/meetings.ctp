<h2>研究集会用データ</h2>
<hr>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('controller' => 'meetings', 'action' => 'add'), array('escape' => false, 'class' => 'btn btn-lg btn-danger')); ?>
</div>
<br>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'meetings')); ?>
			<div class="row pb-4">
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">種別</div>
						</div>
						<?php echo $this->Form->input('Search.event_type', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $event_type)); ?>
					</div>
				</div>
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">ステータス</div>
						</div>
						<?php echo $this->Form->input('Search.status', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $event_status)); ?>
					</div>
				</div>
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">ｷｰﾜｰﾄﾞ</div>
						</div>
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '企画番号・タイトルなど')); ?>
					</div>
				</div>
				<div class="col-4 form-inline">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">開始日</div>
						</div>
						<?php echo $this->Form->input('Search.start', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control datepicker', 'placeholder' => '開始日')); ?>
					</div>
					&nbsp;～&nbsp;
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">終了日</div>
						</div>
						<?php echo $this->Form->input('Search.end', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control datepicker', 'placeholder' => '終了日')); ?>
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
<p class="alert alert-warning">
	ステータスが「報告書提出済み」の一覧が表示されます。<br>
	「研究集会データを作成」リンクより研究集会のデータ作成が可能です。
</p>
<?php if ( !empty($events) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Event.id',					'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.type',					'種別'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.event_number',			'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.title',				'企画タイトル'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.start',				'実施日'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.status',				'ステータス'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.created',				'データ作成日'); ?></th>
				<th>編集</th>
				<th>作成</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $events as $event ): ?>
				<tr>
					<td><?php echo $event['Event']['id']; ?></td>
					<td><?php echo $this->Display->get_event_type($event['Event']['type']); ?></td>
					<td><?php echo $event['Event']['event_number']; ?></td>
					<td><?php echo $this->Html->link($event['Event']['title'], '#', array('data-toggle' => 'modal', 'data-target' => '.bd-example-modal-lg-' . $event['Event']['id'])); ?></td>
					<td><?php echo date('Y/m/d', strtotime($event['Event']['start'])); ?>～<?php echo date('Y/m/d', strtotime($event['Event']['end'])); ?></td>
					<td><?php echo $event_status[$event['Event']['status']]; ?></td>
					<td><?php echo date('Y/m/d', strtotime($event['Event']['created'])); ?></td>
					<td><?php echo $this->Html->link('編集',	array('action' => 'edit', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('研究集会データを作成',	array('controller' => 'meetings', 'action' => 'add', $event['Event']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>