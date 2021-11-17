<?php echo $this->Form->create(null, array('type' => 'file', 'url' => 'report_add3/' . $event['Event']['id'])); ?>
	<div class="container">
		<h2>報告書の作成</h2>
		
		<?php echo $this->Element('admin/report-header'); ?>
		<hr>

		<?php
		$i=0;
		foreach ($this->request->data['EventFile'] as $key => $event_file)
		{
			echo '<div class="row mb-3">';
				echo '<div class="col-2">';
					echo '添付ファイル ' . ($i + 1);
				echo '</div>';
				echo '<div class="col-10">';
					if ( isset($event['EventFile'][$key]['file_org']) && is_string($event['EventFile'][$key]['file_org']) )
					{
						echo '<div class="mb-2">';
							
							echo '<div class="mb-2">選択済みファイル：' . $this->Html->link($event['EventFile'][$key]['file_org'], '/app/webroot/files/event_file/file/' . $event['EventFile'][$key]['id'] . '/' . $event['EventFile'][$key]['file'], array('target' => '_blank') );
								echo '&nbsp;&nbsp;';
								echo $this->Html->link('削除する', array('action' => 'file_delete', $event['EventFile'][$key]['id']), array('class' => 'btn btn-sm btn-danger'), '選択済みファイルを削除しますか？');
							echo '</div>';
							
							echo '<div class="mb-2">';
								echo $this->Display->file( $event['EventFile'][$key] );
							echo '</div>';
							
						echo  '</div>';
					}
					echo $this->Form->input('EventFile.' . $key . '.id', array('type' => 'hidden'));
					echo $this->Form->input('EventFile.' . $key . '.event_id', array('type' => 'hidden'));
					echo $this->Form->input('EventFile.' . $key . '.file_org', array('type' => 'hidden'));
					echo $this->Form->input('EventFile.' . $key . '.file', array('type' => 'file', 'div' => false, 'label' => false, 'class' => 'form-control'));
				echo '</div>';
			echo '</div>';
			echo '<hr>';
			$i++;
		}
		?>
		<div class="text-right">
			<?php echo $this->Form->submit('添付ファイルを追加する', array('div' => false, 'name' => 'add_event_file', 'class' => 'btn btn-info')); ?>
		</div>
		<hr>
		
		<div class="text-center">
			<?php echo $this->Form->submit('更新する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'save')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>