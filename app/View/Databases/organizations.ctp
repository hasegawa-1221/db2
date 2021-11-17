<?php
$this->assign('title', '研究機関データベース | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究機関データベース', '/databases/organizations/');
?>
<h2>研究機関データベース</h2>
<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'organizations')); ?>
			<div class="row pb-4">
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">研究機関名</div>
						</div>
						<?php echo $this->Form->input('Search.name', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">都道府県</div>
						</div>
						<?php echo $this->Form->input('Search.prefecture_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $prefectures)); ?>
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
	<?php if ( !empty($affiliations) ): ?>
		<?php echo $this->Element('paginate'); ?>
		<?php foreach ( $affiliations as $affiliation ): ?>
			<div class="alert mb-2">
				<h5><?php echo $this->Html->link($affiliation['Affiliation']['name'], array('action' => 'organization_detail', $affiliation['Affiliation']['id'])); ?></h5>
				所在地<br>
				&nbsp;〒<?php echo $affiliation['Affiliation']['zip']; ?><?php echo $prefectures[$affiliation['Affiliation']['prefecture_id']]; ?>&nbsp;<?php echo $affiliation['Affiliation']['city']; ?>&nbsp;<?php echo $affiliation['Affiliation']['address']; ?><br>
				連絡先<br>
				&nbsp;電話番号：<?php echo (!empty($affiliation['Affiliation']['tel']))?$affiliation['Affiliation']['tel']:'-'; ?><br>
				&nbsp;メールアドレス：<?php echo (!empty($affiliation['Affiliation']['email']))?$affiliation['Affiliation']['email']:'-'; ?>
			</div>
			<hr>
			<br>
		<?php endforeach; ?>
		<?php echo $this->Element('paginate'); ?>
	<?php else: ?>
		<p class="alert alert-warning">データが見つかりませんでした。</a>
	<?php endif; ?>
</div>