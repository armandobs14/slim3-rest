<?php

$settings = array(
	'connections' => array(
		'default' => array(
			'driver'    => 'mysql',
			'host'      => 'mysql',
			'database'  => 'database',
			'username'  => 'root',
			'password'  => 'password',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => ''
		)
	)
);
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection($settings['connections']['default']);
$capsule->bootEloquent();
