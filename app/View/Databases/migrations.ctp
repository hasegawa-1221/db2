<?php
$this->assign('title', '数学カタログ | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('数学カタログ', '/databases/migrations/');
?>
<h2>数学カタログ</h2>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'migrations')); ?>
			<div class="row pb-4">
				<div class="col-4">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">タイトル</div>
						</div>
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => 'タイトル・概要などから検索します。')); ?>
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
	<?php if ( !empty($migrations) ): ?>
		<?php echo $this->Element('paginate'); ?>
		<?php foreach ( $migrations as $migration ): ?>
			<div class="alert alert-info mb-3">
				<h5><?php echo $migration['Migration']['title']; ?></h5>
				<?php if ( !empty($migration['Migration']['body']) ): ?>
					<p class="ml-2"><?php echo nl2br($migration['Migration']['body']); ?></p>
				<?php endif; ?>
				
				<div class="text-right">
					<?php echo $this->Html->link('詳しく見る', array('action' => 'migration_detail', $migration['Migration']['id']), array('class' => 'btn btn-primary')); ?>
				</div>
				
			</div>
		<?php endforeach; ?>
		<?php echo $this->Element('paginate'); ?>
	<?php else: ?>
		<p class="alert alert-warning">データが見つかりませんでした。</a>
	<?php endif; ?>
</div>