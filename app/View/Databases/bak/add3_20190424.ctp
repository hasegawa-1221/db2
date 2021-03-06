<?php
$this->assign('title', '企画応募 | 数理技術相談データベース');
?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add3')); ?>
	<div class="container">
		<h2>企画応募</h2>
		
		<ul class="page-navi">
			<li class="disabled">企画の概要</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">企画の詳細</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">経費</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">参加について</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">責任者</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">入力内容確認</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">完了</li>
		</ul>
		<hr>

		<h3>旅費</h3>
		<table class="table2 table-bordered table-sm" id="item-id-1">
			<thead class="bg-light">
				<tr>
					<th>所属</th>
					<th>職位</th>
					<th>姓</th>
					<th>名</th>
					<th>日程</th>
					<th>申請金額</th>
					<th>備考</th>
					<th class="text-right"></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( !empty($this->request->data['Expense']['1']) ):
					foreach ($this->request->data['Expense']['1'] as $i => $val): ?>
					<tr>
						<td>
							<?php echo $this->Form->input('Expense.1.' . $i . '.affiliation',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm form-affiliation')); ?>
						</td>
						<td><?php echo $this->Form->input('Expense.1.' . $i . '.job',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td><?php echo $this->Form->input('Expense.1.' . $i . '.lastname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td><?php echo $this->Form->input('Expense.1.' . $i . '.firstname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						
						<!-- <td><?php echo $this->Form->input('Expense.1.' . $i . '.title',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td> -->
						
						<td>
							<?php echo $this->Form->input('Expense.1.' . $i . '.date_start',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm datepicker d-inline col-4', 'error' => false)); ?>～
							<?php echo $this->Form->input('Expense.1.' . $i . '.date_end',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm datepicker d-inline col-4', 'error' => false)); ?>
							<?php echo $this->Form->error('Expense.1.' . $i . '.date_start'); ?>
						</td>
						
						
						<td><?php echo $this->Form->input('Expense.1.' . $i . '.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-request_price item-id-1')); ?></td>
						<td><?php echo $this->Form->input('Expense.1.' . $i . '.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td>
							<?php echo $this->Html->link('－', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-primary btn-minus')); ?>
						</td>
					</tr>
				<?php endforeach;
				endif;
				?>
			</tbody>
			<thead>
				<tr>
					<th class="text-right bg-light" colspan="5">小計</th>
					<td class="text-right subtotal_1"></td>
					<td colspan="2"></td>
				</tr>
			</thead>
		</table>
		<br>
		<div class="text-right">
			<?php echo $this->Html->link('＋入力行を増やす', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-success btn-plus', 'data-item-id' => 1)); ?>
		</div>
		<br>
		
		<h3>諸謝金</h3>
		<table class="table2 table-bordered table-sm" id="item-id-2">
			<thead class="bg-light">
				<tr>
					<th>所属</th>
					<th>職位</th>
					<th>姓</th>
					<th>名</th>
					<th>用務等</th>
					<th>申請金額</th>
					<th>備考</th>
					<th class="text-right"></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( !empty($this->request->data['Expense']['2']) ):
					foreach ($this->request->data['Expense']['2'] as $i => $val): ?>
					<tr>
						<td><?php echo $this->Form->input('Expense.2.' . $i . '.affiliation',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm form-affiliation')); ?></td>
						<td><?php echo $this->Form->input('Expense.2.' . $i . '.job',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td><?php echo $this->Form->input('Expense.2.' . $i . '.lastname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td><?php echo $this->Form->input('Expense.2.' . $i . '.firstname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td><?php echo $this->Form->input('Expense.2.' . $i . '.title',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td><?php echo $this->Form->input('Expense.2.' . $i . '.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-request_price item-id-2')); ?></td>
						<td><?php echo $this->Form->input('Expense.2.' . $i . '.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td>
							<?php echo $this->Html->link('－', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-primary btn-minus', 'title' => 'この行を削除します。')); ?>
						</td>
					</tr>
				<?php endforeach;
				endif;
				?>
			</tbody>
			<thead>
				<tr>
					<th class="text-right bg-light" colspan="5">小計</th>
					<td class="text-right subtotal_2"></td>
					<td colspan="2"></td>
				</tr>
			</thead>
		</table>
		<br>
		<div class="text-right">
			<?php echo $this->Html->link('＋入力行を増やす', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-success btn-plus', 'data-item-id' => 2)); ?>
		</div>
		<br>
		
		<h3>印刷製本費</h3>
		<table class="table2 table-bordered table-sm" id="item-id-3">
			<thead class="bg-light">
				<tr>
					<th>件名</th>
					<th>数量</th>
					<th>単価</th>
					<th>申請金額</th>
					<th>備考</th>
					<th class="text-right"></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( !empty($this->request->data['Expense']['3']) ):
					foreach ($this->request->data['Expense']['3'] as $i => $val): ?>
					<tr>
						<td><?php echo $this->Form->input('Expense.3.' . $i . '.title',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td><?php echo $this->Form->input('Expense.3.' . $i . '.count',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm form-count', 'attr-item-id' => 3, 'attr-i' => $i)); ?></td>
						<td><?php echo $this->Form->input('Expense.3.' . $i . '.price',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm form-price', 'attr-item-id' => 3, 'attr-i' => $i)); ?></td>
						<td><?php echo $this->Form->input('Expense.3.' . $i . '.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-request_price item-id-3')); ?></td>
						<td><?php echo $this->Form->input('Expense.3.' . $i . '.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td>
							<?php echo $this->Html->link('－', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-primary btn-minus', 'title' => 'この行を削除します。')); ?>
						</td>
					</tr>
				<?php endforeach;
				endif;
				?>
			</tbody>
			<thead>
				<tr>
					<th class="text-right bg-light" colspan="3">小計</th>
					<td class="text-right subtotal_3"></td>
					<td colspan="2"></td>
				</tr>
			</thead>
		</table>
		<br>
		<div class="text-right">
			<?php echo $this->Html->link('＋入力行を増やす', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-success btn-plus', 'data-item-id' => 3)); ?>
		</div>
		<br>
	
		<h3>その他</h3>
		<table class="table2 table-bordered table-sm" id="item-id-4">
			<thead class="bg-light">
				<tr>
					<th>件名</th>
					<th>数量</th>
					<th>単価</th>
					<th>申請金額</th>
					<th>備考</th>
					<th class="text-right"></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( !empty($this->request->data['Expense']['4']) ):
					foreach ($this->request->data['Expense']['4'] as $i => $val): ?>
					<tr>
						<td><?php echo $this->Form->input('Expense.4.' . $i . '.title',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td><?php echo $this->Form->input('Expense.4.' . $i . '.count',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm form-count', 'attr-item-id' => 4, 'attr-i' => $i)); ?></td>
						<td><?php echo $this->Form->input('Expense.4.' . $i . '.price',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm form-price', 'attr-item-id' => 4, 'attr-i' => $i)); ?></td>
						<td><?php echo $this->Form->input('Expense.4.' . $i . '.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm text-right form-request_price item-id-4')); ?></td>
						<td><?php echo $this->Form->input('Expense.4.' . $i . '.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
						<td>
							<?php echo $this->Html->link('－', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-primary btn-minus', 'title' => 'この行を削除します。')); ?>
						</td>
					</tr>
				<?php endforeach;
				endif;
				?>
			</tbody>
			<thead>
				<tr>
					<th class="text-right bg-light" colspan="3">小計</th>
					<td class="text-right subtotal_4"></td>
					<td colspan="2"></td>
				</tr>
			</thead>
		</table>
		<br>
		<div class="text-right">
			<?php echo $this->Html->link('＋入力行を増やす', 'javascript:void(0);', array('escape' => false, 'class' => 'btn btn-sm btn-success btn-plus', 'data-item-id' => 4)); ?>
		</div>
		<br>
		
		<div class="continer">
			<div class="row">
				<div class="col-12 col-sm-6 offset-sm-6">
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
			<?php echo $this->Html->link('戻る', array('action' => 'add2'), array('class' => 'btn btn-secondary')); ?>&nbsp;&nbsp;
			<?php echo $this->Form->submit('一時保存する', array('div' => false, 'class' => 'btn btn-primary', 'name' => 'update')); ?>&nbsp;&nbsp;
			<?php echo $this->Form->button('次へ', array('type' => 'button', 'div' => false, 'class' => 'btn btn-success', 'name' => 'confirm', 'onclick' => 'submit();')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>