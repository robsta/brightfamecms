<?php

/** Zend_Controller_Router_Route_Module */
require_once 'Zend/Controller/Router/Route/Module.php';
 
/**
 * Brightfame Page Route
 *
 * @category    Brightfame
 * @package     Brightfame_Route
 * @subpackage  Page
 * @license     New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version     $Id: $
 */
class Brightfame_Route_Page extends Zend_Controller_Router_Route_Module
{
    /**
     * Class Constructor
     * 
     * @param Zend_Controller_Dispatcher_Interface $dispatcher
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function __construct(Zend_Controller_Dispatcher_Interface $dispatcher, Zend_Controller_Request_Abstract $request)
    {
        $request->setControllerKey('page');
        parent::__construct(array('module' => '', 'page' => '', 'action' => ''), $dispatcher, $request);
        $this->_controllerKey = 'page';
    }
}