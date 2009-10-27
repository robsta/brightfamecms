<?php
// Define base path obtainable throughout the whole application
defined('BASE_PATH')
    or define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));

// Define path to application directory
defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', BASE_PATH . '/app');
    
// Define application environment
defined('APPLICATION_ENV')
    or define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Set include path to Zend (and other) libraries
set_include_path(BASE_PATH . '/library' .
    PATH_SEPARATOR . APPLICATION_PATH . '/models' .
    PATH_SEPARATOR . get_include_path() .
    PATH_SEPARATOR . '.'
);

// Require Zend_Application
require_once 'Zend/Application.php';

// Create application
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/application.ini'
);
// Bootstrap, and run application
$application->bootstrap()
            ->run();