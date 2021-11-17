<?php echo $this->Form->create(null, array('type' => 'file', 'url' => 'add')); ?>
	<div class="container">
		<h2>研究事例の作成</h2>
		<hr>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">タイトル</th>
				<td><?php echo $this->Form->input('ResearchCase.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">研究者</th>
				<td>
					<?php echo $this->Form->input('ResearchCase.researcher', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">キーワード</th>
				<td>
					
					<?php echo $this->Form->input('ResearchCase.keyword', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">自由入力</th>
				<td><?php echo $this->Form->input('ResearchCase.body', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">添付ファイル</th>
				<td>
					<?php echo $this->Form->input('ResearchCase.file', array('type' => 'file', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">表示</th>
				<td>
					<?php echo $this->Form->input('ResearchCase.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;研究事例としてHPに表示する')); ?>
				</td>
			</tr>
		</table>
		<div class="text-center">
			<?php echo $this->Form->submit('上記の内容で作成する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'update')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
	</div>
<?php echo $this->Form->end(); ?>