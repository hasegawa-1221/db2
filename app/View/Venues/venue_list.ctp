<h2>開催場所/研究会場一覧</h2>
<div class="text-right">
	<?php echo $this->Html->link('新規登録', array('controller' => 'venues', 'action' => 'add'), array('class' => 'btn btn-lg btn-danger')); ?>
</div>
<br>
<?php if ( !empty($venues) ): ?>
	<table class="table table-bordered">
		<thead class="thead-light">
			<tr>
				<th>並び順</th>
				<th>名称</th>
				<th>住所</th>
				<th>研究会場</th>
				<th>作成日</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $venues as $venue ): ?>
				<?php
				$class = '';
				if ( $venue['Venue']['is_delete'] == 1 )
				{
					$class = ' class="is_delete"';
				}
				?>
				<tr<?php echo $class; ?>>
					<td><?php echo $venue['Venue']['id']; ?></td>
					<td><?php echo $venue['Venue']['name']; ?></td>
					<td>
						<?php echo $venue['Venue']['zip']; ?>
						<?php echo $prefectures[$venue['Venue']['prefecture_id']]; ?>
						<?php echo $venue['Venue']['city']; ?>
						<?php echo $venue['Venue']['address']; ?>
					</td>
					<td>
						<?php echo $this->Display->is_true($venue['Venue']['is_display']); ?>
					</td>
					<td><?php echo $venue['Venue']['created']; ?></td>
					<td><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('action' => 'edit', $venue['Venue']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>