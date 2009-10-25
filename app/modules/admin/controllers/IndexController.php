<?php

class Admin_IndexController extends Brightfame_Controller_Admin
{
    public function indexAction()
    {
        $this->_checkAuth();
    }
}