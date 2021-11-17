<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add')); ?>
	<div class="container">
		<h2>講演課題管理</h2>
		<hr>
		<div class="alert alert-info">
			<table class="table">
				<tr>
					<td style="width:100px;"><?php echo $event_program['EventProgram']['sort']; ?></td>
					<td><h4><?php echo $event_program['EventProgram']['title']; ?></h4></td>
				</tr>
				<?php if( !empty($event_program['EventPerformer']) ): ?>
					<?php $i=1; ?>
					<?php foreach ( $event_program['EventPerformer'] as $event_performer ): ?>
						<tr>
							<td>講演者 <?php echo $i; ?></td>
							<td>
								<?php echo $event_performer['organization']; ?>&nbsp;
								<?php echo $event_performer['role']; ?>&nbsp;
								<?php echo $event_performer['firstname']; ?>&nbsp;
								<?php echo $event_performer['lastname']; ?>
							</td>
						</tr>
						<?php $i++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
		<br>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">表示</th>
				<td class="w-25"><?php echo $this->Form->input('EventProgram.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;HPに表示する')); ?></td>
				<td colspan="2"></td>
			</tr>
		</table>
		<div class="text-center">
			<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
		</div>
		<br>
		</table>
	</div>
<?php echo $this->Form->end(); ?>