<h2>研究会場用データ</h2>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'meetings')); ?>
			<div class="row pb-4">
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">ｷｰﾜｰﾄﾞ</div>
						</div>
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '会場名')); ?>
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
	開催場所右の <i class="fa fa-plus" aria-hidden="true"></i> をクリックすると、研究会場登録画面に遷移します。
</p>

<?php if ( !empty($events) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Event.id',					'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.event_number',			'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.organization',			'開催場所'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.created',				'データ作成日'); ?></th>
				<th>編集</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $events as $event ): ?>
				<tr>
					<td><?php echo $event['Event']['id']; ?></td>
					<td><?php echo $event['Event']['event_number']; ?></td>
					<td>
						<?php echo $event['Event']['place']; ?>
						<?php echo $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i>', array('controller' => 'venues', 'action' => 'add', $event['Event']['id']), array('escape' => false)); ?>
					</td>
					<td><?php echo date('Y/m/d', strtotime($event['Event']['created'])); ?></td>
					<td><?php echo $this->Html->link('管理',	array('action' => 'edit', $event['Event']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>