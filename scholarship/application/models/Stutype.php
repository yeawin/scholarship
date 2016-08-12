<?php

class Application_Model_Stutype extends Zend_Db_Table_Abstract
{

    protected $_primary = "stu_type_code";
    
    protected $_name = "tb_stu_type";
     
    protected $_stu_type_code;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_stu_type_list()
    {
        $select = $this->select();
        $select->from(array("t"=>$this->_name));
        $result = $this->fetchAll($select)->toArray();
        return $result;
    }
    
    public function get_stu_type($stu_type_code)
    {
        $select = $this->select();
        $select->from(array("t"=>$this->_name));
        $select->where("stu_type_code = ?", $stu_type_code);
        $result = $this->fetchRow($select)->toArray();
        return $result;
    }
    
    public function is_stu_type_exist($stu_type_name)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("stu_type_name = ?", $stu_type_name);
        $result = $this->fetchAll($select)->toArray();
        return (count($result) > 0);
    }
    
    public function update_record($data, $stu_type_code)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("stu_type_code = ?", $stu_type_code);
        return $this->update($data, $where);
    }
    
    public function delete_record($stu_type_code)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("stu_type_code = ?", $stu_type_code);
        return $this->delete($where);
    }

}

