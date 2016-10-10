<?php

class Application_Model_SysResourceAction extends Zend_Db_Table_Abstract
{

    protected $_primary = "action_id";
    
    protected $_name = "tb_sys_resource_action";
     
    protected $_action_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_action_list($resource_id = null)
    {
        $select = $this->select()->setIntegrityCheck(false)->distinct(true);
        $select->from(array("a"=>$this->_name));
        $select->joinLeft(array("r"=>"tb_sys_resource"), "a.resource_id = r.resource_id", array("resource_name", "resource_comment"));
        if (null !== $resource_id) {
            $select->where("a.resource_id = ?", $resource_id);
        }
        $select->order("a.resource_id");
        $select->order("a.action_name asc");
        $result = $this->fetchAll($select)->toArray();
        return $result;
    }
    
    public function get_action_record($action_id)
    {
        $select = $this->select()->setIntegrityCheck(false)->distinct(true);
        $select->from(array("a"=>$this->_name));
        $select->joinLeft(array("r"=>"tb_sys_resource"), "a.resource_id = r.resource_id", array("resource_name", "resource_comment"));
        $select->where("a.action_id = ?", $action_id);
        $result = $this->fetchRow($select)->toArray();
        return $result;
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $action_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("action_id = ?", $action_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($action_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("action_id = ?", $action_id);
        return $this->delete($where);
    }

    public function is_action_name_exist($resource_id, $action_name)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("resource_id = ?", $resource_id);
        $select->where("action_name = ?", $action_name);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
}

