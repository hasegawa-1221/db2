<h2>所属の作成</h2>
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit/'.$affiliation['Affiliation']['id'])); ?>
		<div class="row">
			<div class="col-12 text-center">
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">所属先名称/研究機関</label>
						<?php echo $this->Form->input('Affiliation.name', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">郵便番号</label>
						<?php echo $this->Form->input('Affiliation.zip', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">都道府県</label>
						<?php echo $this->Form->input('Affiliation.prefecture_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6', 'options' => $prefectures)); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">市区町村</label>
						<?php echo $this->Form->input('Affiliation.city', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">住所</label>
						<?php echo $this->Form->input('Affiliation.address', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">TEL</label>
						<?php echo $this->Form->input('Affiliation.tel', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">Email</label>
						<?php echo $this->Form->input('Affiliation.email', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">緯度</label>
						<?php echo $this->Form->input('Affiliation.lat', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">経度</label>
						<?php echo $this->Form->input('Affiliation.lng', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">表示</label>
						<?php echo $this->Form->input('Affiliation.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;研究機関として研究期間データベースに表示する (HPに表示する)')); ?>
					</div>
				</div>
				<hr>
				<div class="mb-3">
					<?php echo $this->Form->input('Affiliation.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => false, 'class' => '')); ?>
					<label for="AffiliationIsDelete">チェックを付けて削除とする</label>
				</div>
				<hr>
			</div>
		</div>
	<div class="text-center">
		<?php echo $this->Form->submit('更新する', array('div' => false, 'class' => 'btn btn-success')); ?>&nbsp;
		<?php echo $this->Form->submit('緯度経度を取得し更新する', array('div' => false, 'class' => 'btn btn-warning', 'name' => 'take')); ?>
	</div>
<?php echo $this->Form->end(); ?>