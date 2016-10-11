<?php

class Application_Model_Stucondition extends Zend_Db_Table_Abstract
{

    protected $_primary = "stu_id";
    
    protected $_name = "tb_stu_conditions";
     
    protected $_stu_id;
    
    public function __construct ()
    {
        parent::__construct();
    }

    public function get_condition_list($array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>"tb_stu_info"), array("stu_no"=>"stu_id", "stu_name", "stu_sex", "stu_type", "stu_grade"));
        $select->joinLeft(array("c"=>"tb_stu_conditions"), "i.stu_id = c.stu_id");
        $select->joinLeft(array("t"=>"tb_stu_type"), "i.stu_type = t.stu_type_code");
        $select->joinLeft(array("d"=>"tb_dept_info"), "i.dept_code = d.dept_code", array("dept_name", "dept_full_name", "parent_code", "deptcode04"));
        if (null !== $array) {
            foreach ($array as $where) {
                $select->where($where);
            }
        }
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_condition_record($stu_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>"tb_stu_info"), array("stu_no"=>"stu_id", "stu_name", "stu_sex", "stu_type", "stu_grade"));
        $select->joinLeft(array("c"=>"tb_stu_conditions"), "i.stu_id = c.stu_id");
        $select->joinLeft(array("t"=>"tb_stu_type"), "i.stu_type = t.stu_type_code");
        $select->joinLeft(array("d"=>"tb_dept_info"), "i.dept_code = d.dept_code", array("dept_name", "dept_full_name", "parent_code", "deptcode04"));
        $select->where("i.stu_id = ?", $stu_id);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        
        return $result;
    }
    
    public function is_condition_exist($stu_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("stu_id = ?", $stu_id);
        $result = $this->fetchAll($select)->toArray();
        return (count($result) > 0);
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $stu_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("stu_id = ?", $stu_id);
        return $this->update($data, $where);
    }
}

