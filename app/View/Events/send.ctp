<div class="row">
	<div class="col-xs-6 col-sm-6">
		<h2>メール送信</h2>
	</div>
	
</div>
<br>
<?php echo $this->Form->create('Accidents', array('url' => '/accidents/send/' . $accident['Accident']['id'] )); ?>
<div class="row">
	<label class="col-xs-12 form-group">
		送り先
	</label>
	<div class="col-xs-12 form-group">
		<?php echo $this->Form->input('Mail.to', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
	</div>
</div>
<hr>
<div class="row">
	<label class="col-xs-12 form-group">
		件名
	</label>
	<div class="col-xs-12 form-group">
		<?php echo $this->Form->input('Mail.subject', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
	</div>
</div>
<hr>
<div class="row">
	<label class="col-xs-12 form-group">
		本文
	</label>
	<div class="col-xs-12 form-group">
		<?php echo $this->Form->input('Mail.body', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'height:300px;')); ?>
	</div>
</div>
<div class="text-center">
	<?php echo $this->Form->submit('送信する', array('div' => false, 'class' => 'btn btn-danger')); ?>
</div>

<?php echo $this->Form->end(); ?>