<?php

class Application_Model_Usertype extends Zend_Db_Table_Abstract
{
    protected $_primary = "type_code";
    
    protected $_name = "tb_user_type";
     
    protected $_type_code;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function get_type_list($except_type_code = null, $array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("t"=>$this->_name));
        $select->joinLeft(array("p"=>$this->_name), "t.parent_code = p.type_code", null);
        if (null !== $except_type_code) {
            $select->where("t.type_code != ?", $except_type_code);
        }
        if (null !== $array) {
            foreach ($array as $key=>$value) {
                $select->where("t.{$key} = ?", $value);
            }
        }
        $select->order("t.type_code asc");
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }

    public function get_type_record($type_code)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("t"=>$this->_name));
        $select->joinLeft(array("p"=>$this->_name), "t.parent_code = p.type_code", array("parent_name"=>"type_name"));
        $select->where("t.type_code = ?", $type_code);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    /**
     * 是否存在
     * @param unknown $stu_id
     */
    public function is_user_type_exist($type_code)
    {
        $select = $this->select();
        $select->from($this->_name, array("type_code"));
        $select->where("type_code = ?", $type_code);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    /**
     * 更新父级角色
     * @param unknown $data
     * @param unknown $type_code
     */
    public function update_record($data, $type_code)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("type_code = ?", $type_code);
        return $this->update($data, $where);
    }
    
    public function delete_record($type_code)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("type_code = ?", $type_code);
        return $this->delete($where);
    }

}

