<h2>管理者の登録</h2>
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit/'.$admin['Admin']['id'])); ?>
	
	<div class="row">
		<div class="col-12">
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="AdminUsername" class="col-12 col-sm-3 text-right">アカウント</label>
					<div class="form-group col-12 col-sm-6">
						<?php echo $this->Form->input('Admin.username', array('type' => 'text', 'div' => false, 'label' => false, 'placeholder' => '半角英数', 'class' => 'form-control')); ?>
					</div>
				</div>
			</div>
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserPassword" class="col-12 col-sm-3 text-right">パスワード</label>
					<div class="form-group col-12 col-sm-6">
						<?php echo $this->Form->input('Admin.password', array('type' => 'text', 'div' => false, 'label' => false, 'placeholder' => 'パスワード', 'class' => 'form-control')); ?>
						<small>
							※パスワードを変更しない場合、入力する必要はありません。
						</small>
					</div>
				</div>
			</div>
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserPassword" class="col-12 col-sm-3 text-right">&nbsp;</label>
					<div class="form-group col-12 col-sm-6">
						<?php echo $this->Form->input('Admin.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;チェックを付けて削除とする')); ?>
					</div>
				</div>
			</div>
			<hr>
		</div>
	</div>
	<div class="text-center">
		<?php echo $this->Form->submit('登録する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>