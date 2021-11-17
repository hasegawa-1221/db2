<h2>研究事例用データ</h2>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'cases')); ?>
			<div class="row pb-4">
				<div class="col-2">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">ｷｰﾜｰﾄﾞ</div>
						</div>
						<?php echo $this->Form->input('Search.keyword', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => 'キーワード')); ?>
					</div>
				</div>
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('検索する', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>