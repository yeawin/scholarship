<?php

class Application_Model_SysResourcePrivilege extends Zend_Db_Table_Abstract
{

    protected $_primary = "privilege_id";
    
    protected $_name = "tb_sys_resource_privilege";
     
    protected $_privilege_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_privilege_list($type_code = null, $privilege = null, $resource_id = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("p"=>$this->_name), array("type_code", "privilege"));
        $select->joinLeft(array("a"=>"tb_sys_resource_action"), "p.action_id = a.action_id", array("action_name", "action_comment"));
        $select->joinLeft(array("r"=>"tb_sys_resource"), "r.resource_id = a.resource_id", array("resource_name", "resource_comment"));
        if (null !== $type_code) {
            $select->where("p.type_code = ?", $type_code);
        }
        if (true == $privilege) {
            $select->where("p.privilege = ?", '1');
        }
        if (null !== $resource_id) {
            $select->where("p.resource_id = ?", $resource_id);
        }
        $select->order("r.resource_name");
        $result = $this->fetchAll($select)->toArray();
        return $result;
    }
    
    public function get_privilege_action_id_list($type_code = null, $privilege = null, $resource_id = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("p"=>$this->_name), array("action_id"));
        $select->joinLeft(array("a"=>"tb_sys_resource_action"), "p.action_id = a.action_id", null);
        $select->joinLeft(array("r"=>"tb_sys_resource"), "r.resource_id = a.resource_id", null);
        if (null !== $type_code) {
            $select->where("p.type_code = ?", $type_code);
        }
        if (true == $privilege) {
            $select->where("p.privilege = ?", '1');
        }
        if (null !== $resource_id) {
            $select->where("p.resource_id = ?", $resource_id);
        }
        $select->order("r.resource_name");
        $result = $this->fetchAll($select)->toArray();
        return $result;
    }
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $privilege_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("privilege_id = ?", $privilege_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($privilege_id = null, $resource_id = null, $type_code = null)
    {
        $db = $this->getDefaultAdapter();
        $i = 0;
        if (null !== $privilege_id) {
            $where[$i++] = $db->quoteInto("privilege_id = ?", $privilege_id);
        }
        if (null !== $resource_id) {
            $where[$i++] = $db->quoteInto("resource_id = ?", $resource_id);
        }
        if (null !== $type_code) {
            $where[$i++] = $db->quoteInto("type_code = ?", $type_code);
        }
        return $this->delete($where);
    }
    
    public function is_action_privilege_exist($action_id = null, $resource_id = null)
    {
        $select = $this->select();
        $select->from(array("p"=>$this->_name));
        $select->joinLeft(array("a"=>"tb_sys_resource_action"), "a.action_id = p.action_id", array("action_name", "action_comment", "resource_id"));
        if (null != $action_id) {
            $select->where("p.action_id = ?", $action_id);
        }
        if (null != $resource_id) {
            $select->where("a.resource_id = ?", $resource_id);
        }
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
}

