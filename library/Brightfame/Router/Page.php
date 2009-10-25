<?php

/** Zend_Controller_Router_Rewrite */
require_once 'Zend/Controller/Router/Rewrite.php';

/**
 * Brightfame Page Router
 *
 * @category    Brightfame
 * @package     Brightfame_Router
 * @subpackage  Page
 * @license     New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version     $Id: $
 */
class Brightfame_Router_Page extends Zend_Controller_Router_Rewrite
{
    public function route(Zend_Controller_Request_Abstract $request)
    {
        // Let the Rewrite router route the request first
        $request = parent::route($request);

        if ($request->getParam('page') == '') {
            // If the page param isn't set, route to default page and controller
            $defaultPage = Brightfame_Page_Manager::getInstance()->getDefaultPage();

            //$request->setControllerName($defaultPage->pageType->controller);
            //$request->setParam('page',$defaultPage->page);
        } else {
            // Route to current page's controller
            $page = Brightfame_Page_Manager::getInstance()->getPage($request->getParam('page'));
            //$request->setControllerName($page->pageType->controller);
        }
        
        return $request;
    }
}