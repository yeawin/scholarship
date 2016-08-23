<?php

class Application_Model_StuBank extends Zend_Db_Table_Abstract
{

    protected $_primary = "card_id";
    
    protected $_name = "tb_stu_bank";
     
    protected $_card_id;
    
    public function get_stu_bank_info($stu_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>"tb_stu_info"), array("stu_no"=>"stu_id", "stu_name"))->setIntegrityCheck(false);
        $select->joinLeft(array("d"=>"tb_dept_info"), "i.dept_code = d.dept_code", array("dept_name", "dept_full_name", "parent_code", "deptcode04"));
        $select->joinLeft(array("b"=>"tb_stu_bank"), "i.stu_id = b.stu_id");
        $select->where("i.stu_id = ?", $stu_id);
        $result = $this->fetchRow($select);
        
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_stu_bank_list($where_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>"tb_stu_info"), array("stu_no"=>"stu_id", "stu_name"))->setIntegrityCheck(false);
        $select->joinLeft(array("d"=>"tb_dept_info"), "i.dept_code = d.dept_code", array("dept_name", "dept_full_name", "parent_code", "deptcode04"));
        $select->joinLeft(array("b"=>"tb_stu_bank"), "i.stu_id = b.stu_id");
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
    
    public function delete_record($card_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("card_id = ?", $card_id);
        return $this->delete($where);
    }
    
    /**
     * 学生的银行卡是否存在
     * @param unknown $stu_id
     */
    public function is_bank_cardno_exist($stu_id)
    {
        $select = $this->select();
        $select->from("tb_stu_bank", array("stu_id"));
        $select->where("stu_id = ?", $stu_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    

}

