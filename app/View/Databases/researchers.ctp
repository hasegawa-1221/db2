<?php
$this->assign('title', '研究者データベース | 数理技術相談データベース');

// パンくずリスト設定
$this->Html->addCrumb('研究者データベース', '/databases/researchers/');
?>

<h2>研究者データベース</h2>
<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'researchers')); ?>
			<div class="row pb-4">
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">研究者氏名</div>
						</div>
						<?php echo $this->Form->input('Search.name', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '日本語・英語・カナ')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">所属</div>
						</div>
						<?php echo $this->Form->input('Search.affiliation', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-affiliation', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">部署</div>
						</div>
						<?php echo $this->Form->input('Search.section', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '')); ?>
					</div>
				</div>
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">プロフィール</div>
						</div>
						<?php echo $this->Form->input('Search.profile', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => 'プロフィール欄より検索します。')); ?>
					</div>
				</div>
			</div>
			<div class="row pb-4">
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
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('検索する', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>


<?php if ( !empty($researchers) ): ?>
	<?php echo $this->Element('paginate'); ?>
		<div class="row">
			<?php foreach ( $researchers as $researcher ): ?>
				<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">
								<small><small><?php echo $researcher['Researcher']['name_kana']; ?></small></small><br>
								<?php echo $this->Html->link($researcher['Researcher']['name_ja'] . ' / ' . $researcher['Researcher']['name_en'], array('action' => 'researcher_detail', $researcher['Researcher']['id'])); ?>
							</h5>
							<p class="card-text">
								<h5><?php echo $researcher['Researcher']['affiliation']; ?></h5>
								<small>
								<?php echo $researcher['Researcher']['section']; ?> / <?php echo $researcher['Researcher']['job'];
			if ( !empty($researcher['Researcher']['rm_id']) )
			{
#				$rmurl="https://researchmap.jp/" . substr(strstr($researcher['Researcher']['rm_id'],'?'),4);
				$rmurl=$researcher['Researcher']['rm_id'];
				echo "<br/><a href=\"" . $rmurl . "\" target=_blank>$rmurl</a><br/>";
			}
?>
								</small>
							</p>
						</div>
						<div class="card-footer">
							<small class="text-muted"><?php echo ( !empty($researcher['Researcher']['degree']) )?$researcher['Researcher']['degree']:'&nbsp;'; ?></small>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<div class="container">
		<p class="alert alert-warning">データが未登録です。</a>
	</div>
<?php endif; ?>