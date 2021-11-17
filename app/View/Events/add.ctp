<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add')); ?>
	<div class="container">
		<h2>企画の概要</h2>
		<hr>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">種別</th>
				<td><?php echo $this->Form->input('Event.type', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $event_type)); ?></td>
				<th class="bg-light w-25">企画番号</th>
				<td></td>
			</tr>
			<tr>
				<th class="bg-light">ログインID</th>
				<td>
					<?php echo $this->Form->input('Event.username', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?><br>
					<span class="text-danger">＊半角英数、ハイフン、アンダーバー</span>
				</td>
				<th class="bg-light">パスワード</th>
				<td>
					<?php echo $this->Form->input('Event.password', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
		</table>
		
		
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">名称</th>
				<td><?php echo $this->Form->input('Event.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">該当する重点テーマ</th>
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
				<th class="bg-light">連携分野</th>
				<td><?php echo $this->Form->input('Event.field', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">キーワード</th>
				<td>
					<?php echo $this->Form->input('Event.keyword1', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline')); ?>
					<?php echo $this->Form->input('Event.keyword2', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline')); ?>
					<?php echo $this->Form->input('Event.keyword3', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">主催機関</th>
				<td><?php echo $this->Form->input('Event.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">開催時期</th>
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
		<?php echo $this->Form->submit('上記の内容で登録する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>