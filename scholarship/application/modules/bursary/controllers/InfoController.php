<?php

class Bursary_InfoController extends Zend_Controller_Action
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
        $Bursaryinfo = new Application_Model_Bursaryinfo();
        $scholarship_list = $Bursaryinfo->get_scholarship_list();
        $this->view->scholarship_list = $scholarship_list;
    }

    public function addAction()
    {
        // action body
        $ScholarshipType = new Application_Model_Bursarytype();
        $scholarship_type_list = $ScholarshipType->get_scholarship_type_list();
        $this->view->scholar_type_list = $scholarship_type_list;
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["scholarship_name"]) && "" !== $Params["scholarship_name"])) {
            $this->view->message = "奖学金名称不能为空";
            return false;
        }
        if (!(isset($Params["num"]) && "" !== $Params["num"])) {
            $this->view->message = "奖学金名额不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_type_code"]) && "" !== $Params["scholarship_type_code"])) {
            $this->view->message = "奖学金类型不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_year"]) && "" !== $Params["scholarship_year"])) {
            $this->view->message = "奖学金年级不能为空";
            return false;
        }
        try {
            $data["scholarship_id"] = md5(microtime());
            $data["scholarship_name"] = $Params["scholarship_name"];
            $data["num"] = $Params["num"];
            $data["scholarship_type_code"] = $Params["scholarship_type_code"];
            $data["money"] = floatval($Params["money"]);
            $data["scholarship_year"] = $Params["scholarship_year"];
            $data["start_time"] = ("" == $Params["start_time"]) ? null : $Params["start_time"];
            $data["end_time"] = ("" == $Params["end_time"]) ? null : $Params["end_time"];
            $data["is_visible"] = $Params["is_visible"];
            $data["is_start"] = $Params["is_start"];
            $data["is_expired"] = $Params["is_expired"];
            $data["pay_time"] = ("" == $Params["pay_time"]) ? null : $Params["pay_time"];
            $ScholoarInfo = new Application_Model_Bursaryinfo();
            if ($ScholoarInfo->is_scholarship_name_exist($data["scholarship_name"])) {
                $this->view->message = "已经存在奖学金名称:{$data["scholarship_name"]}";
                return false;
            }
            $ScholoarInfo->insert_record($data);
            $this->redirect("/bursary/info/list");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function editAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "参数id不能为空";
        }
        try {
            $ScholarshipInfo = new Application_Model_Bursaryinfo();
            $scholarship_info = $ScholarshipInfo->get_scholarship_record($Params["id"]);
            $this->view->scholarship_info = $scholarship_info;
            
            $ScholarshipType = new Application_Model_Bursarytype();
            $scholarship_type_list = $ScholarshipType->get_scholarship_type_list();
            $this->view->scholar_type_list = $scholarship_type_list;
        } catch (Exception $e) {
            throwException($e);
        }
        
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["scholarship_name"]) && "" !== $Params["scholarship_name"])) {
            $this->view->message = "奖学金名称不能为空";
            return false;
        }
        if (!(isset($Params["num"]) && "" !== $Params["num"])) {
            $this->view->message = "奖学金名额不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_type_code"]) && "" !== $Params["scholarship_type_code"])) {
            $this->view->message = "奖学金类型不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_year"]) && "" !== $Params["scholarship_year"])) {
            $this->view->message = "奖学金年级不能为空";
            return false;
        }
        try {
            $data["scholarship_id"] = $Params["scholarship_id"];
            $data["scholarship_name"] = $Params["scholarship_name"];
            $data["num"] = $Params["num"];
            $data["scholarship_type_code"] = $Params["scholarship_type_code"];
            $data["scholarship_year"] = $Params["scholarship_year"];
            $data["money"] = floatval($Params["money"]);
            $data["start_time"] = ("" == $Params["start_time"]) ? null : $Params["start_time"];
            $data["end_time"] = ("" == $Params["end_time"]) ? null : $Params["end_time"];
            $data["is_visible"] = $Params["is_visible"];
            $data["is_start"] = $Params["is_start"];
            $data["is_expired"] = $Params["is_expired"];
            $data["pay_time"] = ("" == $Params["pay_time"]) ? null : $Params["pay_time"];
            $ScholoarInfo = new Application_Model_Bursaryinfo();
//             if ($ScholoarInfo->is_scholarship_name_exist($data["scholarship_name"])) {
//                 $this->view->message = "已经存在奖学金名称:{$data["scholarship_name"]}";
//                 return false;
//             }
            $ScholoarInfo->update_record($data, $Params["scholarship_id"]);
            $this->redirect("/bursary/info/list");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "奖学金参数id不能为空";
            return false;
        }
        
        $ScholarshipDept = new Application_Model_Bursarydept();
        if ($ScholarshipDept->is_scholarship_distribute_exist($Params["id"])) {
            $this->view->message = "删除失败，因为奖学金名额分配中，已经存在该奖学金的学院名额分配，请先删除名额分配！";
            return false;
        }
        
        $ScholarshipFlow = new Application_Model_Bursaryflow();
        if ($ScholarshipFlow->is_scholarship_flow_exist($Params["id"])) {
            $this->view->message = "删除失败，因为奖学金申报流程中，已经存在该奖学金的流程，请先删除流程！";
            return false;
        }
        
        $ScholarshipInfo = new Application_Model_Bursaryinfo();
        if ($ScholarshipInfo->delete_record($Params["id"]) > 0 ) {
            $this->redirect("/bursary/info/list");
        } else {
            $this->view->message = "删除失败，可能因不存在奖学金，对应id：{$Params["id"]}";
        }
    }


}













