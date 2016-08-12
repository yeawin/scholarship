<?php

class Application_Model_Bursary extends Zend_Db_Table_Abstract
{

    protected $_primary = "scholarship_id";
    
    protected $_name = "tb_scholarship_info";
     
    protected $_scholarship_id;
    
    public function __construct ()
    {
        parent::__construct();
    }

    public function get_scholarship_list($array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>"tb_scholarship_info"));
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
    
    public function get_scholarship_record($scholarship_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("i"=>"tb_scholarship_info"));
        $select->where("i.scholarship_id = ?", $scholarship_id);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
    
        return $result;
    }
    
    public function is_scholarship_exist($scholarship_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("scholarship_id = ?", $scholarship_id);
        $result = $this->fetchAll($select)->toArray();
        return (count($result) > 0);
    }
    
    public function is_scholarship_type_exist($scholarship_type_code)
    {
        $select = $this->select();
        $select->from($this->_name, array("scholarship_id"));
        $select->where("scholarship_type_code = ?", $scholarship_type_code);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $stu_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("scholarship_id = ?", $stu_id);
        return $this->update($data, $where);
    }
}

