<h2>経費一覧（企画申請時）</h2>
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
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Expense.id',						'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.created',				'データ作成日'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.event_number',			'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.item_id',				'課目'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.affiliation',			'所属'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.job',					'職位'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.lastname',				'氏名'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.title',					'タイトル'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.lastname',				'申請金額'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.lastname',				'AIMaP執行金額'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.lastname',				'ASK執行金額'); ?></th>
				<th><?php echo $this->Paginator->sort('Expense.status',					'状態'); ?></th>
				<th colspan="5">編集</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $expenses as $expense ): ?>
				<tr>
					<td><?php echo $expense['Expense']['id']; ?></td>
					<td><?php echo date('Y/m/d', strtotime($expense['Expense']['created'])); ?></td>
					<td><?php echo $this->Html->link($expense['Event']['event_number'], array('controller' => 'events', 'action' => 'edit3', $expense['Event']['id']), array()); ?></td>
					<td><?php echo $items[$expense['Expense']['item_id']]; ?></td>
					<td><?php echo $expense['Expense']['affiliation']; ?></td>
					<td><?php echo $expense['Expense']['job']; ?></td>
					<td>
						<span class="fullname-<?php echo $expense['Expense']['id']?>"><?php echo $expense['Expense']['lastname']; ?><?php echo $expense['Expense']['firstname']; ?></span>
						<?php
						if ( !empty($expense['Expense']['lastname']) && !empty($expense['Expense']['firstname']) )
						{
							echo $this->Html->link('<i class="fa fa-search" aria-hidden="true"></i>', '#', array('escape' => false, 'data-toggle' => 'modal', 'data-target' => '.bd-example-modal-lg', 'data-expense-id' => $expense['Expense']['id'], 'class' => 'rm-search'));
						}
						?>
					</td>
					<td><?php echo $expense['Expense']['title']; ?></td>
					<td class="text-right">&yen;<?php echo number_format($expense['Expense']['request_price']); ?></td>
					<td class="text-right">&yen;<?php echo number_format($expense['Expense']['accept_price']); ?></td>
					<td class="text-right">
						<?php if ( empty($expense['Expense']['ask_price']) ): ?>
							&yen;0
						<?php else: ?>
							&yen;<?php echo number_format($expense['Expense']['ask_price']); ?>
						<?php endif; ?>
					</td>
					<td class="text-center"><?php echo $this->Display->get_event_status($expense['Expense']['status']); ?></td>
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
