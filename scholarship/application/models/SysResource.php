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
        $result = $this->fetchAll($select)->toArray();
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

}

