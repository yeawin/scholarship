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
        $where_array = array("a.is_pass is null");
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
        $where_array = array("a.stu_id = '$stu_id'");
        $apply_list = $Apply->get_apply_list($where_array);
//         var_dump($apply_list);
        $this->view->apply_list = $apply_list;
        
        //该奖学金的审核进度
        $Review = new Application_Model_Bursaryreview();
        $where_array = array("r.apply_id"=>$apply_id);
        $order_array = array("f.flow_order DESC");
        $reviewed_list = $Review->get_reviewed_list($where_array, $order_array);
//         var_dump($reviewed_list);
        
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
                    break;
                }
            }
        }
//         var_dump($flow_list);
        $this->view->progress = $flow_list;
//         var_dump($flow_list);
        
//         $where_array = array("a.apply_id"=>$apply_id, "a.scholarship_id"=>$scholarship_id);
//         $order_array = array("f.flow_order ASC");
//         $flow_checked_progress = $Apply->get_apply_checked_progress($where_array, $order_array);

//         var_dump($flow_checked_progress);
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
        
        //该奖学金的审核进度
        $Review = new Application_Model_Bursaryreview();
        $where_array = array("r.apply_id"=>$apply_id);
        $order_array = array("f.flow_order DESC");
        $reviewed_list = $Review->get_reviewed_list($where_array, $order_array);
        //         var_dump($reviewed_list);
        
        //申请的奖学金的相关信息
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.apply_id"=>$apply_id);
        $apply_record = $Apply->get_apply_record($where_array);
        $scholarship_id = $apply_record["scholarship_id"];
        $Flow = new Application_Model_Bursaryflow();
        $flow_list = $Flow->get_scholarship_flow_list($scholarship_id);
        
        $len_flow = count($flow_list);
        $len_review = count($reviewed_list);
        $flow_id = $flow_list[$len_review]["flow_id"];

        
        //审核当前流程
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        date_default_timezone_set("PRC");
        $data["review_id"] = md5(rand() + microtime());
        $data["flow_id"] = $flow_id;
        $data["apply_id"] = $apply_id;
        $data["review_time"] = date('Y-m-d H:i:s', time());
        $data["review_pass"] = $pass;
        $data["reviewer"] = $user_id;
        $Review = new Application_Model_Bursaryreview();
        $Review->insert_record($data);
        
        if ('1' !== $pass) {
            //没有通过或者流程结束了，更新申请记录表状态
            $Apply = new Application_Model_Bursaryapply();
            $Apply->update_record(array("is_pass"=>$pass), $apply_id);
        } else if (($len_review + 1) == $len_flow) {
            $Apply = new Application_Model_Bursaryapply();
            $Apply->update_record(array("is_pass"=>$pass, "is_paid"=>'0'), $apply_id);
        }
        $this->redirect("/bursary/check/view/id/".$apply_id);

    }


}







