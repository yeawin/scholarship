<?php

class Application_Model_Bursarycondition extends Zend_Db_Table_Abstract
{

    protected $_primary = "condition_id";
    
    protected $_name = "tb_scholarship_condition";
     
    protected $_condition_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    /**
     * 流程列表
     * @param unknown $scholarship_id 奖学金号
     * @param unknown $array 其他条件
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function get_scholarship_condition_list($scholarship_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("c"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = c.scholarship_id");
        $select->where("c.scholarship_id = ?", $scholarship_id);
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_scholarship_condition_record($condition_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("c"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = c.scholarship_id");
        $select->joinLeft(array("t"=>"tb_scholarship_type"), "i.scholarship_type_code = t.scholarship_type_code");
        $select->where("c.condition_id = ?", $condition_id);
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
    
    public function update_record($data, $condition_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("condition_id = ?", $condition_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($condition_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("condition_id = ?", $condition_id);
        return $this->delete($where);
    }
}

