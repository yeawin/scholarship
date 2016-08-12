<?php

class Application_Model_User extends Zend_Db_Table_Abstract
{
    protected $_primary = "user_id";
    
    protected $_name = "tb_users";
     
    protected $_user_id;
    
    public function __construct ()
    {
        parent::__construct();
    }

    public function get_user_record($user_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("user_id = ?", $user_id);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_stu_info($stu_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("u"=>$this->_name));
        $select->joinLeft(array("s"=>"tb_stu_info"), "s.stu_id = u.user_id");
        $select->joinLeft(array("d"=>"tb_dept_info"), "d.dept_code = s.dept_code", array("dept_name", "dept_full_name"));
        $select->where("u.user_id = ?", $stu_id);
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
    public function is_user_exist($user_id)
    {
        $select = $this->select();
        $select->from($this->_name, array("user_id"));
        $select->where("user_id = ?", $user_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    /**
     * 是否角色是否为空
     * @param unknown $stu_id
     */
    public function is_user_type_exist($type_code)
    {
        $select = $this->select();
        $select->from($this->_name, array("user_id"));
        $select->where("type_code = ?", $type_code);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $user_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("user_id = ?", $user_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($user_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("user_id = ?", $user_id);
        return $this->delete($where);
    }
}

