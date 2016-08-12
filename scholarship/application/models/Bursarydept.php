<?php
/**
 * 奖学金的分布
 * @author Administrator
 *
 */
class Application_Model_Bursarydept extends Zend_Db_Table_Abstract
{

    protected $_primary = "scholarship_dept_id";
    
    protected $_name = "tb_scholarship_dept";
     
    protected $_scholarship_dept_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_scholarship_distribute($scholarship_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("d"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = d.scholarship_id");
        $select->joinLeft(array("c"=>"tb_dept_info"), "c.dept_code = d.dept_code", array("dept_name", "dept_full_name"));
        $select->where("d.scholarship_id = ?", $scholarship_id);
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function count_scholarship_dept($scholarship_id)
    {
        $select = $this->select();
        $select->from($this->_name, array("sum(scholarship_dept_num) as num"));
        $select->where("scholarship_id = ?", $scholarship_id);
        $result = $this->fetchRow($select);
        if ($result) {
            return $result["num"];
        }
        return 0;
    }
    
    public function get_scholarship_belong_dept($dept_code)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("d"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = d.scholarship_id");
        $select->joinLeft(array("c"=>"tb_college"), "c.dept_code = d.dept_code", array("dept_name", "dept_full_name"));
        $select->where("d.dept_code = ?", $dept_code);
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_scholarship_dept_record($scholarship_dept_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("d"=>"tb_scholarship_dept"));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = d.scholarship_id");
        $select->joinLeft(array("t"=>"tb_scholarship_type"), "t.scholarship_type_code = i.scholarship_type_code");
        $select->where("d.scholarship_dept_id = ?", $scholarship_dept_id);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function is_scholarship_distribute_exist($scholarship_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("scholarship_id = ?", $scholarship_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function is_scholarship_dept_distribute_exist($scholarship_id, $dept_code)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("scholarship_id = ?", $scholarship_id);
        $select->where("dept_code = ?", $dept_code);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function update_record($data, $scholarship_dept_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("scholarship_dept_id = ?", $scholarship_dept_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($scholarship_dept_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("scholarship_dept_id = ?", $scholarship_dept_id);
        return $this->delete($where);
    }


}

