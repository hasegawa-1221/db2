<div class="breadcrumbs">
	<?php
	if ( $this->action != 'index' )
	{
		echo $this->Html->getCrumbs(' &rsaquo; ', array(
			'text' => 'Home',
			'url' => '/',
			'escape' => false,
		));
	}
	?>
</div>
<br>