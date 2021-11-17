<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'report_add2/' . $event['Event']['id'])); ?>
	<div class="container">
		<h2>報告書の作成</h2>
		<?php echo $this->Element('admin/report-header'); ?>
		<hr>
		<h4>プログラム</h4>
		
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">最終プログラム<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.program', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control', 'rows' => '40')); ?></td>
			</tr>
			<tr>
				<th class="bg-light w-25">参加者数<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.join_number', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
		</table>
		<br>
		
		<div class="text-center">
			<?php echo $this->Form->submit('更新する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'save')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>