<?php
$this->assign('title', '企画編集 | 数理技術相談データベース');
?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit2')); ?>
	<div class="container">
		<h2>企画応募</h2>
		
		<ul class="page-navi">
			<li class="disabled">
				企画の概要
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">
				企画の詳細
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				経費
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				参加について
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				責任者
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				入力内容確認
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				完了
			</li>
		</ul>
		<hr>

		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light">趣旨・目的<br>
					（具体的に記載のこと：何をやることでどのような効果を期待しているのか等について記載）</th>
				<td>
					<?php echo $this->Form->input('Event.purpose', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light w-25">プログラム<br>
					（未定の場合その旨を明記）</th>
				<td>
					<?php echo $this->Form->input('Event.program', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">取り扱うテーマ・トピックや解決すべき課題</th>
				<td>
					<?php echo $this->Form->input('Event.subject', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">考えられる数学・<br>数理科学的アプローチ</th>
				<td>
					<?php echo $this->Form->input('Event.approach', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			
			<tr>
				<th class="bg-light">これまでの準備状況</th>
				<td>
					<?php echo $this->Form->input('Event.prepare', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			
			<tr>
				<th class="bg-light">終了後のフォローアップの計画</th>
				<td>
					<?php echo $this->Form->input('Event.follow', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">他機関からの支援</th>
				<td>
					<?php echo $this->Form->input('Event.is_support', array('type' => 'radio', 'div' => false, 'label' => false, 'legend' => false, 'separator' => '&nbsp;&nbsp;', 'options' => $options1)); ?>
					<br>
					有の場合は支援元<br>
					<?php echo $this->Form->input('Event.support', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
				</td>
			</tr>
		</table>
		
		<div class="text-center">
			<?php echo $this->Html->link('戻る', array('action' => 'edit1'), array('class' => 'btn btn-secondary')); ?>
			<?php echo $this->Form->submit('一時保存する', array('div' => false, 'class' => 'btn btn-primary', 'name' => 'update')); ?>&nbsp;&nbsp;
			<?php echo $this->Form->submit('次へ', array('div' => false, 'class' => 'btn btn-success', 'name' => 'confirm')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>