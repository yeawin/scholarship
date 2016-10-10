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
    
    public function get_privilege_list($type_code = null, $privilege = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("p"=>$this->_name), array("type_code", "privilege"));
        $select->joinLeft(array("a"=>"tb_sys_resource_action"), "p.action_id = a.action_id", array("action_name", "action_comment"));
        $select->joinLeft(array("r"=>"tb_sys_resource"), "r.rescource_id = a.rescource_id", array("resource_name", "resource_comment"));
        if (null !== $type_code) {
            $select->where("p.type_code = ?", $type_code);
        }
        if (true == $type_code) {
            $select->where("p.privilege = ?", '1');
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
    
    public function delete_record($privilege_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("privilege_id = ?", $privilege_id);
        return $this->delete($where);
    }
}

