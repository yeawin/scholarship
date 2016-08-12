<?php
/**
 * 奖学金类型
 * @author Administrator
 *
 */

class Application_Model_Bursarytype extends Zend_Db_Table_Abstract
{

    protected $_primary = "scholarship_type_code";
    
    protected $_name = "tb_scholarship_type";
     
    protected $_scholarship_type_code;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_scholarship_type_list()
    {
        $select = $this->select();
        $select->from(array("t"=>$this->_name));
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_scholarship_type($scholarship_type_code)
    {
        $select = $this->select();
        $select->from(array("t"=>$this->_name));
        $select->where("scholarship_type_code = ?", $scholarship_type_code);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function is_scholarship_type_exist($scholarship_type_name)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("scholarship_type_name = ?", $scholarship_type_name);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function update_record($data, $scholarship_type_code)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("scholarship_type_code = ?", $scholarship_type_code);
        return $this->update($data, $where);
    }
    
    public function delete_record($scholarship_type_code)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("scholarship_type_code = ?", $scholarship_type_code);
        return $this->delete($where);
    }


}

