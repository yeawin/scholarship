<?php

class Bursary_ConditionController extends Zend_Controller_Action
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
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            die("参数id不能为空");
        }
        $scholarship_id = $Params["id"];
        $ScholarshipInfo = new Application_Model_Bursaryinfo();
        $scholarshipinfo = $ScholarshipInfo->get_scholarship_record($scholarship_id);
        $this->view->scholarshipinfo = $scholarshipinfo;
        
        $Condition = new Application_Model_Bursarycondition();
        $condition_list = $Condition->get_scholarship_condition_list($scholarship_id);
        $this->view->condition_list = $condition_list;
    }

    public function addAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            die("参数id不能为空");
        }
        $scholarship_id = $Params["id"];
        $ScholarshipInfo = new Application_Model_Bursaryinfo();
        $scholarshipinfo = $ScholarshipInfo->get_scholarship_record($scholarship_id);
        $this->view->scholarshipinfo = $scholarshipinfo;
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        try {
            $data["condition_id"] = md5(microtime() + rand());
            $data["scholarship_id"] = $Params["scholarship_id"];
            $data["key"] = $Params["key"];
            $data["symbol"] = $Params["symbol"];
            $data["value"] = $Params["value"];
            $Condition = new Application_Model_Bursarycondition();
            $Condition->insert_record($data);
            $this->redirect("/bursary/condition/list/id/" . $Params["scholarship_id"]);
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
            $Condition = new Application_Model_Bursarycondition();
            $scholarship_condition = $Condition->get_scholarship_condition_record($Params["id"]);
            $this->view->scholarship_condition = $scholarship_condition;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        try {
            $data["key"] = $Params["key"];
            $data["symbol"] = $Params["symbol"];
            $data["value"] = $Params["value"];
            $Condition = new Application_Model_Bursarycondition();
            $Condition->update_record($data, $Params["condition_id"]);
            $this->redirect("/bursary/condition/list/id/" . $Params["scholarship_id"]);
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "奖学金评比条件参数id不能为空";
            return false;
        }
        
        $Condition = new Application_Model_Bursarycondition();
        $scholarship_condition = $Condition->get_scholarship_condition_record($Params["id"]);
        if ($Condition->delete_record($Params["id"]) > 0) {
            $this->redirect("/bursary/condition/list/id/" . $scholarship_condition["scholarship_id"]);
        } else {
            $this->view->message = "删除失败，因为该流程存在后续的流程，请先修改关联的后续流程！";
            return false;
        }
    }


}













