<h2>課目一覧</h2>
<div class="text-right">
	<?php echo $this->Html->link('親作成', array('controller' => 'items', 'action' => 'add'), array('class' => 'btn btn-lg btn-danger')); ?>
</div>
<hr>
<?php if ( !empty($items) ): ?>
	<table class="table table-bordered">
		<thead class="thead-light">
			<tr>
				<th>親課目</th>
				<th>小課目</th>
				<th>企画応募フォームに表示</th>
			</tr>
		</thead>
		<?php foreach ( $items as $item ): ?>
			<?php
			$class = '';
			if ( $item['Item']['is_delete'] == 1 )
			{
				$class = ' class="is_delete"';
			}
			?>
			<tbody>
				<tr<?php echo $class; ?>>
					<td>
						<?php echo $this->Html->link($item['Item']['name'], array('action' => 'edit', $item['Item']['id'])); ?>
						(<?php echo $this->Html->link('子の追加', array('action' => 'add', $item['Item']['id'])); ?>)
					</td>
					<td>
						<?php if ( !empty($item['Children']) ): ?>
							<table class="table table-bordered">
								<?php foreach ( $item['Children'] as $child ): ?>
									<?php
									$child_class = "";
									if ( !empty($class) )
									{
										$child_class = $class;
									}
									else
									{
										if ( $child['is_delete'] == 1 )
										{
											$child_class = ' class="is_delete"';
										}
									}
									?>
									<tr<?php echo $child_class; ?>>
										<td>
											<?php echo $this->Html->link($child['name'], array('action' => 'edit', $child['id'])); ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</table>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $this->Display->is_true($item['Item']['is_display_dropdown']); ?>
					</td>
				</tr>
			</tbody>
		<?php endforeach; ?>
	</table>
	
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>
