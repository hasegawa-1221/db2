<!DOCTYPE html>
<html lang="ja">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>企画・報告書管理システム</title>
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css', array('integrity' => 'sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb', 'crossorigin' => 'anonymous'));
	echo $this->Html->css('bootstrap-cover');
	echo $this->Html->css('font-awesome.min');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
</head>
<body>
<div class="site-wrapper">
	<div class="site-wrapper-inner">
		<div class="cover-container">
			<div class="masthead clearfix">
				<div class="inner">
					<?php /*
					<h3 class="masthead-brand">企画・報告書管理システム</h3>
					<nav class="nav nav-masthead">
						<a class="nav-link active" href="#">マスフォアインダストリ</a>
					</nav>
					*/ ?>
				</div>
			</div>
			<div class="inner cover text-left">
				<div class="text-center">
					<h1 class="cover-heading mb-5">企画・報告書管理システム</h1>
					<?php echo $this->Flash->render(); ?>
				</div>
				
				<?php echo $this->fetch('content'); ?>
			</div>
			<div class="mastfoot">
				<div class="inner">
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>
