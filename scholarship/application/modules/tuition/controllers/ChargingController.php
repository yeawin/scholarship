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
        $where_array = null;
        if (isset($Params["id"])) {
            $where_array = array(
                "s.stu_id = '" . $Params["id"] . "'"
            );
        }
        $order_array = array(
            "s.stu_id asc",
            "h.tuition_key desc",
        );
        $History = new Application_Model_Tuitionhistory();
        $history_list = $History->get_history_list($where_array, $order_array);
        $this->view->history_list = $history_list;
    }


}



