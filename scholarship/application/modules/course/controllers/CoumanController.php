<?php

class Course_CoumanController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $Courseinfo = new Application_Model_Courseinfo();
        $course_list = $Courseinfo->get_course_list();
        $this->view->course_list = $course_list;
    }

    public function addAction()
    {
        // action body
    }

    public function addOkAction()
    {
        // action body
    }


}





