<?php
$this->assign('title', '研究集会データベース | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究集会データベース', '/databases/meetings/');
?>

<h2>研究集会データベース</h2>
<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'meetings')); ?>
			<div class="row pb-4">
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">タイトル</div>
						</div>
						<?php echo $this->Form->input('Search.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">研究分野</div>
						</div>
						<?php echo $this->Form->input('Search.field', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">研究キーワード</div>
						</div>
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">主催機関</div>
						</div>
						<?php echo $this->Form->input('Search.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-affiliation', 'placeholder' => '')); ?>
					</div>
				</div>
			</div>
			<div class="row pb-4">
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">開催場所</div>
						</div>
						<?php echo $this->Form->input('Search.place', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">開催期間</div>
						</div>
						<?php echo $this->Form->input('Search.start', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control datepicker', 'placeholder' => '')); ?>
						
						<div class="input-group-addon">
							<div class="input-group-text">～</div>
						</div>
						<?php echo $this->Form->input('Search.end', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control datepicker', 'placeholder' => '')); ?>
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
	<?php if ( !empty($meetings) ): ?>
		<?php echo $this->Element('paginate'); ?>
		<hr>
		<?php foreach ( $meetings as $meeting ): ?>
			<div class="mb-2 alert">
				<h3><?php echo $meeting['Meeting']['event_number']; ?>&nbsp;&nbsp;<?php echo $this->Html->link($meeting['Meeting']['title'], array('action' => 'meeting_detail', $meeting['Meeting']['id'])); ?></h3>
				<div class="ml-3">
					主催機関：<?php echo $meeting['Meeting']['organization']; ?><br>
					開催時期：<?php echo date('Y年n月j日', strtotime($meeting['Meeting']['start'])); ?>～<?php echo date('Y年n月j日', strtotime($meeting['Meeting']['end'])); ?><br>
					開催場所：<?php echo $meeting['Meeting']['place']; ?>
					<div class="text-right mt-2">
						<?php echo $this->Html->link('詳しく見る', array('action' => 'meeting_detail', $meeting['Meeting']['id']), array('class' => 'btn btn-primary')); ?>
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