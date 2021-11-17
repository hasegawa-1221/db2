<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit4/' . $event['Event']['id'])); ?>
	<div class="container">
		<h2>参加について</h2>
		<?php echo $this->Element('admin/edit-header'); ?>
		<hr>
		
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light w-25">参加制限</th>
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
				<th class="bg-light">参加申込</th>
				<td>
					<?php echo $this->Form->input('Event.qualification_apply', array('type' => 'radio', 'div' => false, 'legend' => false, 'separator' => '&nbsp;&nbsp;', 'options' => $options1)); ?>
				</td>
			</tr>
		</table>
		
		<div class="text-center">
			<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>