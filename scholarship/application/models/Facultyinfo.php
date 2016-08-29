<?php

class Application_Model_Facultyinfo extends Zend_Db_Table_Abstract
{

    protected $_primary = "faculty_id";
    
    protected $_name = "tb_faculty_info";
     
    protected $_faculty_id;
    
    public function __construct ()
    {
        parent::__construct();
    }

    public function get_faculty_list($where_array = null, $order_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>$this->_name), array("faculty_id", "faculty_name", "faculty_sex", "dept_code"))->setIntegrityCheck(false);
        $select->joinLeft(array("d"=>"tb_dept_info"), "i.dept_code = d.dept_code", array("dept_name", "dept_full_name", "parent_code", "deptcode04"));
        if (null !== $where_array) {
            foreach ($where_array as $key=>$value) {
                $select->where("{$key} = ?", $value);
            }
        }
        if (null !== $order_array) {
            foreach ($order_array as $key=>$value) {
                $select->order("{$key} {$value}");
            }
        }
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_faculty_info($faculty_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>$this->_name), array("faculty_id", "faculty_name", "faculty_sex"))->setIntegrityCheck(false);
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
    
    public function update_record($data, $faculty_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("faculty_id = ?", $faculty_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($faculty_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("faculty_id = ?", $faculty_id);
        return $this->delete($where);
    }

    public function is_faculty_exist($faculty_id)
    {
        $select = $this->select();
        $select->from($this->_name, array("faculty_id"));
        $select->where("faculty_id = ?", $faculty_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }

}

