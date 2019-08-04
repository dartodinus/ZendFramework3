<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' =>  [
    	'driver' => 'Pdo',
    	'dsn' 	 => 'pgsql:dbname=master;host=localhost;port=5432',
    	'username' => 'devel',
    	'password' => 'devel'
    ],
    
    /**
    'db' =>  [
    	'driver' => 'Pdo',
    	'dsn' 	 => 'mysql:dbname=zf3application;host=localhost;charset=utf8',
    	'username' => 'root',
    	'password' => ''
    ],
    
    'db' => [
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=zf3login;host=localhost',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"',
            PDO::ATTR_STRINGIFY_FETCHES => false
        ],
        'username' => 'root',
    	'password' => ''
    ],
    */
];
