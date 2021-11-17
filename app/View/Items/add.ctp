<h2>課目の作成</h2>
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add')); ?>
		<div class="row">
			<div class="col-12 text-center">
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="UserLastName" class="col-12 col-sm-3 text-right">親課目</label>
						<?php echo $this->Form->input('Item.parent_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-2', 'options' => $parents)); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="UserLastName" class="col-12 col-sm-3 text-right">課目名</label>
						<?php echo $this->Form->input('Item.name', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-2', 'placeholder' => '課目名')); ?>
					</div>
				</div>
				<hr>
				<div class="mb-3">
					<?php echo $this->Form->input('Item.is_display_dropdown', array('type' => 'checkbox', 'div' => false, 'label' => false, 'class' => '')); ?>
					<label for="ItemIsDisplayDropdown">企画応募フォームの経費ドロップダウンに表示する</label>
				</div>
				<hr>
			</div>
		</div>
	<div class="text-center">
		<?php echo $this->Form->submit('作成する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>