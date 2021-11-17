<?php
	Router::parseExtensions('pdf');
	Router::connect('/', array('controller' => 'databases', 'action' => 'index'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	CakePlugin::routes();
	require CAKE . 'Config' . DS . 'routes.php';
