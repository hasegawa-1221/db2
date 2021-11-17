<?php $this->assign('title', 'ログイン | 数理技術相談データベース'); ?>
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'login')); ?>
	<div class="container">
		<h2 class="text-center pb-2">企画編集・報告書ログイン</h2>
		<div class="row mb-3">
			<label for="EventUsername" class="col-sm-2 offset-sm-2 text-right">ログインID</label>
			<?php echo $this->Form->input('Event.username', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-sm-4')); ?>
		</div>
		<div class="row mb-3">
			<label for="EventPassword" class="col-sm-2 offset-sm-2 text-right">パスワード</label>
			<?php echo $this->Form->input('Event.password', array('type' => 'password', 'div' => false, 'label' => false, 'class' => 'form-control col-sm-4')); ?>
		</div>
	</div>
	<div class="text-center">
		<?php echo $this->Form->submit('ログイン', array('div' => false, 'class' => 'btn btn-lg btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>