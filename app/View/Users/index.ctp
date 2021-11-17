<h2>使用者一覧</h2>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('controller' => 'users', 'action' => 'add'), array('class' => 'btn btn-lg btn-danger')); ?>
</div>
<br>
<?php if ( !empty($users) ): ?>
	<table class="table table-bordered">
		<thead class="thead-light">
			<tr>
				<th>ID</th>
				<th>所属</th>
				<th>氏名</th>
				<th>かな</th>
				<th>研究者</th>
				<th>HPに表示</th>
				<th>作成日</th>
				<th>更新日</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $users as $user ): ?>
				<?php
				$class = '';
				if ( $user['User']['is_delete'] == 1 )
				{
					$class = ' class="is_delete"';
				}
				?>
				<tr<?php echo $class; ?>>
					<td><?php echo $user['User']['id']; ?></td>
					<td><?php echo $user['Affiliation']['name']; ?></td>
					<td><?php echo $user['User']['lastname']; ?> <?php echo $user['User']['firstname']; ?></td>
					<td><?php echo $user['User']['lastname_kana']; ?> <?php echo $user['User']['firstname_kana']; ?></td>
					<td><?php echo $this->Display->is_true($user['User']['is_researcher']); ?></td>
					<td><?php echo $this->Display->is_true($user['User']['is_display']); ?></td>
					<td><?php echo $user['User']['created']; ?></td>
					<td><?php echo $user['User']['modified']; ?></td>
					<td><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('action' => 'edit', $user['User']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>