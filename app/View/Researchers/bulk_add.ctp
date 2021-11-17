<h2>研究者データベース</h2>
<h3>研究者CSVアップロード一括登録</h3>

<div class="text-right">
	<?php
	$base = $this->Html->url( Configure::read('App.site_url') . "files/" );
	echo $this->Html->link('テンプレートダウンロード', $base . 'researcher_list_template.csv', array('class' => 'btn btn-danger'));
	?>
</div>
<br>

<div class="card bg-light">
	<div class="card-body">
		<h5 class="card-title">データ検索</h5>
		<?php echo $this->Form->create(null, array('type' => 'file', 'url' => 'bulk_add')); ?>
			<div class="row pb-4">
				<div class="col-3">
					 <div class="input-group">
						<div class="input-group-addon">
							<div class="input-group-text">ファイル</div>
						</div>
						<?php echo $this->Form->input('Upload.csv', array('type' => 'file', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
					</div>
				</div>
			</div>
			<div class="text-center">
				<?php echo $this->Form->submit('アップロード', array('div' => false, 'class' => 'btn btn-success')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>
