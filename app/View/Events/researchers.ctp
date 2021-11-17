<h2>研究者用データ</h2>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'researchers')); ?>
			<div class="row pb-4">
				<div class="col-2">
					<?php echo $this->Form->input('Search.affiliation', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '所属')); ?>
				</div>
				<div class="col-2">
					<?php echo $this->Form->input('Search.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '姓')); ?>
				</div>
				<div class="col-2">
					<?php echo $this->Form->input('Search.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '名')); ?>
				</div>
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('検索する', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>

<p class="alert alert-warning">
	氏名左の <i class="fa fa-search" aria-hidden="true"></i> をクリックすることでresearchmapより研究者情報を検索します。<br>
	検索後、「研究者DB」に登録ボタンをクリックすることで、本システムに研究者情報を取り込みます。
</p>
<?php if ( !empty($expenses) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Expense.id',						'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.created',				'データ作成日'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.event_number',			'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.affiliation',			'所属'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.lastname',				'氏名'); ?></th>
			</tr>
			<?php
			$i = 0;
			foreach ( $expenses as $expense ): ?>
				<tr>
					<td><?php echo $expense['Expense']['id']; ?></td>
					<td><?php echo date('Y/m/d', strtotime($expense['Expense']['created'])); ?></td>
					<td><?php echo $this->Html->link($expense['Event']['event_number'], array('controller' => 'events', 'action' => 'edit3', $expense['Event']['id']), array()); ?></td>
					<td><?php echo $expense['Expense']['affiliation']; ?></td>
					<td>
						<span class="fullname-<?php echo $expense['Expense']['id']?>"><?php echo $expense['Expense']['lastname']; ?><?php echo $expense['Expense']['firstname']; ?></span>
						<?php
						if ( !empty($expense['Expense']['lastname']) && !empty($expense['Expense']['firstname']) )
						{
							echo $this->Html->link('<i class="fa fa-search" aria-hidden="true"></i>', '#', array('escape' => false, 'data-toggle' => 'modal', 'data-target' => '.bd-example-modal-lg', 'data-expense-id' => $expense['Expense']['id'], 'class' => 'rm-search'));
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>

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
					<?php echo $this->Form->input('Search.name', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
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
