<h2>経費一覧（ASK取込後）</h2>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('controller' => 'expenses', 'action' => 'add'), array('class' => 'btn btn-lg btn-danger')); ?>
</div>

<div class="well">
	<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'index', 'class' => 'form-inline')); ?>
	<?php echo $this->Form->end(); ?>
</div>

<?php if ( !empty($expenses) ): ?>
	<?php echo $this->Element('paginate'); ?>
		<div class="table-responsive">
			<table class="table table-sm table-bordered table-striped text-nowrap">
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('Expense.id',					'DB-ID'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.tab_name',			'タブ名'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.execution_cd',		'執行形態別科目コード'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.execution_name',		'執行形態別科目名称'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.account_item_cd',	'勘定科目コード'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.account_item_name',	'勘定科目名称'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.partner_cd',			'相手先コード'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.partner_name',		'相手先名称'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.departure_date',		'出発日'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.return_date',		'帰着日'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.ask_title',			'件名'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.comment',			'備考'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.description',		'品名／内容'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.ask_price',			'執行額'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.consumption_tax',	'消費税額'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.execution_date',		'執行日'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.payment_status',		'支払状態'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.contract_number',	'契約NO'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.contract_branch',	'契約行NO'); ?></th>
					<th><?php echo $this->Paginator->sort('Expense.tax_cd',				'税区分コード'); ?></th>
					<th colspan="5">編集</th>
				</tr>
				</thead>
				<?php
				$i = 0;
				foreach ( $expenses as $expense ): ?>
					<tr>
						<td><?php echo $expense['Expense']['id']; ?></td>
						<td><?php echo $expense['Expense']['tab_name']; ?></td>
						<td><?php echo $expense['Expense']['execution_cd']; ?></td>
						<td><?php echo $expense['Expense']['execution_name']; ?></td>
						<td><?php echo $expense['Expense']['account_item_cd']; ?></td>
						<td><?php echo $expense['Expense']['account_item_name']; ?></td>
						<td><?php echo $expense['Expense']['partner_cd']; ?></td>
						<td><?php echo $expense['Expense']['partner_name']; ?></td>
						<td><?php echo $expense['Expense']['departure_date']; ?></td>
						<td><?php echo $expense['Expense']['return_date']; ?></td>
						<td><?php echo $expense['Expense']['ask_title']; ?></td>
						<td><?php echo $expense['Expense']['comment']; ?></td>
						<td><?php echo $expense['Expense']['description']; ?></td>
						<td class="text-right">&yen;<?php echo number_format($expense['Expense']['ask_price']); ?></td>
						<td class="text-right">&yen;<?php echo number_format($expense['Expense']['consumption_tax']); ?></td>
						<td><?php echo $expense['Expense']['execution_date']; ?></td>
						<td><?php echo $expense['Expense']['payment_status']; ?></td>
						<td><?php echo $expense['Expense']['contract_number']; ?></td>
						<td><?php echo $expense['Expense']['contract_branch']; ?></td>
						<td><?php echo $expense['Expense']['tax_cd']; ?></td>
						<td class="text-center"><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('action' => 'edit', $expense['Expense']['id']), array('escape' => false)); ?></td>
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
