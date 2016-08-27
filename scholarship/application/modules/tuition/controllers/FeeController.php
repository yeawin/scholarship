<?php

class Tuition_FeeController extends Zend_Controller_Action
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
        $Deduct = new Application_Model_Tuitiondeduct();
        $deduct_list = $Deduct->get_deduct_list();
//         var_dump($deduct_list);
        $this->view->deduct_list = $deduct_list;
    }

    public function refleshAction()
    {
        // 刷新欠费表
        date_default_timezone_set("PRC");
        $time = date("Y-m-d H:i:s");
        $Stu = new Application_Model_Stuinfo();
        $stu_list = $Stu->get_stu_list();
//         var_dump($stu_list);
        foreach ($stu_list as $stu) {
            if ($stu["stu_no"] !== '2012100001') continue;
            //每个学生的每年应交费用
            $stu_grade = $stu["stu_grade"];
            $dept_code = $stu["dept_code"];
            $stu_type = $stu["stu_type"];
            $Tuition = new Application_Model_Tuitioninfo();
            $where_array = array("t.dept_code"=>$dept_code, "t.stu_type"=>$stu_type, "t.grade"=>$stu_grade);
            $tuition_list = $Tuition->get_tuition_list($where_array);
//             var_dump($tuition_list);
            //每个学生的缴费流水
            $stu_id = $stu["stu_no"];
            $History = new Application_Model_Tuitionhistory();
            $where_array = array("h.stu_id= '$stu_id'");
            $history_list = $History->get_history_list($where_array);
//             var_dump($history_list);return;
            
            //遍历每一年的学费
            $data["stu_id"] = $stu["stu_no"];
            $data["check_datetime"] = $time;
            foreach ($tuition_list as $tuition) {
                $flag = false;
                $index = $tuition["year"] - $tuition["grade"] + 1;
                $data["tuition_" . $index . "1"] = $tuition["tuition_1"];
                $data["tuition_" . $index . "2"] = $tuition["tuition_2"];
                $data["tuition_" . $index . "3"] = $tuition["tuition_3"];
                $tuition_id = $tuition["tuition_id"];
                foreach ($history_list as $history) {
                    if ($history["tuition_id"] === $tuition_id) {
                        switch ($history["tuition_key"]) {
                            case "tuition_" . $index . "1": {
                                $data["tuition_" . $index . "1"] -= $history["amount"]; 
                                break;
                            }
                            case "tuition_" . $index . "2": {
                                $data["tuition_" . $index . "2"] -= $history["amount"]; 
                                break;
                            }
                            case "tuition_" . $index . "3":{
                                $data["tuition_" . $index . "3"] -= $history["amount"]; 
                                break;
                            }
                        }
                    }
                }
            }
//             var_dump($data);
            $Deduct = new Application_Model_Tuitiondeduct();
            if ($Deduct->is_stu_exist($stu_id)) {
                $Deduct->update_record($data, $stu_id);
            } else {
                $Deduct->insert_record($data);
            }
        }
//         return false;
        $this->redirect("/tuition/fee/list");
    }


}





