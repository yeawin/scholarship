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
        $History = new Application_Model_Tuitionhistory();
        $history_list = $History->get_history_list();
        $this->view->history_list = $history_list;
    }


}



