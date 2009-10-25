<?php

class Page extends Database
{
    public function getPage($id = 0)
    {
        return $this->db->fetchRow("Select * from pages where id='$id' and published = '1' and deleted='0'");
    }
        
    public function getFrontpage()
    {
        return $this->db->fetchAll("Select * from pages where published = '1' and frontpage = '1' and deleted='0' order by id desc");
    }
        
    public function listPages($page = 0)
    {
        $selection = $this->db->select()->from('pages', array('id','title','role','createdate','moddate','published'))->where('deleted=?',0)->order('title');
        $paginator = Zend_Paginator::factory($selection);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(25);
        $paginator->setPageRange(10);
        return $paginator;
    }
        
    public function save($page)
    {
        $id = $page['id'];
        if($id > 0) {
            $page['moddate'] = DATE("Y-m-d H:i:s");
            return $this->db->update('pages', $page, "id='" . $page['id'] . "'");
        } else {
            $page['createdate'] = DATE("Y-m-d H:i:s");
            return $this->db->insert('pages', $page);
        }
    }
}
