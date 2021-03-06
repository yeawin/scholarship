<?php

class Application_Model_Tuitiondeduct extends Zend_Db_Table_Abstract
{

    protected $_primary = "stu_id";
    
    protected $_name = "tb_tuition_deduct";
     
    protected $_stu_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    /**
     * 
     * @param unknown $arrearage 是否欠费
     */
    public function get_deduct_list($where_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("d"=>$this->_name));
//         $select->joinLeft(array("i"=>"tb_tuition_info"), "i.tuition_id = d.tuition_id");
        $select->joinLeft(array("s"=>"tb_stu_info"), "s.stu_id = d.stu_id");
        $select->joinLeft(array("dept"=>"tb_dept_info"), "s.dept_code = dept.dept_code");
        $select->order("d.check_datetime desc");
        if (null !== $where_array) {
            foreach ($where_array as $where) {
                $select->where($where);
            }
        }
//         if (true === $arrearage) {
//             $select->where("d.tuition_1 > 0.0");
//             $select->orWhere("d.tuition_2 > 0.0");
//             $select->orWhere("d.tuition_3 > 0.0");
//         }
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_deduct_record($stu_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("d"=>$this->_name));
        $select->where("stu_id = ?", $stu_id);
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
     * 学生费用记录存在
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
}

