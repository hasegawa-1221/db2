<?php $this->assign('title', '報告書作成 | 数理技術相談データベース'); ?>

<?php echo $this->Form->create(null, array('type' => 'file', 'url' => 'report_add3')); ?>
	<div class="container">
		<h2>報告書の作成</h2>
		
		<ul class="page-navi">
			<li class="disabled">報告書の概要</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">報告書の詳細</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">添付ファイル</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">入力内容確認</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">完了</li>
		</ul>
		<hr>

		<?php
		$i=0;
		foreach ($this->request->data['EventFile'] as $key => $event_file)
		{
			echo '<div class="row mb-3" id="box-' . $i . '">';
				echo '<div class="col-2">';
					echo '添付ファイル ' . ($i + 1);
				echo '</div>';
				echo '<div class="col-10">';
					if ( isset($event['EventFile'][$key]['file_org']) && is_string($event['EventFile'][$key]['file_org']) )
					{
						echo '<div class="mb-2">
							選択済みファイル：' . $this->Html->link($event['EventFile'][$key]['file_org'], '/app/webroot/files/event_file/file/' . $event['EventFile'][$key]['id'] . '/' . $event['EventFile'][$key]['file'], array('target' => '_blank') );
							echo '&nbsp;&nbsp;' . $this->Html->link('削除する', array('action' => 'file_delete', $event['EventFile'][$key]['id']), array('class' => 'btn btn-sm btn-danger'), '選択済みファイルを削除しますか？');
						echo  '</div>';
					}
					echo '<div class="row mb-3">';
						echo '<div class="col-11">';
							echo $this->Form->input('EventFile.' . $key . '.id', array('type' => 'hidden'));
							echo $this->Form->input('EventFile.' . $key . '.event_id', array('type' => 'hidden'));
							echo $this->Form->input('EventFile.' . $key . '.file_org', array('type' => 'hidden'));
							echo $this->Form->input('EventFile.' . $key . '.file', array('type' => 'file', 'div' => false, 'label' => false, 'class' => 'form-control'));
						echo '</div>';
						echo '<div class="col-1 text-danger h2">';
							if ( !isset($event['EventFile'][$key]['file_org']) || empty($event['EventFile'][$key]['file_org']) )
							{
								echo '<i class="fa fa-window-close file-remove" aria-hidden="true" attr-data-i="' . $i . '"></i>';
							}
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<hr>';
			echo '</div>';
			$i++;
		}
		?>
		<div class="text-right">
			<?php echo $this->Form->submit('添付ファイルを追加する', array('div' => false, 'name' => 'add_event_file', 'class' => 'btn btn-info')); ?>
		</div>
		<hr>
		
		<div class="text-center">
			<?php echo $this->Html->link('戻る', array('action' => 'report_add2'), array('class' => 'btn btn-secondary')); ?>&nbsp;
			<?php echo $this->Form->submit('一時保存', array('div' => false, 'class' => 'btn btn-primary', 'name' => 'save')); ?>&nbsp;
			<?php echo $this->Form->submit('次へ', array('div' => false, 'class' => 'btn btn-success', 'name' => 'confirm', 'onclick' => 'submit();')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>