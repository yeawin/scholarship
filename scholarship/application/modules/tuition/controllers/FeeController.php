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
//         var_dump($deduct_list);
        $this->view->deduct_list = $deduct_list;
    }

    public function refleshAction()
    {
        // action body
        $Stu = new Application_Model_Stuinfo();
        $stu_list = $Stu->get_stu_list();
        
        $Tuition = new Application_Model_Tuitioninfo();
        $tuition_list = $Tuition->get_tuition_list();
        
        var_dump($stu_list);
        var_dump($tuition_list);
    }


}





