<?php

class Tuition_FeeController extends Zend_Controller_Action
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
        $Deduct = new Application_Model_Tuitiondeduct();
        $deduct_list = $Deduct->get_deduct_list();
//         var_dump($deduct_list);exit();
        $this->view->deduct_list = $deduct_list;
    }


}



