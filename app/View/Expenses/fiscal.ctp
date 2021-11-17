<h2>年度別経費</h2>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'fiscal')); ?>
			<div class="row pb-4">
				<div class="col-2">
					<div class="input-group">
						<?php echo $this->Form->input('Search.fiscal_year', array('type' => 'select', 'div' => false, 'label' => false, 'options' => $fiscal_years, 'class' => 'form-control')); ?>
						<span class="input-group-addon">
							年度
						</span>
					</div>
				</div>
			</div>
			
			<div class="text-center">
				<?php echo $this->Form->submit('検索', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>

<div class="container-fluid">
	<div class="row">
		<div class="col-6">
			&nbsp;
		</div>
		<div class="col-6">
			<table class="table table-bordered table-sm ">
				<tr>
					<th class="bg-light text-right">執行額合計</th>
					<td class="text-right">&yen;<?php echo number_format($total_ask_price); ?></td>
				</tr>
				<tr>
					<th class="bg-light text-right">消費税額合計</th>
					<td class="text-right">&yen;<?php echo number_format($total_consumption_tax); ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<table class="table table-bordered table-sm" style="font-size:0.8rem;">
	<tr>
		<th class="bg-light text-center"></th>
		<?php foreach ( $tabs as $key => $tab ): ?>
			<th class="bg-light text-center"><?php echo $key; ?></th>
		<?php endforeach; ?>
	</tr>
	<tr>
		<th class="bg-light text-right">執行額小計</th>
		<?php foreach ( $tabs as $key => $tab ): ?>
			<td class="text-right">&yen;<?php echo number_format($tab['ask_price']); ?></td>
		<?php endforeach; ?>
	</tr>
	<tr>
		<th class="bg-light text-right">消費税額小計</th>
		<?php foreach ( $tabs as $key => $tab ): ?>
			<td class="text-right">&yen;<?php echo number_format($tab['consumption_tax']); ?></td>
		<?php endforeach; ?>
	</tr>
</table>
<br>

<?php if ( !empty($expenses) ): ?>
	<div class="table-responsive">
		<table class="table table-sm table-bordered table-striped text-nowrap" style="font-size:0.8rem;">
			<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('Expense.id',					'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.execution_date',		'執行日'); ?></th>
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
					<td><?php echo $expense['Expense']['execution_date']; ?></td>
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
					<td><?php echo $expense['Expense']['payment_status']; ?></td>
					<td><?php echo $expense['Expense']['contract_number']; ?></td>
					<td><?php echo $expense['Expense']['contract_branch']; ?></td>
					<td><?php echo $expense['Expense']['tax_cd']; ?></td>
					<td class="text-center"><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('action' => 'edit', $expense['Expense']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>
