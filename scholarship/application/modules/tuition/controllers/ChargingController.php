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
        $i = 0;
        if (isset($Params["stu_id"])) {
            $where_array[$i ++] = "s.stu_id = '" . $Params["stu_id"] . "'";
        }
        if (isset($Params["scholarship_id"])) {
            $where_array[$i ++] = "h.scholarship_id = '" . $Params["scholarship_id"] . "'";
        }
        $order_array = array(
            "s.stu_id asc",
            "h.tuition_key desc",
        );
//         var_dump($where_array);return false;
        $History = new Application_Model_Tuitionhistory();
        $history_list = $History->get_history_list($where_array, $order_array);
        $this->view->history_list = $history_list;
    }


}



