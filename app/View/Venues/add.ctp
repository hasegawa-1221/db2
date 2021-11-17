<h2>研究会場の作成</h2>
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add')); ?>
		<div class="row">
			<div class="col-12 text-center">
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">研究会場</label>
						<?php echo $this->Form->input('Venue.name', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">郵便番号</label>
						<?php echo $this->Form->input('Venue.zip', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">都道府県</label>
						<?php echo $this->Form->input('Venue.prefecture_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6', 'options' => $prefectures)); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">市区町村</label>
						<?php echo $this->Form->input('Venue.city', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">住所</label>
						<?php echo $this->Form->input('Venue.address', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">TEL</label>
						<?php echo $this->Form->input('Venue.tel', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">Email</label>
						<?php echo $this->Form->input('Venue.email', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-12 col-sm-6')); ?>
					</div>
				</div>
				<hr>
				<div class="container mb-3">
					<div class="row">
						<label for="ProjectName" class="col-12 col-sm-3 text-right">表示</label>
						<?php echo $this->Form->input('Venue.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;研究会場として研究会場データベースに表示する (HPに表示する)')); ?>
					</div>
				</div>
				<hr>
			</div>
		</div>
	<div class="text-center">
		<?php echo $this->Form->submit('作成する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>