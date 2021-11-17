<?php $this->assign('title', 'ログイン'); ?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'login')); ?>
	<div class="container">
		<div class="row mb-3">
			<label for="UserUsername" class="col-sm-3 text-right">ユーザー名</label>
			<?php echo $this->Form->input('Admin.username', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-sm-7')); ?>
		</div>
		<div class="row mb-3">
			<label for="UserPassword" class="col-sm-3 text-right">パスワード</label>
			<?php echo $this->Form->input('Admin.password', array('type' => 'password', 'div' => false, 'label' => false, 'class' => 'form-control col-sm-7')); ?>
		</div>
	</div>
	<div class="text-center">
		<?php echo $this->Form->submit('ログイン', array('div' => false, 'class' => 'btn btn-lg btn-secondary')); ?>
	</div>
<?php echo $this->Form->end(); ?>