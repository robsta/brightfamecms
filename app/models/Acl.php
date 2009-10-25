<?php

class Acl
{
    public function getRoles()
    {
        return $this->db->fetchAll("Select * from acl_roles order by parent_id asc");
    }
        
    public function getAccess()
    {
        return $this->db->fetchAll("Select * from acl_access");
    }
}