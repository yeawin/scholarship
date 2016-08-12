<?php

class Application_Model_Courseinfo extends Zend_Db_Table_Abstract
{

    protected $_primary = "course_id";
    
    protected $_name = "tb_course_info";
     
    protected $_course_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_course_list()
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>$this->_name), array("course_id", "course_name", "dept_code"))
        ->joinLeft(array("d"=>"tb_college"), "i.dept_code = d.dept_code", array("dept_name", "parent_code", "deptcode04"));
        $select->order("dept_code asc");
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        //         var_dump($result);exit();
        return $result;
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $course_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("course_id = ?", $course_id);
        $this->update($data, $where);
    }
    

}

