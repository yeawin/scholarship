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
        $select = $this->select()->distinct(true);
        $select->from(array("a"=>$this->_name));
        if (null !== $resource_id) {
            $select->where("rescource_id = ?", $resource_id);
        }
        $result = $this->fetchAll($select)->toArray();
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

}

