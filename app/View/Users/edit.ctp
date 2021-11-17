<h2>使用者の更新</h2>
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit/'.$user['User']['id'])); ?>
	<div class="row">
		<div class="col-12">
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserAffiliationId" class="col-12 col-sm-3 text-right">所属</label>
					<?php echo $this->Form->input('User.affiliation_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6', 'options' => $affiliations)); ?>
				</div>
			</div>
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserLastName" class="col-12 col-sm-3 text-right">姓名</label>
					<?php echo $this->Form->input('User.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-2', 'placeholder' => '姓')); ?>
					<?php echo $this->Form->input('User.middlename', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-2', 'placeholder' => 'ミドルネーム')); ?>
					<?php echo $this->Form->input('User.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-2', 'placeholder' => '名')); ?>
				</div>
			</div>
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserLastName" class="col-12 col-sm-3 text-right">姓名（かな）</label>
					<?php echo $this->Form->input('User.lastname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-2', 'placeholder' => 'せい')); ?>
					<?php echo $this->Form->input('User.middlename_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-2', 'placeholder' => 'ミドルネーム')); ?>
					<?php echo $this->Form->input('User.firstname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-2', 'placeholder' => 'めい')); ?>
				</div>
			</div>
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserIsResearcher" class="col-12 col-sm-3 text-right">研究者</label>
					<div class="col-12 col-sm-9">
						<?php echo $this->Form->input('User.is_researcher', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;研究者とする')); ?><br>
						<small>※研究者データベースに登録されます。</small>
					</div>
				</div>
			</div>
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserIsResearcher" class="col-12 col-sm-3 text-right">HPに表示</label>
					<div class="col-12 col-sm-3">
						<?php echo $this->Form->input('User.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;HPに表示する')); ?><br>
						<small>※HPに表示されます。</small>
					</div>
				</div>
			</div>
			<hr>
			<div class="container mb-3">
				<div class="row">
					<label for="UserIsResearcher" class="col-12 col-sm-3 text-right">削除</label>
					<div class="col-12 col-sm-3">
						<?php echo $this->Form->input('User.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;削除とする')); ?>
					</div>
				</div>
			</div>
			<hr>
		</div>
	</div>
	<div class="text-center">
		<?php echo $this->Form->submit('更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>