<?php

class College_ColmanController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $Dept= new Application_Model_Deptinfo();
        $dept_list = $Dept->get_dept_list();
        $this->view->dept_list = $dept_list;
    }



}





