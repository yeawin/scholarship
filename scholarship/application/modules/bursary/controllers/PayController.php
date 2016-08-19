<?php

class Bursary_PayController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function listAction()
    {
        // 奖学金列表
        $Bursaryinfo = new Application_Model_Bursaryinfo();
        $scholarship_list = $Bursaryinfo->get_scholarship_list();
        $this->view->scholarship_list = $scholarship_list;
    }

    public function applicantAction()
    {
        // 申请人列表
        //奖学金信息
        $scholarship_id = $this->getParam("id");
        $Scholarship = new Application_Model_Bursaryinfo();
        $scholarship = $Scholarship->get_scholarship_record($scholarship_id);
        $this->view->scholarship = $scholarship;
        
        //申请人
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.is_paid !"=>"1", "a.is_pass"=> "1", "a.scholarship_id"=>$scholarship_id);
        $order_array = array("a.scholarship_id", "a.apply_time");
        $apply_list = $Apply->get_apply_list($where_array, $order_array);
        $this->view->apply_list = $apply_list;
    }


}





