<h2>研究者データベース</h2>
<br>
<h3>
	<?php echo $researcher['Researcher']['name_ja'] . ' ／ ' . $researcher['Researcher']['name_en']; ?>
	<small>（<?php echo $researcher['Researcher']['affiliation']; ?> <?php echo $researcher['Researcher']['section']; ?> <?php echo $researcher['Researcher']['job']; ?>）</small>
</h3>
<hr>
<div class="row pb-4">
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 基本情報',					array('action' => 'edit',			$researcher['Researcher']['id']),		array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 研究キーワード',			array('action' => 'edit_detail',	1,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 研究分野',					array('action' => 'edit_detail',	2,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 経歴',						array('action' => 'edit_detail',	3,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 学歴',						array('action' => 'edit_detail',	4,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 委員歴',					array('action' => 'edit_detail',	5,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 受賞',						array('action' => 'edit_detail',	6,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 論文',						array('action' => 'edit_detail',	7,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 書籍等出版物',				array('action' => 'edit_detail',	8,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 講演・口頭発表等',			array('action' => 'edit_detail',	9,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 担当経験のある科目',		array('action' => 'edit_detail',	10,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 所属学協会',				array('action' => 'edit_detail',	11,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 競争的資金等の研究課題',	array('action' => 'edit_detail',	12,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 特許',						array('action' => 'edit_detail',	13,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 社会貢献活動',				array('action' => 'edit_detail',	14,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
	<div class="col-2 pb-2">
		<?php echo $this->Html->link('<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> その他',					array('action' => 'edit_detail',	15,	$researcher['Researcher']['id']),	array('escape' => false)); ?>
	</div>
</div>

<?php echo $this->Form->create(null, array('type' => 'psot', 'url' => 'edit_detail/' . $type . '/' . $researcher['Researcher']['id'])); ?>
	<h4><?php echo $this->Display->reseacher_detail_title($type); ?></h4>
	<?php 
	$table = $this->Display->reseacher_detail_table($type);
		if ( !empty($researcher[$table]) )
		{
			foreach ( $researcher[$table] as $key => $detail )
			{
	?>
			<table class="table table-bordered">
				<?php foreach ( $detail as $col => $_detail ):
					$ignores = array('id', 'researcher_id', 'created', 'modified');
					if ( in_array($col, $ignores) )
					{
						if ( $col == 'id' )
						{
							echo $this->Form->input($table . '.' . $key . '.' . 'id', array('type' => 'hidden'));
						}
						continue;
					}
				?>
					<tr>
						<th class="bg-light w-25"><?php echo $this->Display->researcher_detail_column($type)[$col]; ?></th>
						<td>
							<?php
							if ( $col == 'is_delete' )
							{
								echo $this->Form->input($table . '.' . $key . '.' . $col, array('label' => false, 'div' => false));
								echo ' 削除とする';
							}
							else
							{
								echo $this->Form->input($table . '.' . $key . '.' . $col, array('label' => false, 'div' => false, 'class' => 'form-control'));
							}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
	<?php
			}
		}
		else
		{
	?>
		<p><?php echo $this->Display->reseacher_detail_title($type); ?>の情報はまだ登録されておりません。</p>
	<?php } ?>

	<br>
	<h4>追加登録</h4>
	<table class="table table-bordered">
		<?php
		$cols = $this->Display->researcher_detail_column($type);
		foreach ( $cols as $col => $_detail ):
			$ignores = array('id', 'researcher_id', 'created', 'modified');
			if ( in_array($col, $ignores) )
			{
				continue;
			}
		?>
			<tr>
				<th class="bg-light w-25"><?php echo $this->Display->researcher_detail_column($type)[$col]; ?></th>
				<td>
					<?php
					if ( $col == 'is_delete' )
					{
						echo $this->Form->input('Add.' . $table . '.' . $col, array('label' => false, 'div' => false));
						echo ' 削除とする';
					}
					else
					{
						echo $this->Form->input('Add.' . $table . '.' . $col, array('label' => false, 'div' => false, 'class' => 'form-control'));
					}
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<div class="text-center">
		<?php echo $this->Form->submit('更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>
