<?php
/**
 * 奖学金的审核进度
 * @author Administrator
 *
 */
class Application_Model_Bursaryreview extends Zend_Db_Table_Abstract
{

    protected $_primary = "review_id";
    
    protected $_name = "tb_scholarship_review";
     
    protected $_review_id;
    
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * 查看已经审核列表
     * @param unknown $array
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function get_reviewed_list($where_array = null, $order_array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("r"=>$this->_name));
        $select->joinLeft(array("a"=>"tb_scholarship_apply"), "a.apply_id = r.apply_id");
        $select->joinLeft(array("f"=>"tb_scholarship_flow"), "f.flow_id = r.flow_id");
        if (null !== $where_array) {
            foreach ($where_array as $key=>$value) {
                $select->where("{$key} = ?", $value);
            }
        }
        if (null !== $order_array) {
            $select->order($order_array);
        }
        $result = $this->fetchAll($select);
        if ($result) {
            $result = $result->toArray();
        }
        return $result;
    }
    
    /**
     * 上一步
     * @param unknown $apply_id
     * @return Zend_Db_Table_Row_Abstract|NULL
     */
    public function get_prev_progress($apply_id)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("r"=>$this->_name));//审核表
        $select->joinLeft(array("a"=>"tb_scholarship_apply"), "a.apply_id = r.apply_id");//申请表
        $select->joinLeft(array("f"=>"tb_scholarship_flow"), "f.flow_id = r.flow_id", array("flow_id", "flow_name", "parent_id", "flow_order", ));//流程表
        $select->order("f.flow_order desc");
        $select->where("r.apply_id = ?", $apply_id);
        $result = $this->fetchRow($select);
        if ($result) {
            $result = $result->toArray();
        }
         return $result;
    }
    
    
    /**
     * 查看某个申请的所有审核信息
     * @param unknown $array
     */
    public function get_review_record($array = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array("r"=>$this->_name));
        $select->joinLeft(array("a"=>"tb_scholarship_apply"), "a.apply_id = r.apply_id");
        $select->joinLeft(array("f"=>"tb_scholarship_flow"), "f.flow_id = r.flow_id");
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
    
    public function is_reviewed($array = null )
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
    
    public function is_applied_exist($apply_id)
    {
        $select = $this->select();
        $select->from($this->_name);
        $select->where("apply_id = ?", $apply_id);
        $result = $this->fetchAll($select);
        return (count($result) > 0);
    }
    
    
    public function insert_record($data)
    {
        return $this->insert($data);
    }
    
    public function update_record($data, $review_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("review_id = ?", $review_id);
        return $this->update($data, $where);
    }
    
    public function delete_record($review_id)
    {
        $db = $this->getDefaultAdapter();
        $where = $db->quoteInto("review_id = ?", $review_id);
        return $this->delete($where);
    }

}

