<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

$PHP_SELF 	= $_SERVER['PHP_SELF'];
$URI 		= explode('/', $PHP_SELF);

// Define base path
if(count($URI) > 2):
	defined('BASEPATH')
    	|| define('BASEPATH', '/'.$URI[1]);
else:
	defined('BASEPATH')
    	|| define('BASEPATH', $URI[0]);
endif;

// Define path to home
defined('HOME_PUBLIC')
	|| define('HOME_PUBLIC', $_SERVER['DOCUMENT_ROOT'].BASEPATH);
	
// Define path to data directory
defined('DATA_DIR')
    || define('DATA_DIR', HOME_PUBLIC.'/data/');

// Define path to logs directory
defined('LOGS_DIR')
    || define('LOGS_DIR', HOME_PUBLIC.'/data/logs/');

// Define path to image directory
defined('IMAGES_DIR')
    || define('IMAGES_DIR', '/data/uploads');

// Define path to upload files
defined('UPLOAD_PATH')
    || define('UPLOAD_PATH', realpath(dirname(__FILE__) . '/data'));


// Composer autoloading
include __DIR__ . './vendor/autoload.php';

if (! class_exists(Application::class)) {
    throw new RuntimeException(
        "Unable to load application.\n"
        . "- Type `composer install` if you are developing locally.\n"
        . "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
        . "- Type `docker-compose run zf composer install` if you are using Docker.\n"
    );
}

// Retrieve configuration
$appConfig = require __DIR__ . './config/application.config.php';
if (file_exists(__DIR__ . './config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__ . './config/development.config.php');
}


// Run the application!
Application::init($appConfig)->run();
