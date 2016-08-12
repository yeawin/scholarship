<?php

class Student_TypeController extends Zend_Controller_Action
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
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["stu_type_name"]) && "" !== $Params["stu_type_name"])) {
            $this->view->message = "类型名称不能为空";
        }
        try {
            $data["stu_type_name"] = $Params["stu_type_name"];
            $Stutype = new Application_Model_Stutype();
            if (!$Stutype->is_stu_type_exist($data["stu_type_name"])) {
                if($Stutype->insert($data) > 0) {
                    $this->redirect("/student/type/list");
                } else {
                    $this->view->message = "添加失败！学生类型：{$data["stu_type_name"]}";
                }
            } else {
                $this->view->message = "已经存在学生类型：{$data["stu_type_name"]}";
            }
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function editAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["code"]) && "" !== $Params["code"])) {
            $this->view->message = "参数code不能为空";
        }
        try {
            $Stutype = new Application_Model_Stutype();
            $stu_type = $Stutype->get_stu_type($Params["code"]);
            $this->view->stu_type = $stu_type;
        } catch (Exception $e) {
            throwException($e);
        }
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["stu_type_code"]) && "" !== $Params["stu_type_code"])) {
            $this->view->message = "类型代码参数code不能为空";
            return false;
        }
        if (!(isset($Params["stu_type_name"]) && "" !== $Params["stu_type_name"])) {
            $this->view->message = "类型名称不能为空";
            return false;
        }
        try {
            $data["stu_type_name"] = $Params["stu_type_name"];
            $Stutype = new Application_Model_Stutype();
            if ($Stutype->is_stu_type_exist($Params["stu_type_name"])) {
                $this->view->message = "已经存在学生类型：{$Params["stu_type_name"]}";
                return false;
            }
            if ($Stutype->update_record($data, $Params["stu_type_code"]) > 0) {
                $this->redirect("/student/type/list");
                return false;
            } else {
                $this->view->message = "修改失败，可能因不存在学生类型代码：{$Params["stu_type_code"]}";
            }
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["code"]) && "" !== $Params["code"])) {
            $this->view->message = "类型代码参数code不能为空";
            return false;
        }
        $StuInfo = new Application_Model_Stuinfo();
        if ($StuInfo->is_stu_type_exist($Params["code"])) {
            $this->view->message = "删除失败，因为学生列表中，存在该类型的学生";
            return false;
        }
        
        $Stutype = new Application_Model_Stutype();
        if ($Stutype->delete_record($Params["code"]) > 0 ) {
            $this->redirect("/student/type/list");
        } else {
            $this->view->message = "删除失败，可能因不存在学生类型代码：{$Params["code"]}";
        }
         
    }

    public function listAction()
    {
        // action body
        $Stutype = new Application_Model_Stutype();
        $stu_type_list = $Stutype->get_stu_type_list();
        $this->view->stu_type_list = $stu_type_list;
    }


}













