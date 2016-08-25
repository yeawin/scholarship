<?php

class Tuition_ChargingController extends Zend_Controller_Action
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
        // action body
        $Params = $this->getAllParams();
        $stu_id = isset($Params["id"]) ? $Params["id"] : null;
        $where_array = array(
            "s.stu_id"=>$stu_id,    
        );
        $History = new Application_Model_Tuitionhistory();
        $history_list = $History->get_history_list($where_array);
        $this->view->history_list = $history_list;
    }


}



