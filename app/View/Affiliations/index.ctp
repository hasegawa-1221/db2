<h2>所属機関/研究機関一覧</h2>
<div class="text-right">
	<?php echo $this->Html->link('新規登録', array('controller' => 'affiliations', 'action' => 'add'), array('class' => 'btn btn-lg btn-danger')); ?>
</div>
<br>
<?php if ( !empty($affiliations) ): ?>
	<table class="table table-bordered">
		<thead class="thead-light">
			<tr>
				<th>並び順</th>
				<th>名称</th>
				<th>住所</th>
				<th>研究機関</th>
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
					<td>
						<?php echo $this->Display->is_true($affiliation['Affiliation']['is_display']); ?>
					</td>
					<td><?php echo $affiliation['Affiliation']['created']; ?></td>
					<td><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('action' => 'edit', $affiliation['Affiliation']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>