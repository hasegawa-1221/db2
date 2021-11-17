<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit/' . $expense['Expense']['id'], 'class' => 'form-inline')); ?>
	<div class="container-fluid">
		<h2>経費の更新</h2>
		<hr>
		<div class="row">
			<div class="col-12 col-sm-6">
				<h3>企画応募時データ</h3>
				<table class="table table-bordered">
					<tr>
						<th class="w-25 bg-light">種別</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.type',				array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $expense_type)); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">課目</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.item_id',				array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $items)); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">所属</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.affiliation',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">職位</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.job',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">氏名</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.lastname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '姓')); ?>&nbsp;
								<?php echo $this->Form->input('Expense.firstname',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '名')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">件名</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.title',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</div<
						</td>
					</tr>
					<tr>
						<th class="bg-light">日程開始</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.date_start',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</div<
						</td>
					</tr>
					<tr>
						<th class="bg-light">日程終了</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.date_end',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</div<
						</td>
					</tr>
					<tr>
						<th class="bg-light">数量</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.count',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">金額</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.price',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">申請金額</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.request_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control text-right')); ?>&nbsp;円
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">AIMaP執行金額</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.accept_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control text-right')); ?>&nbsp;円
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">備考</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.note',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="col-12 col-sm-6">
				<h3>ASK取り込みデータ</h3>
				<table class="table table-bordered">
					<tr>
						<th class="w-25 bg-light">タブ名</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.tab_name',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">執行形態別科目コード</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.execution_cd',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">執行形態別科目名称</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.execution_name',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">勘定科目コード</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.account_item_cd',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">勘定科目名称</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.account_item_name',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">相手先コード</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.partner_cd',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</div<
						</td>
					</tr>
					<tr>
						<th class="bg-light">相手先名称</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.partner_name',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">出発日</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.departure_date',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">帰着日</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.return_date',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">ASK執行金額</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.accept_price',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control text-right')); ?>&nbsp;円
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">件名</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.ask_title',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">備考</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.comment',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control w-100')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">品名／内容</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.description',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">執行額</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.ask_price',			array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">消費税額</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.consumption_tax',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">執行日</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.execution_date',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">支払状態</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.payment_status',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">契約NO</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.contract_number',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">契約行NO</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.contract_branch',	array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
					<tr>
						<th class="bg-light">税区分コード</th>
						<td>
							<div class=" form-inline">
								<?php echo $this->Form->input('Expense.tax_cd',				array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</dvi>
						</td>
					</tr>
				</table>
			</div>
		</div>
		
		<table class="table">
			<tr>
				<th class="bg-light">状態</th>
				<td>
					<div class=" form-inline">
						<?php echo $this->Form->input('Expense.status',				array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $expense_status)); ?>
					</div>
				</td>
			</tr>
			<tr>
				<th class="bg-light">削除</th>
				<td>
					<div class=" form-inline">
						<?php echo $this->Form->input('Expense.is_delete',			array('type' => 'checkbox', 'div' => false, 'label' => 'チェックを付けて削除する')); ?>
					</div>
				</td>
			</tr>
		</table>
		

	<div class="text-center">
		<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>