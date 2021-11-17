<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit3/' . $event['Event']['id'])); ?>
	<div class="container">
		<h2>経費</h2>
		<?php echo $this->Element('admin/edit-header'); ?>
	</div>

	<div class="container-fluid">
		<hr>
		<h3>旅費</h3>
		<table class="table2 table-bordered table-sm" id="item-id-1">
			<thead class="bg-light">
				<tr>
					<th colspan="2">契約番号</th>
					<th>所属</th>
					<th>職位</th>
					<th>姓</th>
					<th>名</th>
					<th>日程</th>
					<th>申請金額</th>
					<th>執行金額</th>
					<th>ASK金額</th>
					<th>備考</th>
					<th>状態</th>
					<th class="text-center" style="width:3%;"><?php echo $this->Html->link('＋', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-success btn-plus', 'data-item-id' => 1)); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( !empty($this->request->data['Expense']['1']) ):
					foreach ($this->request->data['Expense']['1'] as $i => $val): ?>
						<tr>
							<td>
								<?php echo $this->Form->input('Expense.1.' . $i . '.id',				array('type' => 'hidden')); ?>
								<?php echo $this->Form->input('Expense.1.' . $i . '.contract_number',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.contract_branch',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:3rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.affiliation',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm form-affiliation', 'style' => 'width:8rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.job',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.lastname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:6rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.firstname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:6rem;')); ?></td>
							<td>
								<?php // echo $this->Form->input('Expense.1.' . $i . '.title',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
								<?php echo $this->Form->input('Expense.1.' . $i . '.date_start',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm datepicker d-inline col-4', 'error' => false)); ?>～
								<?php echo $this->Form->input('Expense.1.' . $i . '.date_end',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm datepicker d-inline col-4', 'error' => false)); ?>
								<?php echo $this->Form->error('Expense.1.' . $i . '.date_start'); ?>
							</td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-request_price item-id-1', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.accept_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-accept_price item-id-1', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.ask_price',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-ask_price item-id-1', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
							<td><?php echo $this->Form->input('Expense.1.' . $i . '.status',			array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'options' => $expense_status)); ?></td>
							<td class="text-center">
								<?php echo $this->Html->link('－', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-primary btn-minus')); ?>
							</td>
						</tr>
				<?php
					endforeach;
				endif;
				?>
			</tbody>
			<thead>
				<tr>
					<th class="text-right bg-light" colspan="7">小計</th>
					<td class="text-right subtotal_1" style="width:7%;"></td>
					<td class="text-right subtotal_accept_1" style="width:7%;"></td>
					<td class="text-right subtotal_ask_1" style="width:7%;"></td>
					<td colspan="3"></td>
				</tr>
			</thead>
		</table>
		<br>
		
		<h3>諸謝金</h3>
		<table class="table2 table-bordered table-sm" id="item-id-2">
			<thead class="bg-light">
				<tr>
					<th colspan="2">契約番号</th>
					<th>所属</th>
					<th>職位</th>
					<th>姓</th>
					<th>名</th>
					<th>用務等</th>
					<th>申請金額</th>
					<th>執行金額</th>
					<th>ASK金額</th>
					<th>備考</th>
					<th>状態</th>
					<th class="text-center" style="width:3%;"><?php echo $this->Html->link('＋', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-success btn-plus', 'data-item-id' => 2)); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( !empty($this->request->data['Expense']['2']) ):
					foreach ($this->request->data['Expense']['2'] as $i => $val):
				?>
						<tr>
							<td>
								<?php echo $this->Form->input('Expense.2.' . $i . '.id',				array('type' => 'hidden')); ?>
								<?php echo $this->Form->input('Expense.2.' . $i . '.contract_number',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.contract_branch',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:3rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.affiliation',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm form-affiliation', 'style' => 'width:8rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.job',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.lastname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:6rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.firstname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:6rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.title',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-request_price item-id-2', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.accept_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-accept_price item-id-2', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.ask_price',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-ask_price item-id-2', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
							<td><?php echo $this->Form->input('Expense.2.' . $i . '.status',			array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'options' => $expense_status)); ?></td>
							<td class="text-center">
								<?php echo $this->Html->link('－', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-primary btn-minus')); ?>
							</td>
						</tr>
				<?php
					endforeach;
				endif;
				?>
			</tbody>
			<thead>
				<tr>
					<th class="text-right bg-light" colspan="7">小計</th>
					<td class="text-right subtotal_2" style="width:7%;"></td>
					<td class="text-right subtotal_accept_2" style="width:7%;"></td>
					<td class="text-right subtotal_ask_2" style="width:7%;"></td>
					<td colspan="3"></td>
				</tr>
			</thead>
		</table>
		<br>
	
		<h3>印刷製本費</h3>
		<table class="table2 table-bordered table-sm" id="item-id-3">
			<thead class="bg-light">
				<tr>
					<th colspan="2">契約番号</th>
					<th>件名</th>
					<th>数量</th>
					<th>単価</th>
					<th>申請金額</th>
					<th>執行金額</th>
					<th>ASK金額</th>
					<th>備考</th>
					<th>状態</th>
					<th class="text-center" style="width:3%;"><?php echo $this->Html->link('＋', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-success btn-plus', 'data-item-id' => 3)); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( !empty($this->request->data['Expense']['3']) ):
					foreach ($this->request->data['Expense']['3'] as $i => $val):
				?>
						<tr>
							<td>
								<?php echo $this->Form->input('Expense.3.' . $i . '.id',			array('type' => 'hidden')); ?>
								<?php echo $this->Form->input('Expense.3.' . $i . '.contract_number',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
							</td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.contract_branch',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:3rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.title',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.count',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.price',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-request_price item-id-3', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.accept_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-accept_price item-id-3', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.ask_price',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-ask_price item-id-3', 'style' => 'width:5rem;')); ?></td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
							<td><?php echo $this->Form->input('Expense.3.' . $i . '.status',			array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'options' => $expense_status)); ?></td>
							<td class="text-center">
								<?php echo $this->Html->link('－', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-primary btn-minus')); ?>
							</td>
						</tr>
				<?php
					endforeach;
				endif;
				?>
			</tbody>
			<thead>
				<tr>
					<th class="text-right bg-light" colspan="5">小計</th>
					<td class="text-right subtotal_3" style="width:7%;"></td>
					<td class="text-right subtotal_accept_3" style="width:7%;"></td>
					<td class="text-right subtotal_ask_3" style="width:7%;"></td>
					<td colspan="4"></td>
				</tr>
			</thead>
		</table>
		<br>
	
		<h3>その他</h3>
		<div class="table-responsive">
			<table class="table2 table-bordered table-sm" id="item-id-4">
				<thead class="bg-light">
					<tr>
						<th colspan="2">契約番号</th>
						<th>件名</th>
						<th>数量</th>
						<th>単価</th>
						<th>申請金額</th>
						<th>執行金額</th>
						<th>ASK金額</th>
						<th>備考</th>
						<th>状態</th>
						<th class="text-center" style="width:3%;"><?php echo $this->Html->link('＋', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-success btn-plus', 'data-item-id' => 4)); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( !empty($this->request->data['Expense']['4']) ):
						foreach ($this->request->data['Expense']['4'] as $i => $val): ?>
							<tr>
								<td>
									<?php echo $this->Form->input('Expense.4.' . $i . '.id',				array('type' => 'hidden')); ?>
									<?php echo $this->Form->input('Expense.4.' . $i . '.contract_number',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?>
								</td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.contract_branch',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:3rem;')); ?></td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.title',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.count',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:5rem;')); ?></td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.price',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'style' => 'width:5rem;')); ?></td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-request_price item-id-4', 'style' => 'width:5rem;')); ?></td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.accept_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-accept_price item-id-4', 'style' => 'width:5rem;')); ?></td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.ask_price',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-ask_price item-id-4', 'style' => 'width:5rem;')); ?></td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
								<td><?php echo $this->Form->input('Expense.4.' . $i . '.status',			array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm', 'options' => $expense_status)); ?></td>
								<td class="text-center">
									<?php echo $this->Html->link('－', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-primary btn-minus')); ?>
								</td>
							</tr>
					<?php
						endforeach;
					endif;
					?>
				</tbody>
				<thead>
					<tr>
						<th class="text-right bg-light" colspan="5">小計</th>
						<td class="text-right subtotal_4" style="width:7%;"></td>
						<td class="text-right subtotal_accept_4" style="width:7%;"></td>
						<td class="text-right subtotal_ask_4" style="width:7%;"></td>
						<td colspan="3"></td>
					</tr>
				</thead>
			</table>
		</div>
		<br>
		<div class="continer">
			<div class="row">
				<div class="col-12 col-sm-4 offset-sm-8">
					<table class="table table-bordered">
						<tr>
							<th class="text-right bg-light w-50">合計</th>
							<td class="text-right total"></td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div class="text-center">
			<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>

<?php if(isset($items) && !empty($items)): ?>
<script>
	var items = {
	<?php
	$count = count($items);
	$i = 0;
	foreach($items as $key => $item): ?>
		"<?php echo $key; ?>":"<?php echo $item; ?>"
		<?php
		$i++;
		if ( $i < $count )
		{
			echo ',';
		}
		?>
	<?php endforeach; ?>
	};
</script>
<?php endif; ?>
