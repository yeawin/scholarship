<?php

class Student_ConditionController extends Zend_Controller_Action
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
        $Condition = new Application_Model_Stucondition();
        $condition_list = $Condition->get_condition_list();
        $this->view->condition_list = $condition_list;
    }

    public function editAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["stu_id"]) && "" !== $Params["stu_id"])) {
            $this->view->message = "参数stu_id不能为空";
        }
        try {
            $Stucondition = new Application_Model_Stucondition();
            $condition = $Stucondition->get_condition_record($Params["stu_id"]);
//             var_dump($condition);exit();
            $this->view->condition = $condition;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
//         if (!(isset($Params["escape_time"]) && "" !== $Params["escape_time"])) {
//             $this->view->message = "逃课次数不能为空";
//             return false;
//         }
        if (!(isset($Params["course_grade"]) && "" !== $Params["course_grade"])) {
            $this->view->message = "成绩排名不能为空";
            return false;
        }
//         if (!(isset($Params["course_no_passed"]) && "" !== $Params["course_no_passed"])) {
//             $this->view->message = "挂科门数不能为空";
//             return false;
//         }
//         if (!(isset($Params["discipline"]) && "" !== $Params["discipline"])) {
//             $this->view->message = "违纪情况不能为空";
//             return false;
//         }

        try {
            $stu_id = $Params["stu_id"];
            $data["stu_id"] = $Params["stu_id"];
            $data["escape_time"] = $Params["escape_time"];
            $data["course_grade"] = $Params["course_grade"];
            $data["course_no_passed"] = $Params["course_no_passed"];
            $data["discipline"] = $Params["discipline"];
            $Condition = new Application_Model_Stucondition();
            if ($Condition->is_condition_exist($stu_id)) {
                $Condition->update_record($data, $stu_id);
            } else {
                $Condition->insert_record($data);
            }
            $this->redirect("/student/condition/list");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }


}







