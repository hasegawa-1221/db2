<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'event_program_add/' . $event['Event']['id'])); ?>
	<div class="container-fluid">
		<h2>講演課題の作成</h2>
		<?php echo $this->Element('admin/report-header'); ?>
		<hr>
		<div class="row">
			<div class="col-6">
				<h4>プログラム</h4>
				<hr>
				<?php echo nl2br($event['Event']['program']); ?>
			</div>
			<div class="col-6">
				<h4>講演課題</h4>
				<?php $i=0; ?>
				<?php foreach ( $this->request->data['EventProgram'] as $key => $program ): ?>
					<?php $x=0; ?>
					<div class="alert alert-info">
						<table class="table">
							<tbody>
								<tr>
									<td style="width:100px;">
										<?php echo $this->Form->input('EventProgram.'. $key . '.id',		array('type' => 'hidden')); ?>
										<?php echo $this->Form->input('EventProgram.'. $key . '.event_id',	array('type' => 'hidden')); ?>
										<?php echo $this->Form->input('EventProgram.'. $key . '.date',		array('type' => 'hidden')); ?>
										<?php echo $this->Form->input('EventProgram.'. $key . '.sort', 		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
									</td>
									<td colspan="4"><?php echo $this->Form->input('EventProgram.'. $key . '.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '講演タイトル')); ?></td>
									<td>
										<?php
										if ( $i > 0 )
										{
											// 1週目は必須として削除させない
											echo $this->Form->submit('講演を削除', array('div' => false, 'name' => 'data[EventProgram]['.$key.'][delete-program]', 'class' => 'btn btn-danger'));
										}
										else
										{
											//echo '<span class="btn btn-sm btn-secondary">講演を削除</span>';
										}
										?>
									</td>
								</tr>
								<?php foreach ( $program['EventPerformer'] as $key2 => $performer ): ?>
									<tr>
										<td class="align-middle">
											<?php echo $this->Form->input('EventProgram.'. $key . '.EventPerformer.' . $key2 . '.id',					array('type' => 'hidden')); ?>
											<?php echo $this->Form->input('EventProgram.'. $key . '.EventPerformer.' . $key2 . '.event_program_id',	array('type' => 'hidden')); ?>
											講演者<?php echo $x + 1; ?>
										</td>
										<td><?php echo $this->Form->input('EventProgram.'. $key . '.EventPerformer.' . $key2 . '.organization',		array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '所属機関')); ?></td>
										<td><?php echo $this->Form->input('EventProgram.'. $key . '.EventPerformer.' . $key2 . '.role', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '役職')); ?></td>
										<td><?php echo $this->Form->input('EventProgram.'. $key . '.EventPerformer.' . $key2 . '.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '姓')); ?></td>
										<td><?php echo $this->Form->input('EventProgram.'. $key . '.EventPerformer.' . $key2 . '.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '名')); ?></td>
										<td>
											<?php
											if ( $x > 0 )
											{
												// 1週目は必須として削除させない
												echo $this->Form->submit('講演者を削除', array('div' => false, 'name' => 'data[EventProgram]['.$key.'][EventPerformer]['.$key2.'][delete-performer]',  'class' => 'btn btn-sm btn-warning'));
											}
											?>
										</td>
									</tr>
									<?php $x++; ?>
								<?php endforeach; ?>
								</tr>
							</tbody>
						</table>
						<div class="text-right">
							<?php echo $this->Form->submit('講演者を追加', array('div' => false, 'name' => 'data[EventProgram]['.$key.'][add-performer]', 'class' => 'btn btn-info')); ?>
						</div>
					</div>
					<?php $i++; ?>
				<?php endforeach; ?>
					
				<div class="alert">
					<div class="text-right">
						<?php  echo $this->Form->submit('講演を追加', array('div' => false, 'name' => 'add-program', 'class' => 'btn btn-info')); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="text-center">
			<?php echo $this->Form->submit('更新する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'save')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>