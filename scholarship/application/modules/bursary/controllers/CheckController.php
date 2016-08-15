<?php

class Bursary_CheckController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->view->headTitle("奖学金-审核");
    }

    public function indexAction()
    {
        // action body
    }

    public function listAction()
    {
        // action body
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.is_pass"=>"0");
        $order_array = array("a.scholarship_id", "a.apply_time");
        $apply_list = $Apply->get_apply_list($where_array, $order_array);
//         var_dump($apply_list);
        $this->view->apply_list = $apply_list;
    }

    public function viewAction()
    {
        // action body
        $Params = $this->getAllParams();
        
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.apply_id"=>$Params["id"]);
        $apply_record = $Apply->get_apply_record($where_array);
        
        //该生的资格条件
        $stu_id = $apply_record["stu_id"];
        $StuCon = new Application_Model_Stucondition();
        $stu_con = $StuCon->get_condition_record($stu_id);
        $this->view->stu_con = $stu_con;
        $this->view->apply_id = $Params["id"];
        
        //该生的所有奖学金记录
        $where_array = array("a.stu_id"=>$stu_id);
        $apply_list = $Apply->get_apply_list($where_array);
        $this->view->apply_list = $apply_list;
    }


}





