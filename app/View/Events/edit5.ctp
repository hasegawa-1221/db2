<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit5/' . $event['Event']['id'])); ?>
	<div class="container">
		<h2>責任者</h2>
		<?php echo $this->Element('admin/edit-header'); ?>
		<hr>
		
		<div class="manager-area">
			<?php if ( !empty($this->request->data['EventManager']) ): ?>
				<?php $i = 0; ?>
				<?php foreach ( $this->request->data['EventManager'] as $key => $event_manager ): ?>
					<h4>運営責任者<?php echo $i+ 1; ?></h4>
					<?php
					if ( $i != 0 )
					{
						echo '<div class="alert alert-secondary">';
							// 1人め以外は削除可能
							echo $this->Form->input('EventManager.' . $key . '.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => 'この運営責任者を削除する'));
							echo '<br>';
							echo '<span class="text-danger">※チェックを付けた場合、更新ボタンを押して保存して下さい。</span>';
						echo '</div>';
					}
					?>
					<table class="table2 table-bordered table-sm">
						<tr>
							<th class="bg-light" nowrap="nowrap">参加者ID<br>(メールアドレス)<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.id', array('type' => 'hidden')); ?>
								<?php echo $this->Form->input('EventManager.' . $key . '.email', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">&nbsp;</th>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">姓名<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventManager.' . $key . '.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-5', 'placeholder' => '姓')); ?>&nbsp;
										<?php echo $this->Form->input('EventManager.' . $key . '.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-5', 'placeholder' => '名')); ?>
									</div>
								</div>
							</td>
							<th class="bg-light" nowrap="nowrap">フリガナ<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventManager.' . $key . '.lastname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-5', 'placeholder' => 'せい')); ?>&nbsp;
										<?php echo $this->Form->input('EventManager.' . $key . '.firstname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-5', 'placeholder' => 'めい')); ?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th class="bg-light">所属機関<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">所属部局<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.department', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">職名</th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.job_title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">URL</th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.url', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">郵便番号E<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.zip', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">都道府県<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.prefecture_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'options' => $prefectures)); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">市区町村<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.city', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">住所<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.address', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">TEL<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.tel', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">FAX</th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.fax', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
						</tr>
					</table>
					<hr>
					<br>
					<?php $i++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		
		<div class="text-right">
			<?php echo $this->Form->submit('運営責任者を増やす', array('div' => false, 'name' => 'manager', 'class' => 'btn btn-info')); ?>
		</div>
		<br>
		
		<div class="affair-area">
			<?php if ( !empty($this->request->data['EventAffair']) ): ?>
				<?php $i = 0; ?>
				<?php foreach ( $this->request->data['EventAffair'] as $key => $event_affair ): ?>
					<h4>事務担当者<?php echo $i+ 1; ?></h4>
					<?php
					if ( $i != 0 )
					{
						echo '<div class="alert alert-secondary">';
							// 1人め以外は削除可能
							echo $this->Form->input('EventAffair.' . $key . '.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => 'この運営責任者を削除する'));
							echo '<br>';
							echo '<span class="text-danger">※チェックを付けた場合、更新ボタンを押して保存して下さい。</span>';
						echo '</div>';
					}
					?>
					<table class="table2 table-bordered table-sm">
						<tr>
							<th class="bg-light" nowrap="nowrap">参加者ID<br>(メールアドレス)<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.id', array('type' => 'hidden')); ?>
								<?php echo $this->Form->input('EventAffair.' . $key . '.email', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">&nbsp;</th>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">姓名<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventAffair.' . $key . '.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-5', 'placeholder' => '姓')); ?>&nbsp;
										<?php echo $this->Form->input('EventAffair.' . $key . '.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-5', 'placeholder' => '名')); ?>
									</div>
								</div>
							</td>
							<th class="bg-light" nowrap="nowrap">フリガナ<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventAffair.' . $key . '.lastname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-5', 'placeholder' => 'せい')); ?>&nbsp;
										<?php echo $this->Form->input('EventAffair.' . $key . '.firstname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-5', 'placeholder' => 'めい')); ?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th class="bg-light">所属機関<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">所属部局<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.department', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">職名</th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.job_title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">URL</th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.url', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">郵便番号<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.zip', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">都道府県<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.prefecture_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'options' => $prefectures)); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">市区町村<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.city', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">住所<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.address', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">TEL<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.tel', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<th class="bg-light">FAX</th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.fax', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
						</tr>
					</table>
					<hr>
					<?php $i++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="text-right">
			<?php echo $this->Form->submit('事務担当者を増やす', array('div' => false, 'name' => 'affair', 'class' => 'btn btn-info')); ?>
		</div>
		<br>
		
		
		<div class="text-center">
			<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'update')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>