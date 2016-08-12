<?php

class Bursary_TypeController extends Zend_Controller_Action
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
        $Scholarshiptype = new Application_Model_Bursarytype();
        $scholarship_type_list = $Scholarshiptype->get_scholarship_type_list();
        $this->view->scholarship_type_list = $scholarship_type_list;
    }

    public function addAction()
    {
        // action body
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["scholarship_type_name"]) && "" !== $Params["scholarship_type_name"])) {
            $this->view->message = "类型名称不能为空";
        }
        try {
            $data["scholarship_type_name"] = $Params["scholarship_type_name"];
            $ScholarshipType = new Application_Model_Bursarytype();
            if (!$ScholarshipType->is_scholarship_type_exist($data["scholarship_type_name"])) {
                if($ScholarshipType->insert($data) > 0) {
                    $this->redirect("/bursary/type/list");
                } else {
                    $this->view->message = "添加失败！奖学金类型：{$data["scholarship_type_name"]}";
                }
            } else {
                $this->view->message = "已经存在奖学金类型：{$data["scholarship_type_name"]}";
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
            $ScholarshipType = new Application_Model_Bursarytype();
            $scholarship_type = $ScholarshipType->get_scholarship_type($Params["code"]);
            $this->view->scholarship_type = $scholarship_type;
        } catch (Exception $e) {
            throwException($e);
        }
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["scholarship_type_code"]) && "" !== $Params["scholarship_type_code"])) {
            $this->view->message = "类型代码参数code不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_type_name"]) && "" !== $Params["scholarship_type_name"])) {
            $this->view->message = "类型名称不能为空";
            return false;
        }
        try {
            $data["scholarship_type_name"] = $Params["scholarship_type_name"];
            $ScholarshipType = new Application_Model_Bursarytype();
            if ($ScholarshipType->is_scholarship_type_exist($Params["scholarship_type_name"])) {
                $this->view->message = "已经存在奖学金类型：{$Params["scholarship_type_name"]}";
                return false;
            }
            if ($ScholarshipType->update_record($data, $Params["scholarship_type_code"]) > 0) {
                $this->redirect("/bursary/type/list");
                return false;
            } else {
                $this->view->message = "修改失败，可能因不存在奖学金类型代码：{$Params["scholarship_type_code"]}";
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
        $ScholarshipInfo = new Application_Model_Bursaryinfo();
        if ($ScholarshipInfo->is_scholarship_type_exist($Params["code"])) {
            $this->view->message = "删除失败，因为奖学金列表中，存在该类型的奖学金";
            return false;
        }
        $ScholarshipType = new Application_Model_Bursarytype();
        if ($ScholarshipType->delete_record($Params["code"]) > 0 ) {
            $this->redirect("/bursary/type/list");
        } else {
            $this->view->message = "删除失败，可能因不存在奖学金类型代码：{$Params["code"]}";
        }
         
    }


}













