<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add')); ?>
	<div class="container">
		<h2>数学カタログの作成</h2>
		<hr>
		
		<div class="alert alert-success">
			<table class="table">
				<tr>
					<td rowspan="2" style="width:100px;">
						<?php echo $this->Form->input('Migration.id', array('type' => 'hidden')); ?>
						<?php echo $this->Form->input('Migration.sort', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '並び順')); ?>
					</td>
					<td>
						<?php echo $this->Form->input('Migration.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '数学カタログのタイトル')); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $this->Form->input('Migration.body', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control summernote', 'placeholder' => '概要', 'id' => 'editor')); ?>
						<span class="text-danger">
							※ 画像をアップロードする際は、ファイルサイズを縮小してアップロードして下さい。
						</sapn>
					</td>
				</tr>
			</table>
		</div>
		
		<?php foreach ( $this->request->data['MigrationChapter'] as $key1 => $migration_chapter ): ?>
			<div class="alert alert-info">
				<table class="table mb-2">
					<tr>
						<td rowspan="3" style="width:100px;">
							<?php echo $this->Form->input('MigrationChapter.'.$key1.'.id', array('type' => 'hidden')); ?>
							<?php echo $this->Form->input('MigrationChapter.'.$key1.'.migration_id', array('type' => 'hidden')); ?>
							<?php echo $this->Form->input('MigrationChapter.'.$key1.'.sort', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '並び順')); ?>
						</td>
						<td>
							<?php echo $this->Form->input('MigrationChapter.'.$key1.'.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '章のタイトル')); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $this->Form->input('MigrationChapter.'.$key1.'.body', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control summernote', 'placeholder' => '章の概要', 'id' => 'editor'.$key1)); ?>
							<span class="text-danger">
								※ 画像をアップロードする際は、ファイルサイズを縮小してアップロードして下さい。
							</sapn>
						</td>
					</tr>
					<tr>
						<td>
							<table class="table2 mb-2">
								<?php foreach ( $migration_chapter['MigrationPage'] as $key2 => $migration_page ): ?>
									<tr>
										<td rowspan="2" style="width:50px;">
											項<?php echo $migration_page['sort']; ?>
										</td>
										<td style="width:100px;">
											
											<?php echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.id',						array('type' => 'hidden' )); ?>
											<?php echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.migration_chapter_id',	array('type' => 'hidden' )); ?>
											
											<?php echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.sort', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '項' )); ?>
										</td>
										<td style="width:200px;">
											<?php echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.type', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control change-page-type', 'options' => $databases, 'data-target-id' => 'id-' . $key1 . '-' . $key2)); ?>
										</td>
										<td>
											<?php
											if ( $migration_page['type'] == 1 )
											{
												echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.target_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $options1, 'id' => 'id-' . $key1 . '-' . $key2));
											}
											else if ( $migration_page['type'] == 2 )
											{
												echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.target_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $options2, 'id' => 'id-' . $key1 . '-' . $key2));
											}
											else if ( $migration_page['type'] == 3 )
											{
												echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.target_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $options3, 'id' => 'id-' . $key1 . '-' . $key2));
											}
											else if ( $migration_page['type'] == 4 )
											{
												echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.target_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $options4, 'id' => 'id-' . $key1 . '-' . $key2));
											}
											else if ( $migration_page['type'] == 5 )
											{
												echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.target_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $options5, 'id' => 'id-' . $key1 . '-' . $key2));
											}
											else if ( $migration_page['type'] == 6 )
											{
												echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.target_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $options6, 'id' => 'id-' . $key1 . '-' . $key2));
											}
											else if ( $migration_page['type'] == 7 )
											{
												echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.target_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $options7, 'id' => 'id-' . $key1 . '-' . $key2));
											}
											else
											{
												echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.target_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => array(), 'id' => 'id-' . $key1 . '-' . $key2));
											}
											?>
										</td>
									</tr>
									<tr>
										<td colspan="3"><?php echo $this->Form->input('MigrationChapter.'.$key1.'.MigrationPage.' . $key2 . '.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '表示タイトル' )); ?></td>
									</tr>
								<?php endforeach; ?>
							</table>
							<div class="text-right">
								<?php echo $this->Form->submit('項の追加', array('div' => false, 'name' => 'data[MigrationChapter][' . $key1 . '][add-page]', 'class' => 'btn btn-sm btn-info')); ?>
							</div>
						</td>
					</tr>
				</table>
				
			</div>
		<?php endforeach; ?>
		<div class="alert">
			<div class="text-right">
				<?php echo $this->Form->submit('章の追加', array('div' => false, 'name' => 'add-chapter', 'class' => 'btn btn-sm btn-info')); ?>
			</div>
		</div>
		
		<div class="container mb-3">
			<div class="row">
				<?php echo $this->Form->input('Migration.is_display', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;数学カタログに表示する (HPに表示する)')); ?>
			</div>
		</div>
		<hr>
		
		<div class="text-center">
			<?php echo $this->Form->submit('更新する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'save')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>