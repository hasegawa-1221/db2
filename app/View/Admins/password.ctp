<h2>パスワードの変更</h2>
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'password/' . $user['User']['id'])); ?>
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-group">
				<?php echo $this->request->data['User']['name']; ?>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-group">
				<?php echo $this->request->data['User']['username']; ?>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-group">
				<?php echo $this->Form->input('User.password', array('type' => 'text', 'div' => false, 'label' => 'パスワード', 'placeholder' => 'パスワード', 'class' => 'form-control')); ?>
			</div>
		</div>
	</div>
	<hr>
	<div class="text-center">
		<?php echo $this->Form->submit('パスワードを変更する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>