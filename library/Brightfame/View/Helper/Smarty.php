<?php
 
class Brightfame_View_Helper_Smarty
       extends Zend_Controller_Action_Helper_Abstract {
 
    private $_view = null;
 
    public function setView($view)
    {
        $this->_view = $view;
    }
 
    public function init()
    {
        $this->getActionController()->view = $this->_view;
    }
}