<?php

class Brightfame_Acl_Role_Member implements Zend_Acl_Role_Interface
{
    public function getRoleId()
    {
        return 'member';
    }
}