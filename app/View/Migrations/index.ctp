<h2>数学カタログ</h2>
<hr>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('action' => 'add'), array('escape' => false, 'class' => 'btn btn-lg btn-danger')); ?>
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
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => 'タイトル')); ?>
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
	HPに表示中のデータ一覧です。
</p>

<?php if ( !empty($migrations) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<table class="table table-bordered">
		<thead class="thead-light">
			<tr>
				<th>DB-ID</th>
				<th>タイトル</th>
				<th>HPに表示</th>
				<th>作成日</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $migrations as $migration ): ?>
				<?php
				$class = '';
				if ( $migration['Migration']['is_delete'] == 1 )
				{
					$class = ' class="is_delete"';
				}
				?>
				<tr<?php echo $class; ?>>
					<td><?php echo $migration['Migration']['id']; ?></td>
					<td><?php echo $migration['Migration']['title']; ?></td>
					<td><?php echo $this->Display->is_true($migration['Migration']['is_display']); ?></td>
					<td><?php echo $migration['Migration']['created']; ?></td>
					<td><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('controller' => 'migrations', 'action' => 'add', $migration['Migration']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>