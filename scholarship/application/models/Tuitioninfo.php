<?php

class Application_Model_Tuitioninfo extends Zend_Db_Table_Abstract
{

    protected $_primary = "tuition_id";
    
    protected $_name = "tb_tuition_info";
     
    protected $_tuition_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_tuition_list($where_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("t"=>$this->_name));
        $select->joinLeft(array("d"=>"tb_dept_info"), "t.dept_code = d.dept_code", array("dept_name", "dept_full_name", "parent_code", "deptcode04"));
        $select->joinLeft(array("s"=>"tb_stu_type"), "t.stu_type = s.stu_type_code");
        $select->order("d.dept_name asc");
        $select->order("t.grade asc");
        $select->order("t.year desc");
       if (null !== $where_array) {
            foreach ($where_array as $key=>$value) {
                $select->where("{$key} = ?", $value);
            }
        }

        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_tuition_record($where_array = null)
    {
        $select = $this->select();
        $select->from(array("t"=>$this->_name));
        if (null !== $where_array) {
            foreach ($where_array as $where) {
                $select->where($where);
            }
        }
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function is_tuition_exist($tuition_id = null, $dept_code = null, $grade = null, $year = null, $stu_type = null)
    {
        $select = $this->select();
        $select->from($this->_name);
        if (null !== $tuition_id) {
            $select->where("tuition_id = ?", $tuition_id);
        }
        if (null !== $dept_code) {
            $select->where("dept_code = ?", $dept_code);
        }
        if (null !== $grade) {
            $select->where("grade = ?", $grade);
        }
        if (null !== $year) {
            $select->where("year = ?", $year);
        }
        if (null !== $stu_type) {
            $select->where("stu_type = ?", $stu_type);
        }
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $tuition_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("tuition_id = ?", $tuition_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($tuition_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("tuition_id = ?", $tuition_id);
        return $this->delete($where);
    }

}

