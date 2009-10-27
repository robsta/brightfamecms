<?php

class IndexController extends Zend_Controller_Action
{
    public $page;
    
    public function indexAction()
    {
        // create the new page object
        $this->page = Brightfame_Builder::loadPage(null, 'initialize.xml');
        
        // load the data
        Brightfame_Builder::loadPage(null, 'load_data.xml', $this->page);

        // load the view
        Brightfame_Builder::loadPage(null, 'load_view.xml', $this->page, $this->view);
        
        // render the page
        $this->view->page = $this->page;
        $this->view->layout()->page = $this->page->getParam('xhtml');
    }
}