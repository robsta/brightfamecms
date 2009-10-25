<?php

class Brightfame_Acl_Role_Administrator implements Zend_Acl_Role_Interface
{
    public function getRoleId()
    {
        return 'administrator';
    }
}