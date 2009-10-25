<?php

defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));
 
defined('LIBRARY_PATH')
    or define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../../lib'));
 
$paths = array(
    APPLICATION_PATH . '/models',
    realpath(APPLICATION_PATH . '/../library'),
    LIBRARY_PATH,
    '.',
);
 
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths));
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Brightfame_');
 
include dirname(__FILE__) . '/../app/bootstrap.php';
 
Zend_Controller_Front::getInstance()->dispatch();