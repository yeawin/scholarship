<?php

class Student_InfoController extends Zend_Controller_Action
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
        $Stuinfo = new Application_Model_Stuinfo();
        $stu_list = $Stuinfo->get_stu_list();
        $this->view->stu_list = $stu_list;
    }

    public function addAction()
    {
        // action body
        $Dept = new Application_Model_Deptinfo();
        $parent_code = STUDENT_DEPT;
        $dept_list = $Dept->get_dept_list($parent_code);
        $this->view->dept_list = $dept_list;
        
        $Stutype = new Application_Model_Stutype();
        $stu_type_list = $Stutype->get_stu_type_list();
        $this->view->stu_type_list = $stu_type_list;
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["stu_id"]) && "" !== $Params["stu_id"])) {
            $this->view->message = "学号不能为空";
            return false;
        }
        if (!(isset($Params["stu_name"]) && "" !== $Params["stu_name"])) {
            $this->view->message = "姓名不能为空";
            return false;
        }
        if (!(isset($Params["stu_sex"]) && "" !== $Params["stu_sex"])) {
            $this->view->message = "性别不能为空";
            return false;
        }
        if (!(isset($Params["dept_code"]) && "" !== $Params["dept_code"])) {
            $this->view->message = "所属学院不能为空";
            return false;
        }
        if (!(isset($Params["stu_type"]) && "" !== $Params["stu_type"])) {
            $this->view->message = "学生类型不能为空";
            return false;
        }
        if (!(isset($Params["stu_grade"]) && "" !== $Params["stu_grade"])) {
            $this->view->message = "入学年级不能为空";
            return false;
        }
        try {
            $data["stu_id"] = $Params["stu_id"];
            $data["stu_name"] = $Params["stu_name"];
            $data["stu_sex"] = $Params["stu_sex"];
            $data["stu_id"] = $Params["stu_id"];
            $data["dept_code"] = $Params["dept_code"];
            $data["stu_grade"] = $Params["stu_grade"];
            $data["stu_type"] = $Params["stu_type"];
            $Stuinfo = new Application_Model_Stuinfo();
            if ($Stuinfo->is_stu_exist($data["stu_id"])) {
                $this->view->message = "已经存在学号：{$data["stu_id"]} 的学生";
                return false;
            } else {
                $Stuinfo->insert_record($data);
                $User = new Application_Model_User();
                $data = array(
                    "user_id"=>$Params["stu_id"], 
                    "password"=>md5($Params["stu_id"]),
                 );
                $User->insert_record($data);
                
            }

            $this->redirect("/student/info/list");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function editAction()
    {
        // action body
    }

    public function editOkAction()
    {
        // action body
    }

    public function delAction()
    {
        // action body
    }


}













