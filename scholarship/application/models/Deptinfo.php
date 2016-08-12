<?php

class Application_Model_Deptinfo extends Zend_Db_Table_Abstract
{

    protected $_primary = "dept_code";
    
    protected $_name = "tb_dept_info";
     
    protected $_dept_code;
    
    public function __construct ()
    {
//         $this->_user_id = $user_id;
        parent::__construct();
    }
    
//     public function get_dept_list($parent_code = null)
//     {
//         $select = $this->select()->setIntegrityCheck(false);
//         $select->from(array("d"=>$this->_name), array("dept_code", "dept_name", "dept_full_name"))
//             ->joinLeft(array("p"=>$this->_name), "d.parent_code = p.dept_code", array("parent_name"=>"dept_name"));
//         $select->order("p.parent_code asc");
//         $select->order("d.dept_code asc");
//         if (null !== $parent_code) {
//             $select->where("p.parent_code = ?", $parent_code);
//         }
//         $result = $this->fetchAll($select)->toArray();
//         return $result;
//     }
    
    public function get_dept_list($parent_code = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("d"=>$this->_name))//院系
        ->joinLeft(array("p"=>$this->_name), "d.parent_code = p.dept_code", array("parent_name"=>"dept_name"));//学院
//         ->joinLeft(array("r"=>$this->_name), "c.parent_code = r.dept_code", null);//教学科研
        $select->order("p.dept_code asc");
        $select->order("d.dept_code asc");
        if (null !== $parent_code) {
            $select->where("p.parent_code = ?", $parent_code);
        }
        $select->where("d.grade = '4'");
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
    
    public function update_record($data, $dept_code)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("dept_code = ?", $dept_code);
        return $this->update($data, $where);
    }

}

