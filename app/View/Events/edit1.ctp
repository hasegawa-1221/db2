<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit1/' . $event['Event']['id'])); ?>
	<div class="container">
		<h2>企画の概要</h2>
		<?php echo $this->Element('admin/edit-header'); ?>
		<hr>
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light w-25">種別</th>
				<td>
					<?php echo $this->Form->input('Event.type', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $event_type)); ?>
				</td>
				<th class="bg-light w-25">企画番号</th>
				<td>
					<?php echo $this->Form->input('Event.event_number', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">ログインID</th>
				<td><?php echo $this->Form->input('Event.username', array(-'type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
				<th class="bg-light">パスワード</th>
				<td>
					<?php echo $this->Form->input('Event.password', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
					<small>※パスワードを変更する場合のみ入力して下さい。</small>
				</td>
			</tr>
		</table>
		
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light w-25">名称</th>
				<td><?php echo $this->Form->input('Event.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">集会等のタイプ</th>
				<td>
					<?php
					if( !empty($themes) )
					{
						foreach ( $themes as $id => $theme )
						{
							echo $this->Form->input('EventTheme.' . $id . '.id', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;' . $theme));
							echo '<br>';
						}
					}
					echo $this->Form->error('EventTheme.id');
					?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">連携相手の分野・業界</th>
				<td><?php echo $this->Form->input('Event.field', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">キーワード</th>
				<td>
					
					<div class="keyword-area mb-2">
						<?php
						if ( empty($this->request->data['EventKeyword']) )
						{
							for ( $i=0; $i<3; $i++ )
							{
								echo $this->Form->input('EventKeyword.' . $i . '.id', array('type' => 'hidden'));
								echo $this->Form->input('EventKeyword.' . $i . '.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 mb-2 d-inline data-keyword'));
								echo '&nbsp;';
							}
						}
						else
						{
							foreach ( $this->request->data['EventKeyword'] as $key => $event_keyword )
							{
								echo $this->Form->input('EventKeyword.' . $key . '.id', array('type' => 'hidden'));
								echo $this->Form->input('EventKeyword.' . $key . '.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 mb-2 d-inline data-keyword'));
								echo '&nbsp;';
							}
						}
						?>
					</div>
					<div class="text-right">
						<?php echo $this->Html->link('キーワードを増やす', 'javascript:void(0);', array('class' => 'btn btn-info btn-add-keyword')); ?>
					</div>
				</td>
			</tr>
			<tr>
				<th class="bg-light">主催機関</th>
				<td><?php echo $this->Form->input('Event.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">開催時期<br>（YYYY-MM-DD）</th>
				<td>
					<?php echo $this->Form->input('Event.start', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline datepicker')); ?>～
					<?php echo $this->Form->input('Event.end', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline datepicker')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">開催場所</th>
				<td><?php echo $this->Form->input('Event.place', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
		</table>
		
		<div class="text-center">
			<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
