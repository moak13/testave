<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
	'driver' => 'mysql',
	'host' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'ave_voting',
	'charset' => 'latin1',
	'collation' => 'latin1_swedish_ci',
	'prefix' => ''
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
?>