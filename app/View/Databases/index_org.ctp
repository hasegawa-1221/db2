<?php
$this->assign('title', '数理技術相談データベース');
?>

<style>
.form-signin {
	max-width: 400px;
	padding: 15px;
	margin: 0 auto;
}
</style>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'index', 'class' => 'form-signin')); ?>
	<h4 class="form-signin-heading">
		下のドロップダウンより、<br>
		閲覧するデータベースを選択して下さい。
	</h4>
	<div class="form-group">
		<div class="input-group">
			<?php echo $this->Form->input('Database.id', array('type' => 'select', 'div' => false, 'label' => false, 'options' => $database_list, 'class' => 'form-control')); ?>
			<?php echo $this->Form->submit('アクセス', array('div' => false, 'class' => 'btn btn-primary input-group-addon')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>