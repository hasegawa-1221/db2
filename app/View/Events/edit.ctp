<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit/' . $event['Event']['id'])); ?>
	<div class="container">
		<h2>企画管理</h2>
		<?php echo $this->Element('admin/edit-header'); ?>
		<hr>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">内部コメント</th>
				<td colspan="2">
					<?php echo $this->Form->input('Event.comment', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control', 'rows' => 20)); ?>
					<small>※ 申請者には表示されません。</small>
				</td>
			</tr>
			<tr>
				<th class="bg-light w-25">ステータス</th>
				<td class="w-25"><?php echo $this->Form->input('Event.status', array('type' => 'select', 'div' => false, 'label' => false, 'options' => $event_status, 'class' => 'form-control')); ?></td>
				<td colspan="2"></td>
			</tr>
		</table>
		<div class="text-center">
			<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
		</div>
		<br>
		
		<h4>ステータスに関して</h4>
		<table class="table">
			<tr>
				<th class="w-25">企画申請中</th>
				<td>企画が応募された直後の状態です。</td>
			</tr>
			<tr>
				<th>企画検討中</th>
				<td>企画の内容を検討している状態です。</td>
			</tr>
			<tr>
				<th>企画承認済み</th>
				<td>企画の実施を承認した状態です。</td>
			</tr>
			<tr>
				<th>報告書受付中</th>
				<td>企画が終了し報告書の提出を求める状態です。</td>
			</tr>
			<tr>
				<th>報告書提出済み</th>
				<td>提出された報告書の内容をチェックしている段階です。</td>
			</tr>
			<tr>
				<th>報告書承認（HPに表示）</th>
				<td>提出された報告書をHPに表示します。</td>
			</tr>
			<tr>
				<th>企画不採択</th>
				<td>企画を承認しない場合はこちらを設定して下さい。</td>
			</tr>
		</table>
	</div>
<?php echo $this->Form->end(); ?>