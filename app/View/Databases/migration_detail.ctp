<?php
$this->assign('title', $migration['Migration']['title'] . ' | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('数学カタログ', '/databases/migrations/');
$this->Html->addCrumb($migration['Migration']['title'], '/databases/migration_detail/' . $migration['Migration']['id']);
?>

<div class="container">
	<div class="row">
		<div class="col-12">
			<h2><?php echo $migration['Migration']['title']; ?></h2>
			<?php if ( !empty($migration['Migration']['body']) ): ?>
				<div class="lead alert"><?php echo nl2br($migration['Migration']['body']); ?></div>
			<?php endif; ?>
			<?php if ( !empty($migration['MigrationChapter']) ): ?>
				<?php foreach ( $migration['MigrationChapter'] as $migration_chapter ): ?>
					<div class="ml-4">
						<h4><?php echo $migration_chapter['sort']; ?>. <?php echo $migration_chapter['title']; ?></h4>
						<?php if( !empty($migration_chapter['body']) ): ?>
							<div class="alert lead">
								<?php echo nl2br($migration_chapter['body']); ?>
							</div>
						<?php endif; ?>
						
						<?php if( !empty($migration_chapter['MigrationPage']) ): ?>
							<?php foreach ( $migration_chapter['MigrationPage'] as $migration_page ): ?>
								<?php if (!empty($migration_page['title'])): ?>
								<dl class="ml-4">
									<dt>
										<h5>
											<?php echo $migration_page['sort']; ?>. 
											<?php
											$action = $this->Display->get_migration_action($migration_page['type']);
											$col = $this->Display->get_migration_col($migration_page['type']);
											echo $this->Html->link($migration_page['title'], array('action' => $action, $migration_page[$col] ));
											?>
										</h5>
									</dt>
									<dd>
										<?php if( !empty($migration_page['body']) ): ?>
											<p><?php echo $migration_page['body']; ?></p>
										<?php endif; ?>
									</dd>
								</dl>
								<?php endif; ?>
							<?php endforeach; ?>
							<hr>
							<br>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>