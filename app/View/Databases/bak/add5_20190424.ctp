<?php
$this->assign('title', '企画応募 | 数理技術相談データベース');
?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add5')); ?>
	<div class="container">
		<h2>企画応募</h2>
		
		<ul class="page-navi">
			<li class="disabled">企画の概要</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">企画の詳細</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">経費</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">参加について</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">責任者</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">入力内容確認</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">完了</li>
		</ul>
		<hr>
		
		<?php echo $this->Form->submit('一時保存する',	array('div' => false, 'class' => 'btn btn-primary', 'name' => 'update', 'style' => 'display:none;')); ?>
		
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
							echo '<span class="text-danger">※チェックを付けた場合、一時保存ボタンを押して更新して下さい。</span>';
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
										<?php echo $this->Form->input('EventManager.' . $key . '.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control col-5 form-control-sm', 'placeholder' => '姓')); ?>&nbsp;
										<?php echo $this->Form->input('EventManager.' . $key . '.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control col-5 form-control-sm', 'placeholder' => '名')); ?>
										<?php echo $this->Form->error('EventManager.' . $key . '.lastname'); ?>
										<?php echo $this->Form->error('EventManager.' . $key . '.firstname'); ?>
									</div>
								</div>
							</td>
							<th class="bg-light" nowrap="nowrap">フリガナ<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventManager.' . $key . '.lastname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control col-5 form-control-sm', 'placeholder' => 'セイ')); ?>&nbsp;
										<?php echo $this->Form->input('EventManager.' . $key . '.firstname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control col-5 form-control-sm', 'placeholder' => 'メイ')); ?>
										<?php echo $this->Form->error('EventManager.' . $key . '.lastname_kana'); ?>
										<?php echo $this->Form->error('EventManager.' . $key . '.firstname_kana'); ?>
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
							<th class="bg-light" nowrap="nowrap">郵便番号<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.zip', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control form-control-sm', 'onKeyUp' => 'AjaxZip3.zip2addr(this,"","data[EventManager][' . $key . '][prefecture_id]", "data[EventManager][' . $key . '][city]")')); ?>
								<span class="text-danger small">例）000-0000</span>
								<?php echo $this->Form->error('EventManager.' . $key . '.zip'); ?>
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
							echo $this->Form->input('EventAffair.' . $key . '.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => 'この事務担当者を削除する'));
							echo '<br>';
							echo '<span class="text-danger">※チェックを付けた場合、一時保存ボタンを押して更新して下さい。</span>';
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
										<?php echo $this->Form->input('EventAffair.' . $key . '.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control col-5 form-control-sm', 'placeholder' => '姓')); ?>&nbsp;
										<?php echo $this->Form->input('EventAffair.' . $key . '.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control col-5 form-control-sm', 'placeholder' => '名')); ?>
										<?php echo $this->Form->error('EventAffair.' . $key . '.lastname'); ?>
										<?php echo $this->Form->error('EventAffair.' . $key . '.firstname'); ?>
									</div>
								</div>
							</td>
							<th class="bg-light" nowrap="nowrap">フリガナ<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventAffair.' . $key . '.lastname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control col-5 form-control-sm', 'placeholder' => 'セイ')); ?>&nbsp;
										<?php echo $this->Form->input('EventAffair.' . $key . '.firstname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control col-5 form-control-sm', 'placeholder' => 'メイ')); ?>
										<?php echo $this->Form->error('EventAffair.' . $key . '.lastname_kana'); ?>
										<?php echo $this->Form->error('EventAffair.' . $key . '.firstname_kana'); ?>
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
								<?php echo $this->Form->input('EventAffair.' . $key . '.zip', array('type' => 'text', 'div' => false, 'label' => false, 'error' => false, 'class' => 'form-control form-control-sm', 'onKeyUp' => 'AjaxZip3.zip2addr(this,"","data[EventAffair][' . $key . '][prefecture_id]", "data[EventAffair][' . $key . '][city]")' )); ?>
								<span class="text-danger small">例）000-0000</span>
								<?php echo $this->Form->error('EventAffair.' . $key . '.zip'); ?>
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
			<?php echo $this->Html->link('戻る', array('action' => 'add4'), array('class' => 'btn btn-secondary')); ?>&nbsp;&nbsp;
			<?php echo $this->Form->submit('一時保存する',	array('div' => false, 'class' => 'btn btn-primary', 'name' => 'update')); ?>&nbsp;&nbsp;
			<?php echo $this->Form->submit('次へ',			array('div' => false, 'class' => 'btn btn-success', 'name' => 'confirm')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>