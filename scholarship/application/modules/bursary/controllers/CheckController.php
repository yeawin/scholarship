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
        var_dump($apply_list);
        $this->view->apply_list = $apply_list;
    }


}



