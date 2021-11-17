<?php
$this->assign('title', '企画編集 | 数理技術相談データベース');
?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit4')); ?>
	<div class="container">
		<h2>企画応募</h2>
		
		<ul class="page-navi">
			<li class="disabled">
				企画の概要
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				企画の詳細
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				経費
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">
				参加について
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				責任者
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				入力内容確認
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				完了
			</li>
		</ul>
		<hr>

		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light w-25">参加制限<span class="text-danger">*</span></th>
				<td>
					<?php echo $this->Form->input('Event.qualification', array('type' => 'radio', 'div' => false, 'legend' => false, 'separator' => '&nbsp;&nbsp;', 'options' => $options3, 'class' => 'event_qualification')); ?>
				</td>
			</tr>
			
			<tr>
				<th class="bg-light w-25">有の場合は参加資格</th>
				<td>
					<?php echo $this->Form->input('Event.qualification_other', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'id' => 'EventQualificationOther')); ?>
				</td>
			</tr>
			
			<tr>
				<th class="bg-light">参加申込<span class="text-danger">*</span></th>
				<td>
					<?php echo $this->Form->input('Event.qualification_apply', array('type' => 'radio', 'div' => false, 'legend' => false, 'separator' => '&nbsp;&nbsp;', 'options' => $options1)); ?>
				</td>
			</tr>
			<?php /*
			<tr>
				<th class="bg-light">参加資格</th>
				<td>
					<?php echo $this->Form->input('Event.qualification', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加申込みの要不要</th>
				<td>
					<?php echo $this->Form->input('Event.qualification_apply', array('type' => 'radio', 'div' => false, 'legend' => false, 'separator' => '&nbsp;&nbsp;', 'options' => $options1)); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">申込方法</th>
				<td>
					<?php echo $this->Form->input('Event.qualification_method', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加費の有無</th>
				<td>
					<?php echo $this->Form->input('Event.is_qualification_cost', array('type' => 'radio', 'div' => false, 'legend' => false, 'separator' => '&nbsp;&nbsp;', 'options' => $options2)); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加費の詳細</th>
				<td>
					<?php echo $this->Form->input('Event.qualification_cost', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			*/ ?>
		</table>
		
		<div class="text-center">
				<?php echo $this->Html->link('戻る', array('action' => 'edit3'), array('class' => 'btn btn-secondary')); ?>
			<?php echo $this->Form->submit('一時保存する', array('div' => false, 'class' => 'btn btn-primary', 'name' => 'update')); ?>&nbsp;&nbsp;
			<?php echo $this->Form->submit('次へ', array('div' => false, 'class' => 'btn btn-success', 'name' => 'confirm')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>