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

<?php echo $this->Form->create(null, array('type' => 'psot', 'url' => 'edit/' . $researcher['Researcher']['id'])); ?>
	<h4>基本情報</h4>
	<table class="table table-bordered">
		<tr>
			<th class="bg-light">rm_id</th>
			<td><?php echo $this->Form->input('Researcher.rm_id', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light"></th>
			<td></td>
		</tr>
		<tr>
			<th class="bg-light">氏名（日本語）</th>
			<td><?php echo $this->Form->input('Researcher.name_ja', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light">氏名（英語）</th>
			<td><?php echo $this->Form->input('Researcher.name_en', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
		</tr>
		<tr>
			<th class="bg-light">氏名（かな）</th>
			<td><?php echo $this->Form->input('Researcher.name_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light"></th>
			<td></td>
		</tr>
		<tr>
			<th class="bg-light">email</th>
			<td><?php echo $this->Form->input('Researcher.email', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light">URL</th>
			<td><?php echo $this->Form->input('Researcher.url', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
		</tr>
		<tr>
			<th class="bg-light">性別</th>
			<td><?php echo $this->Form->input('Researcher.gender', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light">生年月日</th>
			<td><?php echo $this->Form->input('Researcher.birth_date', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
		</tr>
		<tr>
			<th class="bg-light">rm上の所属ID</th>
			<td><?php echo $this->Form->input('Researcher.rm_affiliation_id', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light"></th>
			<td></td>
		</tr>
		<tr>
			<th class="bg-light">所属</th>
			<td><?php echo $this->Form->input('Researcher.affiliation', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light">部署</th>
			<td><?php echo $this->Form->input('Researcher.section', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
		</tr>
		<tr>
			<th class="bg-light">職名</th>
			<td><?php echo $this->Form->input('Researcher.job', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light">学位</th>
			<td><?php echo $this->Form->input('Researcher.degree', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
		</tr>
		<tr>
			<th class="bg-light">その他の所属rm上のID</th>
			<td><?php echo $this->Form->input('Researcher.other_affiliation_id', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light">その他の所属名</th>
			<td><?php echo $this->Form->input('Researcher.other_affiliation', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
		</tr>
		<tr>
			<th class="bg-light">その他の所属部署</th>
			<td><?php echo $this->Form->input('Researcher.other_affiliation_section', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light">その他の所属職名</th>
			<td><?php echo $this->Form->input('Researcher.other_affiliation_job', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
		</tr>
		<tr>
			<th class="bg-light">科研費ID</th>
			<td><?php echo $this->Form->input('Researcher.kaken_id', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			<th class="bg-light"></th>
			<td></td>
		</tr>
		<tr>
			<th class="bg-light">プロフィール</th>
			<td colspan="3"><?php echo $this->Form->input('Researcher.profile', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
		</tr>
		<tr>
			<th class="bg-light">表示</th>
			<td colspan="3"><?php echo $this->Form->input('Researcher.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;研究者としてHPに表示する')); ?></td>
		</tr>
	</table>
	<div class="text-center">
		<?php echo $this->Form->submit('更新する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>