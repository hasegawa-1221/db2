<?php
$this->assign('title', '研究会場データベース | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究会場データベース', '/databases/venues/');
?>
<h2>研究会場データベース</h2>
<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'venues')); ?>
			<div class="row pb-4">
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">研究会場名</div>
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
	<?php if ( !empty($venues) ): ?>
		<?php echo $this->Element('paginate'); ?>
		<?php foreach ( $venues as $venue ): ?>
			<div class="alert mb-2">
				<h5><?php echo $this->Html->link($venue['Venue']['name'], array('action' => 'venue_detail', $venue['Venue']['id'])); ?></h5>
				所在地<br>
				&nbsp;〒<?php echo $venue['Venue']['zip']; ?><?php echo $prefectures[$venue['Venue']['prefecture_id']]; ?>&nbsp;<?php echo $venue['Venue']['city']; ?>&nbsp;<?php echo $venue['Venue']['address']; ?><br>
				連絡先<br>
				&nbsp;電話番号：<?php echo (!empty($venue['Venue']['tel']))?$venue['Venue']['tel']:'-'; ?><br>
				&nbsp;メールアドレス：<?php echo (!empty($venue['Venue']['email']))?$venue['Venue']['email']:'-'; ?>
			</div>
			<hr>
			<br>
		<?php endforeach; ?>
		<?php echo $this->Element('paginate'); ?>
	<?php else: ?>
		<p class="alert alert-warning">データが見つかりませんでした。</a>
	<?php endif; ?>
</div>