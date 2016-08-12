<?php

class Bursary_FlowController extends Zend_Controller_Action
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
        
        $ScholarshipFlow = new Application_Model_Bursaryflow();
        $flow_list = $ScholarshipFlow->get_scholarship_flow_list($scholarship_id);
        $this->view->flow_list = $flow_list;
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
        
        $ScholarshipFlow = new Application_Model_Bursaryflow();
        $flow_list = $ScholarshipFlow->get_scholarship_flow_list($scholarship_id);
        $this->view->flow_list = $flow_list;
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["flow_name"]) && "" !== $Params["flow_name"])) {
            $this->view->message = "流程名称不能为空";
            return false;
        }
        if (!(isset($Params["check_role_id"]) && "" !== $Params["check_role_id"])) {
            $this->view->message = "审核对象不能为空";
            return false;
        }
        try {
            $data["flow_name"] = $Params["flow_name"];
            $data["scholarship_id"] = $Params["scholarship_id"];
            $data["check_role_id"] = $Params["check_role_id"];
            $data["flow_order"] = 1;
            foreach ($Params["parent_id"] as $parent_id) {
                $data["flow_id"] = md5(microtime() + rand());
                $data["parent_id"] = $parent_id;
                $ScholarFlow = new Application_Model_Bursaryflow();
                $scholar_flow = $ScholarFlow->get_scholarship_flow_record($parent_id);
                if (isset($scholar_flow["flow_order"])) {
                    $data["flow_order"] = intval($scholar_flow["flow_order"]) + 1;
                }
                $ScholarFlow->insert($data);
            }
//             if (is_array($Params["parent_id"])) {
//                 foreach ($Params["parent_id"] as $parent_id) {
//                     $data["flow_id"] = md5(microtime() + rand());
//                     $data["parent_id"] = $parent_id;
//                     $ScholarFlow = new Application_Model_Bursaryflow();
//                     $scholar_flow = $ScholarFlow->get_scholarship_flow_record($parent_id);
//                     if (isset($scholar_flow["flow_order"])) {
//                         $data["flow_order"] = intval($scholar_flow["flow_order"]) + 1;
//                     }
//                     $ScholarFlow->insert($data);
//                 }

//             } else {
//                 $parent_id = $Params["parent_id"];
//                 $data["flow_id"] = md5(microtime() + rand());
//                 $data["parent_id"] = $parent_id;
//                 $data["flow_order"] = 1;
//                 $ScholarFlow = new Application_Model_Bursaryflow();
//                 $scholar_flow = $ScholarFlow->get_scholarship_flow_record($parent_id);
//                 if (isset($scholar_flow["scholarship_id"])) {
//                     $data["flow_order"] = intval($scholar_flow["flow_order"]) + 1;
//                 }
//                 $ScholarFlow->insert($data);
//             }
            $this->redirect("/bursary/flow/list/id/" . $Params["scholarship_id"]);
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
            $ScholarshipFlow = new Application_Model_Bursaryflow();
            $scholarship_flow = $ScholarshipFlow->get_scholarship_flow_record($Params["id"]);
//             var_dump($scholarship_flow);exit();
            $this->view->scholarship_flow = $scholarship_flow;
        
            $ScholarshipFlow = new Application_Model_Bursaryflow();
            $flow_list = $ScholarshipFlow->get_scholarship_flow_list($scholarship_flow["scholarship_id"], $Params["id"]);
            $this->view->flow_list = $flow_list;
//             $ScholarshipType = new Application_Model_Bursarytype();
//             $scholarship_type_list = $ScholarshipType->get_scholarship_type_list();
//             $this->view->scholar_type_list = $scholarship_type_list;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
//         var_dump($Params);exit();
        if (!(isset($Params["flow_name"]) && "" !== $Params["flow_name"])) {
            $this->view->message = "流程名称不能为空";
            return false;
        }
        if (!(isset($Params["check_role_id"]) && "" !== $Params["check_role_id"])) {
            $this->view->message = "审核对象不能为空";
            return false;
        }
        try {
            $data["flow_name"] = $Params["flow_name"];
//             $data["scholarship_id"] = $Params["scholarship_id"];
            $data["check_role_id"] = $Params["check_role_id"];
            $data["flow_order"] = 1;
            foreach ($Params["parent_id"] as $parent_id) {
                $data["flow_id"] = $Params["flow_id"];
                $data["parent_id"] = $parent_id;
                $ScholarFlow = new Application_Model_Bursaryflow();
                $scholar_flow = $ScholarFlow->get_scholarship_flow_record($parent_id);
                if (isset($scholar_flow["flow_order"])) {
                    $data["flow_order"] = intval($scholar_flow["flow_order"]) + 1;
                }
                $ScholarFlow->update_record($data, $data["flow_id"]);
            }
            $this->redirect("/bursary/flow/list/id/" . $Params["scholarship_id"]);
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "奖学金流程参数id不能为空";
            return false;
        }
        
        $ScholarshipFlow = new Application_Model_Bursaryflow();
        if ($ScholarshipFlow->is_have_children_flow($Params["id"])) {
            $this->view->message = "删除失败，因为该流程存在后续的流程，请先修改关联的后续流程！";
            return false;
        }

        $ScholarshipFlow = new Application_Model_Bursaryflow();
        $scholarship_flow = $ScholarshipFlow->get_scholarship_flow_record($Params["id"]);
        if ($ScholarshipFlow->delete_record($Params["id"]) > 0 ) {
            $this->redirect("/bursary/flow/list/id/{$scholarship_flow["scholarship_id"]}");
        } else {
            $this->view->message = "删除失败，可能因不存在奖学金的流程，对应id：{$Params["id"]}";
        }
        
    }


}













