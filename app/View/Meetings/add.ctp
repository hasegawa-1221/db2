<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add/' . $event_id)); ?>
	<div class="container">
		<h2>研究集会の作成</h2>
		<hr>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">企画番号</th>
				<td><?php echo $this->Form->input('Meeting.event_number', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light w-25">名称</th>
				<td><?php echo $this->Form->input('Meeting.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">主催機関</th>
				<td><?php echo $this->Form->input('Meeting.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">開催時期</th>
				<td>
					<?php echo $this->Form->input('Meeting.start', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline datepicker')); ?>～
					<?php echo $this->Form->input('Meeting.end', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline datepicker')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">開催場所</th>
				<td><?php echo $this->Form->input('Meeting.place', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light w-25">プログラム</th>
				<td>
					<?php echo $this->Form->input('Meeting.program', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control', 'rows' => 30)); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">考えられる数学・<br>数理科学的アプローチ</th>
				<td>
					<?php echo $this->Form->input('Meeting.approach', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">趣旨・目的</th>
				<td>
					<?php echo $this->Form->input('Meeting.purpose', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light w-25">当日の論点</th>
				<td>
					<?php echo $this->Form->input('Meeting.issue', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">研究の現状と課題<br>（既にできていること、<br>できていないことの切り分け）</th>
				<td>
					<?php echo $this->Form->input('Meeting.subject', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			
			<tr>
				<th class="bg-light">新たに明らかになった課題、<br>今後解決すべきこと</th>
				<td>
					<?php echo $this->Form->input('Meeting.new_subject', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">今後の展開・フォローアップ</th>
				<td>
					<?php echo $this->Form->input('Meeting.follow', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">他からの支援</th>
				<td>
					<?php echo $this->Form->input('Meeting.support', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light w-25">参加資格</th>
				<td>
					<?php echo $this->Form->input('Meeting.qualification', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加申込みの要不要</th>
				<td>
					<?php echo $this->Form->input('Meeting.qualification_apply', array('type' => 'radio', 'div' => false, 'legend' => false, 'separator' => '&nbsp;&nbsp;', 'options' => $options1)); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">申込方法</th>
				<td>
					<?php echo $this->Form->input('Meeting.qualification_method', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加費の有無</th>
				<td>
					<?php echo $this->Form->input('Meeting.is_qualification_cost', array('type' => 'radio', 'div' => false, 'legend' => false, 'separator' => '&nbsp;&nbsp;', 'options' => $options2)); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加費の詳細</th>
				<td>
					<?php echo $this->Form->input('Meeting.qualification_cost', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">運営責任者</th>
				<td>
					<?php echo $this->Form->input('Meeting.manager', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			
			<?php
			if ( isset($this->request->data['MeetingFile']) )
			{
				$i=0;
				foreach ($this->request->data['MeetingFile'] as $key => $meeting_file)
				{
					echo '<tr>';
						echo '<th class="bg-light">';
							echo '添付ファイル ' . ($i + 1);
						echo '</th>';
						echo '<td>';
							echo '<div class="mb-2">';
								echo $this->Display->file( $meeting_file );
							echo '</div>';
						echo '</td>';
					echo '</tr>';
					$i++;
				}
			}
			?>
			
			<tr>
				<th class="bg-light">表示</th>
				<td>
					<?php echo $this->Form->input('Meeting.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;研究集会としてHPに表示する')); ?>
				</td>
			</tr>
			
		</table>
		
		
		
		<div class="text-center">
			<?php echo $this->Form->input('Meeting.event_id', array('type' => 'hidden')); ?>
			<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'update')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
	</div>
<?php echo $this->Form->end(); ?>