<!DOCTYPE html>
<html lang="ja">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<title><?php echo $this->fetch('title'); ?></title>
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css', array('integrity' => 'sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb', 'crossorigin' => 'anonymous'));
	echo $this->Html->css('lightbox');
	echo $this->Html->css('bootstrap-theme');
	echo $this->Html->css('font-awesome.min');
	echo $this->Html->css('https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
	echo $this->Html->css('simplePagination');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	echo $this->Html->css('bootstrap-datepicker.min');
	echo $this->Html->css('summernote');
	echo $this->Html->css('summernote-bs4');
	?>
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

<?php echo $this->Element('admin/header'); ?>

<div class="container-fluid">
	<div class="row">
		<main role="main" class="col-12 pt-3">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</main>
	</div>
</div>

<div class="overlay"><div class="loader-wrapper"><div class="loader"></div></div></div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<?php echo $this->Html->script('bootstrap-datepicker.min'); ?>
<?php echo $this->Html->script('lightbox'); ?>
<?php echo $this->Html->script('jquery.simplePagination'); ?>
<?php echo $this->Html->script('admin'); ?>
<?php echo $this->Html->script('summernote'); ?>
<?php echo $this->Html->script('summernote-bs4'); ?>
<?php echo $this->Html->script('/js/lang/summernote-ja-JP'); ?>
<?php echo $this->Html->script('ajaxzip3'); ?>
<script>

$('.summernote').summernote({
    height: 300,
    fontNames: ["YuGothic","Yu Gothic","Hiragino Kaku Gothic Pro","Meiryo","sans-serif", "Arial","Arial Black","Comic Sans MS","Courier New","Helvetica Neue","Helvetica","Impact","Lucida Grande","Tahoma","Times New Roman","Verdana"],
	lang: "ja-JP",
});

</script>

</html>