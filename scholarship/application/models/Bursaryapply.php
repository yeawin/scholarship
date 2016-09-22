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

    public function get_apply_list($where_array = null, $order_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("a"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "a.scholarship_id = i.scholarship_id");
        $select->joinLeft(array("s"=>"tb_stu_info"), "a.stu_id = s.stu_id");
        $select->joinLeft(array("u"=>"tb_users"), "u.user_id = s.stu_id", array("email", "phone"));
        $select->joinLeft(array("d"=>"tb_dept_info"), "s.dept_code = d.dept_code");
        if (null !== $where_array) {
            foreach ($where_array as $where) {
                $select->where($where);
            }
        }
        if (null !== $order_array) {
            $select->order($order_array);
        }
//         echo $select->__toString();exit();
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    /**
     * 申请奖学金的进度
     * @param unknown $where_array
     * @param unknown $order_array
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function get_apply_checked_progress($where_array = null, $order_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("f"=>"tb_scholarship_flow"), array("flow_id", "flow_name", "scholarship_id", "check_role_id", "parent_id", "flow_order"));//流程信息
        $select->joinLeft(array("r"=>"tb_scholarship_review"), "r.flow_id = f.flow_id", array("review_id", "review_time", "review_pass", "reviewer", "review_apply_id"=>"apply_id"));//审核信息
        $select->joinLeft(array("a"=>"tb_scholarship_apply"), "a.scholarship_id = f.scholarship_id", array("apply_id", "stu_id", "apply_time", "is_pass", "is_paid"));   //申请信息
//         $select->joinLeft(array("i"=>"tb_scholarship_info"), "a.scholarship_id = i.scholarship_id");
        
        
        if (null !== $where_array) {
            foreach ($where_array as $key=>$value) {
                $select->where("{$key} = ?", $value);
            }
        }
        if (null !== $order_array) {
            $select->order($order_array);
        }
//                 echo $select->__toString();
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    public function get_apply_record($where_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("a"=>$this->_name));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "a.scholarship_id = i.scholarship_id");
        if (null !== $where_array) {
            foreach ($where_array as $key=>$value) {
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

