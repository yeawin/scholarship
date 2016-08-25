<?php

class Application_Model_Tuitionhistory extends Zend_Db_Table_Abstract
{

    protected $_primary = "history_id";
    
    protected $_name = "tb_tuition_history";
     
    protected $_history_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_history_list($where_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("h"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_tuition_info"), "i.tuition_id = h.tuition_id");
        $select->joinLeft(array("s"=>"tb_stu_info"), "s.stu_id = h.stu_id");
        $select->order("h.datetime desc");
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
    
    public function get_tuition_record($tuition_id)
    {
        $select = $this->select();
        $select->from(array("t"=>$this->_name));
        $select->where("tuition_id = ?", $tuition_id);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function is_tuition_exist($tuition_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("tuition_id = ?", $tuition_id);
        $result = $this->fetchAll($select)->toArray();
        return (count($result) > 0);
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $tuition_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("tuition_id = ?", $tuition_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($tuition_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("tuition_id = ?", $tuition_id);
        return $this->delete($where);
    }

}

