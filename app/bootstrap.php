<?php

/**
 * Brightfame
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category   Brightfame
 * @package    Bootstrap
 * @copyright  Copyright (c) 2009 Rob Morgan. (http://brightfamecms.com)
 * @license    New BSD License
 * @version    $Id:$
 */

/**
 * Bootstrap of Brightfame CMS.
 * 
 * @category   Brightfame
 * @package    Bootstrap
 * @copyright  Copyright (c) 2009 Rob Morgan. (http://brightfamecms.com)
 * @license    New BSD License
 * @version    Release: @package_version@
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialize the autoloader
     *
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        // Ensure front controller instance is present
        $this->bootstrap('frontController');
        
        // Get frontController resource
        $this->_front = $this->getResource('frontController');

        // Add autoloader empty namespace
        $autoLoader =  new Zend_Loader_Autoloader_Resource(array(
            'basePath'      => APPLICATION_PATH,
            'namespace'     => '',
            'resourceTypes' => array(
                'form' => array(
                    'path'      => 'admin/forms/',
                    'namespace' => 'Admin_Form_',
                ),
                'model' => array(
                    'path'      => 'models/',
                    'namespace' => 'Model_'
                ),
            ),
        ));
        
        // Return it, so that it can be stored by the bootstrap
        return $autoLoader;
    }

    /**
     * Initialize the local php configuration
     *
     * @return void
     */
    protected function _initPhpConfig()
    {
    }

    /**
     * Initialize the site configuration
     *
     * @return Zend_Config_Xml
     */
    protected function _initConfig()
    {
        // Retrieve configuration from file
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/config/application.ini', APPLICATION_ENV);

        // Add config to the registry so it is available globally
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config);
        
        // Return it, so that it can be stored by the bootstrap
        return $config;
    }

    /**
     * Initialize the Cache
     *
     * @return Zend_Cache_Core
     */
    protected function _initCache()
    {
        // Cache options
        $frontendOptions = array(
           'lifetime' => 1200,                          // Cache lifetime of 20 minutes
           'automatic_serialization' => true,
        );
        
        $backendOptions = array(
            'lifetime' => 3600,                         // Cache lifetime of 1 hour
            'cache_dir' => BASE_PATH . '/tmp/cache/',   // Directory where to put the cache files
        );
        
        // Get a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
        
        // Return it, so that it can be stored by the bootstrap
        return $cache;
    }

    /**
     * Initialize the Database
     *
     * @return Zend_Db_Adapter_Abstract
     */
    protected function _initDb()
    {
        $this->bootstrap('config');
        // Get config resource
        $config = $this->getResource('config');

        // Setup database
        $db = Zend_Db::factory($config->database->adapter, $config->database->toArray());
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $db->query("SET NAMES 'utf8'");
        $db->query("SET CHARACTER SET 'utf8'");
        Zend_Db_Table::setDefaultAdapter($db);
        
        // Return it, so that it can be stored by the bootstrap
        return $db;
    }

    /**
     * Initialize the view
     *
     * @return Zend_View
     */
    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();

        // Set doctype and charset
        $view->doctype('XHTML1_TRANSITIONAL');
        $view->placeholder('charset')->set('utf-8');

        // Add the view to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Load digitalus helpers

        // base helpers
        $view->addHelperPath('Brightfame/View/Helper', 'Brightfame_View_Helper');
        $view->addHelperPath('Brightfame/Content/Control', 'Brightfame_Content_Control');

        $helperDirs = Brightfame_Directory::getDirectories(BASE_PATH . '/library/Digitalus/View/Helper');
        if (is_array($helperDirs)) {
            foreach ($helperDirs as $dir) {
                $view->addHelperPath(BASE_PATH . '/library/Digitalus/View/Helper/' . $dir, 'Digitalus_View_Helper_' . ucfirst($dir));
            }
        }
        $view->baseUrl = $this->_front->getBaseUrl();
        
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
    
    /**
     * Initialize the controllers
     *
     * @return void
     */
    protected function _initControllers()
    {
        // Setup core cms modules
        $this->_front->addControllerDirectory(APPLICATION_PATH . '/modules/admin/controllers', 'admin');
        $this->_front->addControllerDirectory(APPLICATION_PATH . '/modules/frontend/controllers', 'frontend');
    }
    
    /**
     * Initialize the CMS Routes
     * 
     * @return void
     */
    protected function _initRouter()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
    
        // Add some routes
        $router->addRoute('routeId', new Zend_Controller_Router_Route('route/definition/:param'));
        //...
    
        // Returns the router resource to bootstrap resource registry
        return $router;
    }
}