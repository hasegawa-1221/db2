<h2>研究者データベース</h2>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', '#', array('escape' => false, 'data-toggle' => 'modal', 'data-target' => '.bd-example-modal-lg', 'class' => 'rm-search btn btn-lg btn-danger')); ?>
</div>
<br>
<div class="text-right">
	<?php echo $this->Html->link('CSV一括登録', array('action' => 'bulk_add'), array('escape' => false, 'class' => 'btn btn-lg btn-danger')); ?>
</div>
<br>
<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'index')); ?>
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
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Researcher.id',					'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Researcher.name_ja',				'氏名'); ?></th>
				<th><?php echo $this->Paginator->sort('Researcher.affiliation',			'所属'); ?></th>
				<th><?php echo $this->Paginator->sort('Researcher.section',				'部署'); ?></th>
				<th><?php echo $this->Paginator->sort('Researcher.job',					'職名'); ?></th>
				<th><?php echo $this->Paginator->sort('Researcher.degree',				'学位'); ?></th>
				<th><?php echo $this->Paginator->sort('Researcher.is_display',			'HPに表示'); ?></th>
				<th><?php echo $this->Paginator->sort('Researcher.last_rm_date',		'researchmap<br>最終データ取得日', array('escape' => false)); ?></th>
				<th>researchmap<br>データ取得</th>
				<th>編集</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $researchers as $researcher ): ?>
				<tr>
					<td><?php echo $researcher['Researcher']['id']; ?></td>
					<td>
						<span class="fullname-<?php echo $researcher['Researcher']['id']?>"><?php echo $researcher['Researcher']['name_ja']; ?></span> / <?php echo $researcher['Researcher']['name_kana']; ?><br>
						<?php echo $researcher['Researcher']['name_en']; ?>
					</td>
					<td><?php echo $researcher['Researcher']['affiliation']; ?></td>
					<td><?php echo $researcher['Researcher']['section']; ?></td>
					<td><?php echo $researcher['Researcher']['job']; ?></td>
					<td><?php echo $researcher['Researcher']['degree']; ?></td>
					<td class="text-center"><?php echo $this->Display->is_true($researcher['Researcher']['is_display']); ?></td>
					<td><?php echo $researcher['Researcher']['last_rm_date']; ?></td>
					<td class="text-center">
						<?php
						if ( empty($researcher['Researcher']['rm_id']) )
						{
							// researchmapから新規取得
							echo $this->Html->link('新規取得', '#', array('escape' => false, 'data-toggle' => 'modal', 'data-target' => '.bd-example-modal-lg2', 'data-researcher-id' => $researcher['Researcher']['id'], 'class' => 'rm-search-row'));
						}
						else
						{
							// 再取得
							echo $this->Html->link('再取得', '#', array('escape' => false, 'data-attr-id' => $researcher['Researcher']['rm_id'], 'data-researcher-id' => $researcher['Researcher']['id'], 'class' => 'reget-researcher'));
						}
						?>
					</td>
					<td class="text-center"><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('action' => 'edit', $researcher['Researcher']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>

<?php /*新規登録用*/ ?>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">researchmap検索</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="input-group">
					<?php echo $this->Form->input('Search.name', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'id' => 'SearchName')); ?>
					<div class="input-group-btn">
						<button type="button" class="btn btn-success btn-rm-search">検索</button>
					</div>
				</div>
				<br>
				<div class="rm-paging"></div>
				<div class="results"></div>
				<div class="rm-paging"></div>
			</div>
		</div>
	</div>
</div>

<?php /* 更新用 */ ?>
<div class="modal fade bd-example-modal-lg2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel2" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">researchmap検索</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="input-group">
					<?php echo $this->Form->input('Search.name', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'id' => 'SearchName2')); ?>
					<div class="input-group-btn">
						<button type="button" class="btn btn-success btn-rm-search2">検索</button>
					</div>
				</div>
				<br>
				<div class="rm-paging"></div>
				<div class="results"></div>
				<div class="rm-paging"></div>
			</div>
		</div>
	</div>
</div>


