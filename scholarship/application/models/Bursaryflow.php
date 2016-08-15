<?php
/**
 * 奖学金流程
 * @author Administrator
 *
 */
class Application_Model_Bursaryflow extends Zend_Db_Table_Abstract
{

    protected $_primary = "flow_id";
    
    protected $_name = "tb_scholarship_flow";
     
    protected $_flow_id;
    
    public function __construct ()
    {
        parent::__construct();
    }
    
    /**
     * 流程列表
     * @param unknown $scholarship_id 奖学金号
     * @param unknown $except_flow_id 要排除的流程
     * @param unknown $array 其他条件
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function get_scholarship_flow_list($scholarship_id, $except_flow_id = null, $array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("f"=>$this->_name));
        $select->joinLeft(array("p"=>$this->_name), "f.parent_id = p.flow_id", array("parent_flow_name"=>"flow_name"));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = f.scholarship_id");
        $select->where("f.scholarship_id = ?", $scholarship_id);
        $select->order("f.flow_order");
        if (null !== $except_flow_id) {
            $select->where("f.flow_id != ?", $except_flow_id);
        }
        if (null !== $array) {
            foreach ($array as $key=>$value) {
                $select->where("{$key} != ?", $value);
            }
        }
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
//         var_dump($result);exit();
        return $result;
    }
    
    /**
     * 流程信息
     * @param unknown $flow_id
     */
    public function get_scholarship_flow_record($flow_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("f"=>$this->_name));
        $select->joinLeft(array("p"=>$this->_name), "f.parent_id = p.flow_id", array("parent_flow_name"=>"flow_name"));
        $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = f.scholarship_id");
        $select->joinLeft(array("t"=>"tb_scholarship_type"), "i.scholarship_type_code = t.scholarship_type_code");
        
        $select->where("f.flow_id = ?", $flow_id);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    /**
     * 下一步流程信息
     * @param unknown $scholarship_id 奖学金id
     * @param unknown $flow_order 步骤顺序
     */
    public function get_next_flow($parent_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("f"=>$this->_name));
//         $select->joinLeft(array("p"=>$this->_name), "f.parent_id = p.flow_id", array("parent_flow_name"=>"flow_name"));
//         $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = f.scholarship_id");
//         $select->joinLeft(array("t"=>"tb_scholarship_type"), "i.scholarship_type_code = t.scholarship_type_code");
    
//         $select->where("f.scholarship_id = ?", $scholarship_id);
        $select->where("f.parent_id = ?", $parent_id);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    /**
     * 流程信息
     * @param unknown $flow_id
     */
    public function get_scholarship_step_record($scholarship_id, $flow_order)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("f"=>$this->_name));
//         $select->joinLeft(array("p"=>$this->_name), "f.parent_id = p.flow_id", array("parent_flow_name"=>"flow_name"));
//         $select->joinLeft(array("i"=>"tb_scholarship_info"), "i.scholarship_id = f.scholarship_id");
//         $select->joinLeft(array("t"=>"tb_scholarship_type"), "i.scholarship_type_code = t.scholarship_type_code");
    
        $select->where("f.scholarship_id = ?", $scholarship_id);
        $select->where("f.flow_order = ?", $flow_order);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    /**
     * 按奖学金id查询流程是否存在
     * @param unknown $scholarship_id
     * @return boolean
     */
    public function is_scholarship_flow_exist($scholarship_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("scholarship_id = ?", $scholarship_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    /**
     * 是否存在上级流程
     * @param unknown $scholarship_id
     * @param unknown $parent_id
     */
    public function is_parent_flow_exist($scholarship_id, $parent_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("scholarship_id = ?", $scholarship_id);
        $select->where("parent_id = ?", $parent_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    /**
     * 是否有子流程
     * @return boolean
     */
    public function is_have_children_flow($flow_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("parent_id = ?", $flow_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    public function update_record($data, $flow_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("flow_id = ?", $flow_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($flow_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("flow_id = ?", $flow_id);
        return $this->delete($where);
    }


}

