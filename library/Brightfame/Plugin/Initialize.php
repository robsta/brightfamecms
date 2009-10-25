<?php

/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';
 
/**
 * Plugin to initialize application state
 *
 * @category    Brightfame
 * @package     Brightfame_Plugin
 * @license     New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version     $Id: $
 */
class Brightfame_Plugin_Initialize extends Zend_Controller_Plugin_Abstract
{
    /**
     * Constructor
     *
     * @param string $env Application environment
     * @return void
     */
    public function __construct($env = 'production')
    {
        $this->env = $env;
        $this->initConfig();
    }
 
    /**
     * Route Startup handler
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->front = Zend_Controller_Front::getInstance();
        $this->initControllers()
             ->initView()
             ->initLog()
             ->initDb()
             ->initPlugins();
    }
 
    /**
     * PreDispatch actions
     *
     * Initialize module bootstraps
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function initModules()
    {
        $modules = $this->front->getControllerDirectory();
        foreach ($modules as $module => $dir) {
            if ('default' == $module) {
                continue;
            }
            $bootstrapFile = dirname($dir) . '/Bootstrap.php';
            $class = ucfirst($module) . '_Bootstrap';
            if (Zend_Loader::loadFile('Bootstrap.php', dirname($dir))
                && class_exists($class)
            ) {
                $bootstrap = new $class;
                $bootstrap->setAppBootstrap($this);
                $bootstrap->bootstrap();
            }
        }
        return $this;
    }
 
    /**
     * Initialize configuration
     *
     * @return Brightfame_Plugin_Initialize
     */
    public function initConfig()
    {
        $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/config/application.ini', $this->env, true);
        Zend_Registry::set('config', $this->config);
        return $this;
    }
 
    /**
     * Initialize controller directories
     *
     * @return Brightfame_Plugin_Initialize
     */
    public function initControllers()
    {
        $this->front->setDefaultModule('frontend');
        $this->front->addModuleDirectory($this->config->appPath . '/modules');
        $this->front->setParam('noViewRenderer', true);
        
        return $this;
    }
 
    /**
     * Initialize logger(s)
     *
     * @return Brightfame_Plugin_Initialize
     */
    public function initLog()
    {
        $writer = new Zend_Log_Writer_Firebug();
        $log = new Zend_Log($writer);
 
        $writer->setPriorityStyle(8, 'TABLE');
        $log->addPriority('TABLE', 8);
 
        Zend_Registry::set('log', $log);
        return $this;
    }
 
    /**
     * Initialize database
     *
     * @return Brightfame_Plugin_Initialize
     */
    public function initDb()
    {
        $config = $this->config;
        $db = Zend_Db::factory($config->database);
        
        if ($this->env == 'development') {
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $db->setProfiler($profiler);    
        }
        
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        //Zend_Db_Table_Abstract::setDefaultMetadataCache('dbmetacache');
        Zend_Registry::set('db', $db);
 
        return $this;
    }
 
    /**
     * Initialize view and layouts
     *
     * @return Brightfame_Plugin_Initialize
     */
    public function initView()
    {
        $config = $this->config;
    
        // initialize smarty
        require_once 'Smarty/Smarty.class.php';
        $smarty = new Smarty();
        $smarty->template_dir = $config->smarty->template_dir;
        $smarty->compile_dir = $config->smarty->compile_dir;
        $smarty->config_dir = $config->smarty->config_dir;
        $smarty->cache_dir = $config->smarty->cache_dir;
 
        $view = new Brightfame_View_Smarty();
        $view->setEngine($smarty);
 
        $viewManager = new Brightfame_View_Helper_Smarty();
        $viewManager->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewManager);
 
        Zend_Registry::set('view', $view);
        return $this;
    }
    
    /**
     * Initialize plugins
     *
     * @return Brightfame_Plugin_Initialize
     */
    public function initPlugins()
    {
        $class = new Brightfame_Plugin_Auth();
        $this->front->registerPlugin($class);
        return $this;
    }
}