<?php
$this->assign('title', '研究事例データベース | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究事例データベース', '/databases/cases/');
?>
<h2>研究事例データベース</h2>
<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'cases')); ?>
			<div class="row pb-4">
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">タイトル</div>
						</div>
						<?php echo $this->Form->input('Search.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => 'タイトル')); ?>
					</div>
				</div>
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">研究者名</div>
						</div>
						<?php echo $this->Form->input('Search.researcher', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '研究者名')); ?>
					</div>
				</div>
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">ｷｰﾜｰﾄﾞ</div>
						</div>
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => 'キーワード')); ?>
					</div>
				</div>
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('検索する', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>
<div class="container">
	<?php if ( !empty($cases) ): ?>
		<?php echo $this->Element('paginate'); ?>
		<hr>
		<?php foreach ( $cases as $case ): ?>
			<div class="mb-2 alert">
				<h5 class="pb-2"><?php echo $this->Html->link($case['ResearchCase']['title'], array('action' => 'case_detail', $case['ResearchCase']['id'])); ?></h5>
				<div class="ml-3">
					研究者：<?php echo $case['ResearchCase']['researcher']; ?><br>
					キーワード：<?php echo $case['ResearchCase']['keyword']; ?><br>
					<div class="text-right mt-2">
						<?php echo $this->Html->link('詳しく見る', array('action' => 'case_detail', $case['ResearchCase']['id']), array('class' => 'btn btn-primary')); ?>
					</div>
				</div>
			</div>
			<hr>
		<?php endforeach; ?>
		<?php echo $this->Element('paginate'); ?>
	<?php else: ?>
		<p class="alert alert-warning">データが見つかりませんでした。</a>
	<?php endif; ?>
</div>