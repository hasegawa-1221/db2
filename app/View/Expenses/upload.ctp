<h2>ASKデータ取り込み</h2>
<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">ASKデータ取り込み</h5>
			<p>
				ask2シートを「<span class="text-danger">テキスト（タブ区切り）(*.txt)</span>」形式で保存してアップロードして下さい。
			</p>
		<?php echo $this->Form->create(null, array('type' => 'file', 'url' => 'upload', 'class' => 'form-inline')); ?>
			<div class="form-group">
				<div class="input-group">
					<?php echo $this->Form->input('Expense.csv', array('type' => 'file', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
					<span class="input-group-btn">
						<?php echo $this->Form->submit('アップロード', array('div' => false, 'class' => 'btn btn-success')); ?>
					</span>
				</div>
			</div>
			
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>