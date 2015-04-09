<?php

return array(
	'fetch' => PDO::FETCH_CLASS,
	'default' => 'mysql',
	'connections' => array(
		'mysql' => array(
			'driver'    => 'mysql',
			'host'      => $_ENV['DB_HOST'],
			'database'  => $_ENV['DB_DATABASE'],
			'username'  => $_ENV['DB_USER'],
			'password'  => $_ENV['DB_PASS'],
			//'charset'   => 'utf8',
			//'collation' => 'utf8_unicode_ci',
            'charset'   => 'Latin1',
            'collation' => 'latin1_swedish_ci',
			'prefix'    => '',
		),
	),
	'migrations' => 'migrations',
	'redis' => array(
		'cluster' => false,
		'default' => array(
			'host'     => $_ENV['REDIS_HOST'],
			'port'     => 6379,
			'database' => 0,
		),
	),

);
