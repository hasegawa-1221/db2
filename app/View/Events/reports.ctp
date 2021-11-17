<h2>講演課題用データ</h2>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'reports')); ?>
			<div class="row pb-4">
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">ｷｰﾜｰﾄﾞ</div>
						</div>
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '企画番号・タイトルなど')); ?>
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
	「管理」リンク内の「HPに表示する」にチェックを付けることでHPに表示されます。
</p>

<?php if ( !empty($events) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tr>
				<th><?php echo $this->Paginator->sort('Event.id',			'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.event_number',	'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.title',		'企画タイトル'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.program',		'プログラム'); ?></th>
				<th colspan="2">編集</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $events as $event ): ?>
				<tr>
					<td><?php echo $event['Event']['id']; ?></td>
					<td><?php echo $this->Html->link( $event['Event']['event_number'], array('controler' => 'events', 'action' => 'edit', $event['Event']['id'])); ?></td>
					<td><?php echo $event['Event']['title']; ?></td>
					<td class="w-50"><?php echo $this->Text->truncate($event['Event']['program'], 100); ?></td>
					<td><?php echo $this->Html->link('編集',	array('action' => 'report_add2', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('講演課題作成',	array('action' => 'event_program_add', $event['Event']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>