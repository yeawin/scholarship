<?php
/**
 * 奖学金申请
 * @author Administrator
 *
 */
class Application_Model_Bursaryapply extends Zend_Db_Table_Abstract
{

    protected $_primary = "apply_id";
    
    protected $_name = "tb_scholarship_apply";
     
    protected $_apply_id;
    
    public function __construct ()
    {
        parent::__construct();
    }

    public function get_apply_list($array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("a"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "a.scholarship_id = i.scholarship_id");
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
    
    public function get_apply_record($array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("a"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "a.scholarship_id = i.scholarship_id");
        if (null !== $array) {
            foreach ($array as $key=>$value) {
                $select->where("{$key} = ?", $value);
            }
        }
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
    
        return $result;
    }
    
    public function is_applied($array = null )
    {
        $select = $this->select();
        $select->from($this->_name);
        if (null !== $array) {
            foreach ($array as $key=>$value) {
                $select->where("{$key} = ?", $value);
            }
        }
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function is_scholarship_exist($scholarship_id, $stu_id = null)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("scholarship_id = ?", $scholarship_id);
        if (null !== $stu_id) {
            $select->where("stu_id = ?", $stu_id);
        }
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $apply_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("apply_id = ?", $apply_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($apply_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("apply_id = ?", $apply_id);
        return $this->delete($where);
    }

}

