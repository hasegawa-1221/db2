<h2>研究事例データベース</h2>
<hr>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('controller' => 'cases', 'action' => 'add'), array('escape' => false, 'class' => 'btn btn-lg btn-danger')); ?>
</div>
<br>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'index')); ?>
			<div class="row pb-4">
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">ｷｰﾜｰﾄﾞ</div>
						</div>
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => 'タイトル・キーワードなど')); ?>
					</div>
				</div>
				<div class="col-2">
					<?php echo $this->Form->input('Search.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;HPに表示のみ')); ?>
				</div>
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('検索する', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>

<?php if ( !empty($cases) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('ResearchCase.id',				'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('ResearchCase.title',				'タイトル'); ?></th>
				<th><?php echo $this->Paginator->sort('ResearchCase.researcher',		'研究者'); ?></th>
				<th><?php echo $this->Paginator->sort('ResearchCase.keyword',			'キーワード'); ?></th>
				<th><?php echo $this->Paginator->sort('ResearchCase.file',			'添付ファイル'); ?></th>
				<th><?php echo $this->Paginator->sort('ResearchCase.is_display',		'HPに表示'); ?></th>
				<th><?php echo $this->Paginator->sort('ResearchCase.created',			'データ作成日'); ?></th>
				<th>編集</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $cases as $case ): ?>
				<tr>
					<td><?php echo $case['ResearchCase']['id']; ?></td>
					<td><?php echo $case['ResearchCase']['title']; ?></td>
					<td><?php echo $case['ResearchCase']['researcher']; ?></td>
					<td><?php echo $case['ResearchCase']['keyword']; ?></td>
					<td>
						<?php
						if ( !empty($case['ResearchCase']['file']) )
						{
							$base = $this->Html->url( Configure::read('App.site_url') . "files/research_case/file/" );
							echo $this->Html->link( $case['ResearchCase']['file_org'], $base . $case['ResearchCase']['file_dir'] . "/" . $case['ResearchCase']['file'], array('escape' => false, 'target' => '_blank') );
						}
						?>
					</td>
					<td class="text-center"><?php echo $this->Display->is_true($case['ResearchCase']['is_display']); ?></td>
					<td><?php echo date('Y/m/d', strtotime($case['ResearchCase']['created'])); ?></td>
					<td><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',	array('controller' => 'cases', 'action' => 'edit', $case['ResearchCase']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>