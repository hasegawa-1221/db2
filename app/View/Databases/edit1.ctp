<?php
$this->assign('title', '企画編集 | 数理技術相談データベース');
?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit1')); ?>
	<div class="container">
		<h2>企画応募</h2>
		
		<ul class="page-navi">
			<li class="active">
				企画の概要
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				企画の詳細
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				経費
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				参加について
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				責任者
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				入力内容確認
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				完了
			</li>
		</ul>
		<hr>
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light w-25">ログインID</th>
				<td>
					<?php echo $event['Event']['username']; ?>
				</td>
			</tr>
		</table>
		<br>
		
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light w-25">名称<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">開催時期<span class="text-danger">*</span><br>（YYYY-MM-DD）</th>
				<td>
					<?php echo $this->Form->input('Event.start', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-3 d-inline datepicker')); ?>～
					<?php echo $this->Form->input('Event.end', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-3 d-inline datepicker')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">開催場所<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.place', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">主催機関<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">集会等のタイプ<span class="text-danger">*</span></th>
				<td>
					<?php
					if( !empty($themes) )
					{
						foreach ( $themes as $id => $theme )
						{
							echo $this->Form->input('EventTheme.' . $id . '.id', array('type' => 'checkbox', 'div' => false, 'label' => '<strong>&nbsp;' . $theme . '</strong>'));
							echo '<br>';
							if ( $id == 7 )
							{
								echo '(例) 諸科学分野の学会や産業界の集会等において数学応用研究事例を紹介する会合、数理的手法・理論のチュートリアル、企業と学生との交流会';
								echo '<br>';
								echo '<br>';
							}
							else if ( $id == 8 )
							{
								echo '(例) スタディグループ（企業や諸科学分野から提示された問題の解決策について、数学・数理科学の研究者や学生が一定期間集中して議論する集会）、諸科学・産業が抱える問題を数学・数理科学者向けに紹介する集会';
								echo '<br>';
								echo '<br>';
							}
							else if ( $id == 9 )
							{
								echo '(例) 特定のテーマやトピックについて諸科学・産業関係者と数学・数理科学研究者が議論するワークショップ、広く一般向けに数学応用研究事例などを紹介する集会、諸科学・産業との連携の先進的取組やノウハウを共有する集会';
								echo '<br>';
								echo '<br>';
							}
						}
					}
					echo $this->Form->error('EventTheme.id');
					?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">キーワード<span class="text-danger">*</span></th>
				<td>
					<div class="keyword-area mb-2">
						<?php
						if ( empty($this->request->data['EventKeyword']) )
						{
							for ( $i=0; $i<3; $i++ )
							{
								echo $this->Form->input('EventKeyword.' . $i . '.id', array('type' => 'hidden'));
								echo $this->Form->input('EventKeyword.' . $i . '.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-3 mb-2 d-inline data-keyword'));
								echo '&nbsp;';
							}
						}
						else
						{
							foreach ( $this->request->data['EventKeyword'] as $key => $event_keyword )
							{
								echo $this->Form->input('EventKeyword.' . $key . '.id', array('type' => 'hidden'));
								echo $this->Form->input('EventKeyword.' . $key . '.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm col-3 mb-2 d-inline data-keyword'));
								echo '&nbsp;';
							}
						}
						?>
					</div>
					<div class="text-right">
						<?php echo $this->Html->link('キーワードを増やす', 'javascript:void(0);', array('class' => 'btn btn-info btn-add-keyword')); ?>
					</div>
				</td>
			</tr>
			<tr>
				<th class="bg-light">連携相手の分野・業界<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.field', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control form-control-sm')); ?></td>
			</tr>
		</table>
	
	<div class="text-center">
			<?php echo $this->Form->submit('一時保存する', array('div' => false, 'class' => 'btn btn-primary', 'name' => 'update')); ?>&nbsp;&nbsp;
		<?php echo $this->Form->submit('次へ', array('div' => false, 'class' => 'btn btn-success', 'name' => 'confirm')); ?>
	</div>
<?php echo $this->Form->end(); ?>