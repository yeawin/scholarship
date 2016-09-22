<?php

class User_ManagerController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        // action body
        $Dept = new Application_Model_Deptinfo();
        $parent_code = STUDENT_DEPT;
        $dept_list = $Dept->get_dept_list($parent_code);
        $this->view->dept_list = $dept_list;
        
        $Usertype = new Application_Model_Usertype();
        $user_type_list = $Usertype->get_type_list();
        $this->view->user_type_list = $user_type_list;
    }

    public function addOkAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }

    public function editOkAction()
    {
        // action body
    }


}









