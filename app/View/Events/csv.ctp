<h2>CSVダウンロード</h2>

<div class="well">
	<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'csv')); ?>
		
		<div class="form-inline">
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text">開催日（開始）</div>
				</div>
				<?php echo $this->Form->input('Search.start', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control datepicker', 'placeholder' => 'YYYY-MM-DD')); ?>
			</div>
			&nbsp;～&nbsp;
			 <div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text">開催日（終了）</div>
				</div>
				<?php echo $this->Form->input('Search.end', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control datepicker', 'placeholder' => 'YYYY-MM-DD')); ?>
			</div>
		</div>
		
		<div class="text-center">
			<hr>
			<?php echo $this->Form->submit('企画申請CSV',	array('div' => false, 'class' => 'btn btn-info', 'name' => 'event')); ?>&nbsp;&nbsp;
			<?php echo $this->Form->submit('報告書CSV',	array('div' => false, 'class' => 'btn btn-info', 'name' => 'report')); ?>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
<br>
<br>
<table class="table table-bordered table-sm">
	<tr>
		<th class="w-25 bg-light">企画申請CSV</th>
		<td>ステータスが「企画申請中」、「企画検討中」、「企画承認済み」のデータを出力します。</td>
	</tr>
	<tr>
		<th class="w-25 bg-light">報告書CSV</th>
		<td>ステータスが「報告書受付中」、「報告書提出済み」、「報告書承認」のデータを出力します。</td>
	</tr>
</table>
<br>
<table class="table table-bordered table-sm">
<tr>
	<th class="w-25 bg-light">企画申請中</th>
	<td>企画が応募された直後の状態です。</td>
</tr>
<tr>
	<th class="w-25 bg-light">企画検討中</th>
	<td>企画の内容を検討している状態です。</td>
</tr>
<tr>
	<th class="w-25 bg-light">企画承認済み</th>
	<td>企画の実施を承認した状態です。</td>
</tr>
<tr>
	<th class="w-25 bg-light">報告書受付中</th>
	<td>企画が終了し報告書の提出を求める状態です。</td>
</tr>
<tr>
	<th class="w-25 bg-light">報告書提出済み</th>
	<td>提出された報告書の内容をチェックしている段階です。</td>
</tr>
<tr>
	<th class="w-25 bg-light">報告書承認（HPに表示）</th>
	<td>提出された報告書をHPに表示します。</td>
</tr>
</table>
