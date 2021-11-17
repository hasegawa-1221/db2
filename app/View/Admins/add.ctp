<h2>管理者の登録</h2>
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add')); ?>
	
	<div class="row">
		<div class="col-12 text-center">
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="AdminUsername" class="col-12 col-sm-3 text-right">アカウント</label>
					<?php echo $this->Form->input('Admin.username', array('type' => 'text', 'div' => false, 'label' => false, 'placeholder' => '半角英数', 'class' => 'form-control col-12 col-sm-6')); ?>
				</div>
			</div>
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserPassword" class="col-12 col-sm-3 text-right">パスワード</label>
					<?php echo $this->Form->input('Admin.password', array('type' => 'text', 'div' => false, 'label' => false, 'placeholder' => 'パスワード', 'class' => 'form-control col-12 col-sm-6')); ?>
				</div>
			</div>
			<hr>
			<div class="mb-3">
				<?php echo $this->Form->input('Admin.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => 'チェックを付けて削除とする')); ?>
			</div>
			<hr>
		</div>
	</div>
	<div class="text-center">
		<?php echo $this->Form->submit('登録する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>