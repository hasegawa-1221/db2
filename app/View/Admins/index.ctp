<h2>管理者一覧</h2>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('controller' => 'admins', 'action' => 'add'), array('class' => 'btn btn-lg btn-danger')); ?>
</div>
<br>
<?php if ( !empty($admins) ): ?>
	<table class="table table-bordered">
		<thead class="thead-light">
			<tr>
				<th>ID</th>
				<th>ログインID</th>
				<th>作成日</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $admins as $admin ): ?>
				<tr>
					<td><?php echo $admin['Admin']['id']; ?></td>
					<td><?php echo $admin['Admin']['username']; ?></td>
					<td><?php echo $admin['Admin']['created']; ?></td>
					<td><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('action' => 'edit', $admin['Admin']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>