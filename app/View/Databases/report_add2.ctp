<?php $this->assign('title', '報告書作成 | 数理技術相談データベース'); ?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'report_add2')); ?>
	<div class="container">
		<h2>報告書の作成</h2>
		
		<ul class="page-navi">
			<li class="disabled">報告書の概要</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">報告書の詳細</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">添付ファイル</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">入力内容確認</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">完了</li>
		</ul>
		<hr>

		<h5>報告書の詳細</h5>
		<hr>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">最終プログラム<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.program', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control', 'rows' => '40')); ?></td>
			</tr>
			<tr>
				<th class="bg-light w-25">参加者数<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.join_number', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
		</table>
		<br>
		<div class="text-center">
			<?php echo $this->Html->link('戻る', array('action' => 'report_add1'), array('class' => 'btn btn-secondary')); ?>&nbsp;
			<?php echo $this->Form->submit('一時保存', array('div' => false, 'class' => 'btn btn-primary', 'name' => 'save')); ?>&nbsp;
			<?php echo $this->Form->submit('次へ', array('div' => false, 'class' => 'btn btn-success', 'name' => 'confirm', 'onclick' => 'submit();')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>