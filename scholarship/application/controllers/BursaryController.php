<?php

class BursaryController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $Bursary = new Application_Model_Bursaryinfo();
        $bursary_list = $Bursary->get_scholarship_list();
        $this->view->bursary_list = $bursary_list;
        
    }

    public function flowAction()
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

    public function distributeAction()
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
        
        $ScholarshipDept = new Application_Model_Bursarydept();
        $scholarship_distribute = $ScholarshipDept->get_scholarship_distribute($scholarship_id);
        $this->view->scholarship_distribute = $scholarship_distribute;
    }

    public function viewAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "参数id不能为空";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        $scholarship_id = $Params["id"];
        $ScholarshipInfo = new Application_Model_Bursaryinfo();
        $scholarshipinfo = $ScholarshipInfo->get_scholarship_record($scholarship_id);
//         if (intval($scholarshipinfo["is_start"]) !== 1) {
//             $this->view->message = "还没有开始";
//             $this->renderScript("/error/warning.phtml");
//             return false;
//         }
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        $ScholarshipApply = new Application_Model_Bursaryapply();
        $is_applied = $ScholarshipApply->is_applied(array(
            "scholarship_id"=>$scholarship_id,
            "stu_id"=>$user_id,
        ));
//         if (!$is_applied) {
//             $this->view->message = "你还没有申请这项奖学金！";
//             $this->renderScript("/error/warning.phtml");
//             return false;
//         }
        
        $Flow = new Application_Model_Bursaryflow();
        $flow_list = $Flow->get_scholarship_flow_list($scholarship_id);
        $this->view->flow_list = $flow_list;
        
    }

    public function applyAction()
    {
        // 奖学金申请
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "参数id不能为空";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        $scholarship_id = $Params["id"];
        $ScholarshipInfo = new Application_Model_Bursaryinfo();
        $scholarshipinfo = $ScholarshipInfo->get_scholarship_record($scholarship_id);
        $this->view->bursary = $scholarshipinfo;
        
        if (intval($scholarshipinfo["is_start"]) !== 1) {
            $this->view->message = "还没有开始";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        
        $Condition = new Application_Model_Stucondition();
        $condition = $Condition->get_condition_record($user_id);
        $this->view->condition = $condition;
        

        
        $where_array = array("a.stu_id"=>$user_id, "a.scholarship_id"=>$scholarship_id);
        $ScholarshipApply = new Application_Model_Bursaryapply();
        $scholarship_apply_record = $ScholarshipApply->get_apply_record($where_array);
        $this->view->scholarship_apply_record = $scholarship_apply_record;
    }

    public function applyOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "参数奖学金id不能为空";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        $scholarship_id = $Params["id"];
        $ScholarshipInfo = new Application_Model_Bursaryinfo();
        $scholarshipinfo = $ScholarshipInfo->get_scholarship_record($scholarship_id);
        if (intval($scholarshipinfo["is_start"]) !== 1) {
            $this->view->message = "还没有开始";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        try {
            $auth = Zend_Auth::getInstance();
            $identity = $auth->getIdentity();
            $user_id = $identity->user_id;
            $BursaryApply = new Application_Model_Bursaryapply();
            $StuInfo = new Application_Model_Stuinfo();
            $stu_info = $StuInfo->get_stu_info($user_id);
            if ($stu_info["escape_time"] > 0 || $stu_info["course_no_passed"] > 0 || $stu_info["discipline"] > 0) {
                $this->view->message = "你符合奖学金申请资格条件！";
                $this->renderScript("/error/warning.phtml");
                return false;
            }
            if (!$BursaryApply->is_scholarship_exist($scholarship_id, $user_id)) {
                $data["apply_id"] = md5(microtime() + rand());
                $data["scholarship_id"] = $scholarshipinfo["scholarship_id"];
                $data["stu_id"] = $user_id;
                date_default_timezone_set("PRC");
                $data["apply_time"] = date("Y-m-d H:i:s");
//                 $data["is_pass"] = 0;
//                 $data["is_paid"] = 0;
                $BursaryApply->insert_record($data);
            }
            $this->redirect("/default/bursary/my");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
            $this->renderScript("/error/warning.phtml");
            return false;
        }

    }

    public function myAction()
    {
        // 我的奖学金
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        $BursaryApply = new Application_Model_Bursaryapply();
        $bursary_list = $BursaryApply->get_apply_list(array("a.stu_id = '$user_id'"));
        $this->view->bursary_list = $bursary_list;
    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "奖学金流程参数id不能为空";
            return false;
        }
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        $apply_id = $Params["id"];
        $BursaryApply = new Application_Model_Bursaryapply();
        $apply_record = $BursaryApply->get_apply_record(array("a.apply_id"=>$apply_id));
        if ($apply_record["stu_id"] !== $user_id) {
            $this->view->message = "id为<b>{$apply_id}</b>的申请记录，不属于你";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        if ($BursaryApply->delete_record($apply_id) > 0 ) {
            $this->redirect("/default/bursary/my");
        } else {
            $this->view->message = "id为<b>{$apply_id}</b>的申请记录，删除失败";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        
    }

    public function progressAction()
    {
        // 奖学金申请进度
        $Params = $this->getAllParams();
        if (!(isset($Params["apply_id"]) && "" !== $Params["apply_id"])) {
            $this->view->message = "参数apply_id不能为空";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        $apply_id = $Params["apply_id"];
        $where_array = array("a.appLy_id"=>$apply_id);
        $ScholarshipApply = new Application_Model_Bursaryapply();
        $apply_record = $ScholarshipApply->get_apply_record($where_array);
//         $stu_id = $apply_record["stu_id"];
        $scholarship_id = $apply_record["scholarship_id"];
        
        //该奖学金的审核进度
        $Review = new Application_Model_Bursaryreview();
        $where_array = array("r.apply_id"=>$apply_id);
        $order_array = array("f.flow_order DESC");
        $reviewed_list = $Review->get_reviewed_list($where_array, $order_array);
        
        $Flow = new Application_Model_Bursaryflow();
        $flow_list = $Flow->get_scholarship_flow_list($scholarship_id);
        $len_flow = count($flow_list);
        $len_review = count($reviewed_list);
        for ($i = 0; $i < $len_flow; $i ++)
        {
            $flow_list[$i]["review_id"] = null;
            for ($j = 0; $j < $len_review; $j ++)
            {
                if ($reviewed_list[$j]["flow_id"] === $flow_list[$i]["flow_id"]) {
                    $flow_list[$i]["review_id"] = $reviewed_list[$j]["review_id"];
                    $flow_list[$i]["review_pass"] = $reviewed_list[$j]["review_pass"];
                    $flow_list[$i]["reviewer"] = $reviewed_list[$j]["reviewer"];
                    $flow_list[$i]["review_time"] = $reviewed_list[$j]["review_time"];
                    $flow_list[$i]["comment"] = $reviewed_list[$j]["comment"];
                    break;
                }
            }
        }
        $this->view->progress = $flow_list;

    }

    public function conditionAction()
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


}





















