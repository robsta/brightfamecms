<?php

class IndexController extends Brightfame_Controller_Action
{
    public function init()
    {
        parent::init();
    }
        
    public function indexAction()
    {
        $pages_model = new Page();
        $pages = $pages_model->getFrontpage();
        $myrole = isset($this->user->role)?$this->user->role:'Guests';
        $x = 0;
        foreach ($pages as $page) {
            $role = $page['role'];
            $this->_setAccess($role,"page:{$page['id']}");
                
            if ($this->_isAllowed($myrole,"page:{$page['id']}") > 0) {
                $this->view->page = $page;
                $x++;
            }
            if ($x == 1) {
                return;
            }
        }
    }
}