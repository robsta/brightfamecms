<?php

Class PageController extends Brightfame_Controller_Action
{
    // 1. Catch & process overloaded actions.
    public function __call($name, $arguments)
    {
        $this->_setCustomView('page');
        $pages = new Page();
        $id= $this->_getParam("action");
        if($id == 'view') $id = $this->_getParam("id");
       
        $page = $pages->getPage($id);
       

        if(!$page['id'])
        {
                $this->_redirect('/index', 'Page not Found');
        }
        $myrole = isset($this->user->role)?$this->user->role:'Guest';
        $role = $page['role'];
        $this->_setAccess($role,"page:{$page['id']}");
        if( $this->_isAllowed($myrole,"page:{$page['id']}") < 1)
        {
                $this->_redirect('/index', 'Not Authorised to view this content');
        }
        $this->view->page = $page;
        $this->view->headMeta()->appendName('keywords', $page['meta_keywords']);
        $this->view->headMeta()->appendName('description', $page['meta_description']);
        $this->view->headTitle($page['title']);
    }
}