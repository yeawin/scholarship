<?php

class Application_Model_SysResource extends Zend_Db_Table_Abstract
{

    protected $_primary = "resource_id";
    
    protected $_name = "tb_sys_resource";
     
    protected $_resource_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_resource_list()
    {
        $select = $this->select()->distinct(true);
        $select->from(array("r"=>$this->_name));
        $select->order("r.resource_name asc");
        $result = $this->fetchAll($select)->toArray();
        return $result;
    }
    
    public function get_resource_name_list()
    {
        $select = $this->select()->distinct(true);
        $select->from(array("r"=>$this->_name), array("resource_name"));
        $select->order("r.resource_name asc");
        $result = $this->fetchAll($select)->toArray();
        return $result;
    }
    
    public function get_resource_record($resource_id)
    {
        $select = $this->select()->distinct(true);
        $select->from(array("a"=>$this->_name));
        $select->where("a.resource_id = ?", $resource_id);
        $result = $this->fetchRow($select)->toArray();
        return $result;
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $resource_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("resource_id = ?", $resource_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($resource_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("resource_id = ?", $resource_id);
        return $this->delete($where);
    }

    public function is_resource_name_exist($resource_name)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("resource_name = ?", $resource_name);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function is_resource_exist($resource_id, $resource_name)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("resource_name = ?", $resource_name);
        $select->where("resource_id = ?", $resource_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
}

