<?php

class Application_Model_Stuinfo extends Zend_Db_Table_Abstract
{

    protected $_primary = "stu_id";
    
    protected $_name = "tb_stu_info";
     
    protected $_stu_id;
    
    public function __construct ()
    {
        parent::__construct();
    }

    public function get_stu_list($array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>$this->_name), array("stu_no"=>"stu_id", "stu_name", "stu_sex", "stu_type", "stu_grade"))->setIntegrityCheck(false);
        $select->joinLeft(array("c"=>"tb_stu_conditions"), "i.stu_id = c.stu_id");
        $select->joinLeft(array("t"=>"tb_stu_type"), "i.stu_type = t.stu_type_code");
        $select->joinLeft(array("d"=>"tb_dept_info"), "i.dept_code = d.dept_code", array("dept_name", "dept_full_name", "parent_code", "deptcode04"));
        if (null !== $array) {
            foreach ($array as $key=>$value) {
                $select->where("{$key} = ?", $value);
            }
        }
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_stu_info($stu_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>$this->_name), array("stu_no"=>"stu_id", "stu_name", "stu_sex", "stu_type", "stu_grade"))->setIntegrityCheck(false);
        $select->joinLeft(array("c"=>"tb_stu_conditions"), "i.stu_id = c.stu_id");
        $select->joinLeft(array("t"=>"tb_stu_type"), "i.stu_type = t.stu_type_code");
        $select->joinLeft(array("d"=>"tb_dept_info"), "i.dept_code = d.dept_code", array("dept_name", "dept_full_name", "parent_code", "deptcode04"));
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
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
    
    public function delete_record($stu_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("stu_id = ?", $stu_id);
        return $this->delete($where);
    }

    /**
     * 学生是否存在
     * @param unknown $stu_id
     */
    public function is_stu_exist($stu_id)
    {
        $select = $this->select();
        $select->from($this->_name, array("stu_id"));
        $select->where("stu_id = ?", $stu_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    /**
     * 学生列表中是否存在该学生类别
     * @param unknown $stu_type_code
     * @return boolean
     */
    public function is_stu_type_exist($stu_type_code)
    {
        $select = $this->select();
        $select->from($this->_name, array("stu_id"));
        $select->where("stu_type = ?", $stu_type_code);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
}

