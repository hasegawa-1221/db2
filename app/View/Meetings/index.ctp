<h2>研究集会データベース</h2>
<hr>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('controller' => 'meetings', 'action' => 'add'), array('escape' => false, 'class' => 'btn btn-lg btn-danger')); ?>
</div>
<br>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'index')); ?>
			<div class="row pb-4">
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
				<div class="col-2">
					<?php echo $this->Form->input('Search.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;HPに表示のみ')); ?>
				</div>
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('検索する', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>

<?php if ( !empty($meetings) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Meeting.id',					'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Meeting.event_number',		'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('Meeting.title',				'企画タイトル'); ?></th>
				<th><?php echo $this->Paginator->sort('Meeting.start',				'実施日'); ?></th>
				<th><?php echo $this->Paginator->sort('Meeting.status',				'HPに表示'); ?></th>
				<th><?php echo $this->Paginator->sort('Meeting.created',			'データ作成日'); ?></th>
				<th>編集</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $meetings as $meeting ): ?>
				<tr>
					<td><?php echo $meeting['Meeting']['id']; ?></td>
					<td><?php echo $meeting['Meeting']['event_number']; ?></td>
					<td><?php echo $meeting['Meeting']['title']; ?></td>
					<td><?php echo date('Y/m/d', strtotime($meeting['Meeting']['start'])); ?>～<?php echo date('Y/m/d', strtotime($meeting['Meeting']['end'])); ?></td>
					<td class="text-center"><?php echo $this->Display->is_true($meeting['Meeting']['is_display']); ?></td>
					<td><?php echo date('Y/m/d', strtotime($meeting['Meeting']['created'])); ?></td>
					<td><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',	array('controller' => 'meetings', 'action' => 'edit', $meeting['Meeting']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>