<header>
	<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
		<?php echo $this->Html->link($appConfig['site_name'], array('controller' => 'databases', 'action' => 'index'), array('class' => 'navbar-brand')); ?>
		<button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<?php echo $this->Html->link('ホーム',			array('controller' => 'databases', 'action' => 'index'), array('escape' => false, 'class' => 'nav-link')); ?>
			</li>
			<li class="nav-item">
				<?php echo $this->Html->link('企画応募',			array('controller' => 'databases', 'action' => 'add1'), array('escape' => false, 'class' => 'nav-link')); ?>
			</li>
			<li class="nav-item">
				<?php echo $this->Html->link('企画編集・報告書',	array('controller' => 'databases', 'action' => 'login'), array('escape' => false, 'class' => 'nav-link')); ?>
			</li>
			<?php if ( $auth_user ): ?>
				<li class="nav-item">
					<?php echo $this->Html->link('ログアウト',	array('controller' => 'databases', 'action' => 'logout'), array('escape' => false, 'class' => 'nav-link')); ?>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</nav>
</header>