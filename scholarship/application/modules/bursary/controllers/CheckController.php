<?php

class Bursary_CheckController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->view->headTitle("奖学金-审核");
    }

    public function indexAction()
    {
        // action body
    }

    public function listAction()
    {
        // action body
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.is_pass"=>"0");
        $order_array = array("a.scholarship_id", "a.apply_time");
        $apply_list = $Apply->get_apply_list($where_array, $order_array);
//         var_dump($apply_list);
        $this->view->apply_list = $apply_list;
    }

    public function viewAction()
    {
        // action body
        $Params = $this->getAllParams();
        $apply_id = $Params["id"];
        $this->view->apply_id = $apply_id;
        
        //申请的奖学金的相关信息
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.apply_id"=>$apply_id);
        $apply_record = $Apply->get_apply_record($where_array);
        $stu_id = $apply_record["stu_id"];
        $scholarship_id = $apply_record["scholarship_id"];
        
        //该生的资格条件
        $StuCon = new Application_Model_Stucondition();
        $stu_con = $StuCon->get_condition_record($stu_id);
        $this->view->stu_con = $stu_con;
        
        //该生的所有奖学金记录
        $where_array = array("a.stu_id"=>$stu_id);
        $apply_list = $Apply->get_apply_list($where_array);
        $this->view->apply_list = $apply_list;
        
        //该奖学金的审核进度
        $Review = new Application_Model_Bursaryreview();
        $where_array = array("r.apply_id"=>$apply_id);
        $order_array = array("f.flow_order DESC");
        $reviewed_list = $Review->get_reviewed_list($where_array, $order_array);
        
        $where_array = array("a.apply_id"=>$apply_id);
        $order_array = array("f.flow_order ASC");
        $flow_checked_progress = $Apply->get_apply_checked_progress($where_array, $order_array);
//         var_dump($flow_checked_progress);
        $this->view->progress = $flow_checked_progress;
//         var_dump($flow_checked_list);exit();
        
//         $prev_progress = $Review->get_prev_progress($apply_id);
       
//         $flow_id_prev = isset($prev_progress["flow_id"]) ? $prev_progress["flow_id"] : '';  //当前步骤
//         echo "上一步"; var_dump($prev_progress);
        //该奖学金的审核流程
        
//         echo $scholarship_id;
//         $Flow = new Application_Model_Bursaryflow();
//         $flow_list = $Flow->get_scholarship_flow_list($scholarship_id);
//         $len = count($flow_list);
//         $progress = array(
//             "prev"=>null, 
//             "current"=>null,
//             "next"=>null,
//         );
//         for ($i = 0; $i < $len; $i ++)
//         {
//             if ($flow_id_prev  == $flow_list[$i]["parent_id"]) {
//                 $progress["prev"] = isset($flow_list[$i - 1]["flow_id"]) ? $flow_list[$i - 1] : null;
//                 $progress["current"] = $flow_list[$i];
//                 $progress["next"] = isset($flow_list[$i + 1]["flow_id"]) ? $flow_list[$i + 1] : null;
//             }
//         }
//         $this->view->progress = $flow_checked_progress;
//         var_dump($flow_list);
//         var_dump($progress);
    }

    public function reviewAction()
    {
        // action body
        $Params = $this->getAllParams();
        $apply_id = $Params["id"];
        $pass = $Params["pass"];
        
        //申请的奖学金的相关信息
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.apply_id"=>$apply_id);
        $apply_record = $Apply->get_apply_record($where_array);
        $scholarship_id = $apply_record["scholarship_id"];
        
        $Review = new Application_Model_Bursaryreview();
        $where_array = array("r.apply_id"=>$apply_id);
//         $order_array = array("f.flow_order");
        $reviewed_list = $Review->get_reviewed_list($where_array);
        $prev_progress = $Review->get_prev_progress($apply_id);
        $parent_flow_id = isset($prev_progress["flow_id"]) ? $prev_progress["flow_id"] : '';  //当前步骤
        $Flow = new Application_Model_Bursaryflow();
        $flow_current = $Flow->get_next_flow($scholarship_id, $parent_flow_id);
        
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        date_default_timezone_set("PRC");
        $data["review_id"] = md5(rand() + microtime());
        $data["flow_id"] = $flow_current["flow_id"];
        $data["apply_id"] = $apply_id;
        $data["review_time"] = date('Y-m-d H:i:s', time());
        $data["review_pass"] = $pass;
        $data["reviewer"] = $user_id;
//         var_dump($data);exit();
        $Review->insert_record($data);
        $this->redirect("/bursary/check/list");
//         $Flow = new Application_Model_Bursaryflow();
//         $Flow->get_flow_record($scholarship_id, $flow_order_current);
//         var_dump($data);
    }


}







