<h2>委員会タスク一覧</h2>
<?php if ( !empty($accidents) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Accident.date',				'発覚日'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.user_id',			'担当営業'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.customer_id',		'得意先'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.title',				'品名'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.count',				'数量'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.trouble_id',		'発生場所'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.place_id',			'発見場所'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.expense',			'経費処理（営業）'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.description',		'概要'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.countermeasure',	'対策'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.manager_comment',	'責任者'); ?></th>
				<th><?php echo $this->Paginator->sort('Accident.committee_comment',	'委員会'); ?></th>
				<th>詳細</th>
				<?php if ( $auth_user['role'] > 1 ): ?>
					<th>編集</th>
				<?php endif; ?>
			</tr>
			<?php
			$i = 0;
			foreach ( $accidents as $accident ): ?>
				<tr>
					<td><?php echo date('Y年m月d日', strtotime($accident['Accident']['date'])); ?></td>
					<td><?php echo $accident['User']['name']; ?></td>
					<td><?php echo $accident['Customer']['name']; ?></td>
					<td><?php echo $accident['Accident']['title']; ?></td>
					<td><?php echo $accident['Accident']['count']; ?>部</td>
					<td><?php echo $accident['Trouble']['name']; ?></td>
					<td><?php echo $accident['Place']['name']; ?></td>
					<td class="text-right">&yen;<?php echo number_format($accident['Accident']['expense']); ?></td>
					
					<td class="text-center">
						<?php
						if ( !empty($accident['Accident']['description']) )
						{
							echo '<a tabindex="' . $i . '" role="button" data-toggle="popover" data-trigger="focus" title="事故の概要と処理" data-content="' . $accident['Accident']['description'] . '"><span class="glyphicon glyphicon-ok text-success"></span></a>';
							$i++;
						}
						?>
					</td>
					<td class="text-center">
						<?php
						if ( !empty($accident['Accident']['countermeasure']) )
						{
							echo '<a tabindex="' . $i . '" role="button" data-toggle="popover" data-trigger="focus" title="今後の対策" data-content="' . $accident['Accident']['countermeasure'] . '"><span class="glyphicon glyphicon-ok text-success"></span></a>';
							$i++;
						}
						?>
					</td>
					<td class="text-center">
						<?php
						if ( !empty($accident['Accident']['manager_comment']) )
						{
							echo '<a tabindex="' . $i . '" role="button" data-toggle="popover" data-trigger="focus" title="今部署責任者のコメント" data-content="' . $accident['Accident']['manager_comment'] . '"><span class="glyphicon glyphicon-ok text-success"></span></a>';
							$i++;
						}
						?>
					</td>
					<td class="text-center">
						<?php
						if ( !empty($accident['Accident']['committee_comment']) )
						{
							echo '<a tabindex="' . $i . '" role="button" data-toggle="popover" data-trigger="focus" title="委員会からのコメント" data-content="' . $accident['Accident']['committee_comment'] . '"><span class="glyphicon glyphicon-ok text-success"></span></a>';
							$i++;
						}
						?>
					</td>
					
					<td class="text-center"><?php echo $this->Html->link('<span class="glyphicon glyphicon-new-window"></span>', array('action' => 'detail', $accident['Accident']['id']), array('escape' => false)); ?></td>
					<?php if ( $auth_user['role'] > 1 ): ?>
						<td class="text-center">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $accident['Accident']['id']), array('escape' => false)); ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>



<div class="modal fade customer-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">得意先一覧</h4>
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<div class="input-group">
								<?php echo $this->Form->input('Customer.name', array('type' => 'text', 'div' => false, 'label' => false, 'placeholder' => '', 'class' => 'form-control')); ?>
								<span class="input-group-btn">
									<button type="button" class="btn btn-danger reset-customer-btn"><span class="glyphicon glyphicon-remove"></span></button>
								</span>
								
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<button type="button" class="btn btn-primary search-customer-btn"><span class="glyphicon glyphicon-search"></span> 検索する</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				
				<div class="row">
					<div class="default-area tags">
						<?php if (!empty($customers)): ?>
							<?php
							foreach ( $customers as $id => $customer )
							{
								echo '<div class="col-xs-12 col-sm-6 col-md-4">';
									echo $this->Html->link('<h3><span class="label label-success">' . $customer . '</span></h3>', 'javascript:void(0);', array('data-customer-id' => $id, 'escape' => false));
								echo '</div>';
							}
							?>
						<?php endif; ?>
					</div>
					
					<div class="result-area tags"></div>
					
					<div class="loading-area">
						<?php echo $this->Html->image('loading.gif', array()); ?>
					</div>
					
				</div>
			</div>
			
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
			</div>
		</div>
	</div>
</div>
