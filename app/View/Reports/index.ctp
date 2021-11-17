<h2>講演課題データベース</h2>
<hr>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('controller' => 'reports', 'action' => 'add'), array('escape' => false, 'class' => 'btn btn-lg btn-danger')); ?>
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
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('検索する', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>

<?php if ( !empty($event_programs) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tr>
				<th><?php echo $this->Paginator->sort('EventProgram.id',			'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.event_number',			'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('EventProgram.date',			'並び順'); ?></th>
				<th><?php echo $this->Paginator->sort('EventProgram.title',			'講演タイトル'); ?></th>
				<th>講演者</th>
				<th><?php echo $this->Paginator->sort('EventProgram.is_display',	'HPに表示'); ?></th>
				<th>備考</th>
				<th colspan="2">管理</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $event_programs as $event_program ): ?>
				<tr>
					<td><?php echo $event_program['EventProgram']['id']; ?></td>
					<td><?php echo $this->Html->link( $event_program['Event']['event_number'], array('controler' => 'events', 'action' => 'edit', $event_program['Event']['id'])); ?></td>
					<td><?php echo $event_program['EventProgram']['sort']; ?></td>
					<td><?php echo $event_program['EventProgram']['title']; ?></td>
					<td>
						<?php
						if ( !empty($event_program['EventPerformer']) )
						{
							foreach ( $event_program['EventPerformer'] as $event_performer )
							{
								if ( empty($event_performer['organization']) 
									&& empty($event_performer['role'])
									&& empty($event_performer['lastname'])
									&& empty($event_performer['firstname']) )
								{
									
								}
								else
								{
									echo '・';
									echo $event_performer['organization'] . '&nbsp;';
									echo $event_performer['role'] . '&nbsp;';
									echo $event_performer['lastname'] . '&nbsp;';
									echo $event_performer['firstname'];
									echo '<br>';
								}
							}
						}
						?>
					</td>
					<td><?php echo $this->Display->is_true($event_program['EventProgram']['is_display']); ?></td>
					<td>
						<?php
						if ( empty($event_program['Meeting']['id']) )
						{
							echo '<span class="text-danger">';
								echo '対応する研究集会がありません。';
							echo '</span>';
						}
						?>
					</td>
					<td><?php echo $this->Html->link('管理',	array('controller' => 'reports', 'action' => 'edit', $event_program['EventProgram']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('編集',	array('controller' => 'events', 'action' => 'event_program_add', $event_program['Event']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>