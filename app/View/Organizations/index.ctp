<h2>研究機関データベース</h2>

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
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '研究機関名')); ?>
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

<?php if ( !empty($affiliations) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<table class="table table-bordered">
		<thead class="thead-light">
			<tr>
				<th>並び順</th>
				<th>名称</th>
				<th>住所</th>
				<th>作成日</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $affiliations as $affiliation ): ?>
				<?php
				$class = '';
				if ( $affiliation['Affiliation']['is_delete'] == 1 )
				{
					$class = ' class="is_delete"';
				}
				?>
				<tr<?php echo $class; ?>>
					<td><?php echo $affiliation['Affiliation']['id']; ?></td>
					<td><?php echo $affiliation['Affiliation']['name']; ?></td>
					<td>
						<?php echo $affiliation['Affiliation']['zip']; ?>
						<?php echo $prefectures[$affiliation['Affiliation']['prefecture_id']]; ?>
						<?php echo $affiliation['Affiliation']['city']; ?>
						<?php echo $affiliation['Affiliation']['address']; ?>
					</td>
					<td><?php echo $affiliation['Affiliation']['created']; ?></td>
					<td><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('controller' => 'affiliations', 'action' => 'edit', $affiliation['Affiliation']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>