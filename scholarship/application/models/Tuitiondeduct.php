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
    public function get_deduct_list($arrearage = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("d"=>$this->_name));
//         $select->joinLeft(array("i"=>"tb_tuition_info"), "i.tuition_id = d.tuition_id");
        $select->joinLeft(array("s"=>"tb_stu_info"), "s.stu_id = d.stu_id");
        $select->order("d.check_datetime desc");
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


}

