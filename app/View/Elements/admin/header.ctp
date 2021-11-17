<header>
	<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
		<?php echo $this->Html->link($appConfig['site_name'], array('controller' => 'dashboards', 'action' => 'index'), array('class' => 'navbar-brand')); ?>
		<button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
		<ul class="navbar-nav ml-auto">
			
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">データベース管理</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<?php echo $this->Html->link('数学カタログ',			array('controller' => 'migrations',		'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('研究者',				array('controller' => 'researchers',	'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('研究集会',			array('controller' => 'meetings',		'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('講演課題',			array('controller' => 'reports',		'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('研究機関',			array('controller' => 'organizations',	'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('研究会場',			array('controller' => 'venues',			'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('研究事例',			array('controller' => 'cases',			'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
				</div>
			</li>
			
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">企画管理</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<?php echo $this->Html->link('企画一覧',			array('controller' => 'events',			'action' => 'index'),			array('escape' => false, 'class' => 'dropdown-item')); ?>
					<div class="dropdown-divider"></div>
					<?php echo $this->Html->link('研究者用データ',		array('controller' => 'events',			'action' => 'researchers'),		array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('研究集会用データ',	array('controller' => 'events',			'action' => 'meetings'),		array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('講演課題用データ',	array('controller' => 'events',			'action' => 'reports'),			array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('研究機関用データ',	array('controller' => 'events',			'action' => 'organizations'),	array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('研究会場用データ',	array('controller' => 'events',			'action' => 'venues'),			array('escape' => false, 'class' => 'dropdown-item')); ?>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CSV</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<?php echo $this->Html->link('CSVダウンロード',			array('controller' => 'events',			'action' => 'csv'),			array('escape' => false, 'class' => 'dropdown-item')); ?>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">経費</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<?php echo $this->Html->link('経費一覧（企画申請時）', array('controller' => 'expenses', 'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('経費一覧（ASK取込後時）', array('controller' => 'expenses', 'action' => 'index2'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('年度別経費', array('controller' => 'expenses', 'action' => 'fiscal'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('ASKデータ取り込み', array('controller' => 'expenses', 'action' => 'upload'), array('escape' => false, 'class' => 'dropdown-item')); ?>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">報告書管理</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<?php echo $this->Html->link('報告書一覧',			array('controller' => 'events',			'action' => 'report_list'), array('escape' => false, 'class' => 'dropdown-item')); ?>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">マスタ管理</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<?php echo $this->Html->link('所属管理',			array('controller' => 'affiliations',	'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('会場管理',			array('controller' => 'venues',			'action' => 'venue_list'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('課目管理',			array('controller' => 'items',			'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
					<?php echo $this->Html->link('管理者管理',			array('controller' => 'admins',			'action' => 'index'), array('escape' => false, 'class' => 'dropdown-item')); ?>
				</div>
			</li>
			
			<li class="nav-item">
				<?php echo $this->Html->link('ログアウト', array('controller' => 'admins', 'action' => 'logout'), array('class' => 'btn btn-outline-warning')); ?>
			</li>
		</ul>
	</div>
</nav>
</header>