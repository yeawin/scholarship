<?php

class Tuition_InfoController extends Zend_Controller_Action
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
        $Tuition = new Application_Model_Tuitioninfo();
        $tuition_list = $Tuition->get_tuition_list();
        $this->view->tuition_list = $tuition_list;
    }

    public function addAction()
    {
        // action body
        $Dept = new Application_Model_Deptinfo();
        $parent_code = STUDENT_DEPT;
        $dept_list = $Dept->get_dept_list($parent_code);
        $this->view->dept_list = $dept_list;
        
        $Stutype = new Application_Model_Stutype();
        $stu_type_list = $Stutype->get_stu_type_list();
        $this->view->stu_type_list = $stu_type_list;
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["grade"]) && "" !== $Params["grade"])) {
            $this->view->message = "入学年级不能为空";
            return false;
        }
        if (!(isset($Params["year"]) && "" !== $Params["year"])) {
            $this->view->message = "核定年份不能为空";
            return false;
        }
        if (!(isset($Params["dept_code"]) && "" !== $Params["dept_code"])) {
            $this->view->message = "所属学院不能为空";
            return false;
        }
        try {
            $data["tuition_id"] = md5(microtime());
            $data["dept_code"] = $Params["dept_code"];
            $data["grade"] = $Params["grade"];
            $data["year"] = $Params["year"];
            $data["stu_type"] = $Params["stu_type"];
            $data["tuition_1"] = floatval($Params["tuition_1"]);
            $data["tuition_2"] = floatval($Params["tuition_2"]);
            $data["tuition_3"] = floatval($Params["tuition_3"]);
            $auth = Zend_Auth::getInstance();
            $identity = $auth->getIdentity();
            $data["user_id"] = isset($identity->user_id) ? $identity->user_id : "admin";
            $Tuition = new Application_Model_Tuitioninfo();
            if ($Tuition->is_tuition_exist(null, $data["dept_code"], $data["grade"], $data["year"], $data["stu_type"])) {
                $this->view->message = "存在" . $data["grade"] . "级该类型学生的" . $data["year"] . "年学费记录";
                return false;
            } else {
                $Tuition->insert_record($data);
                $this->redirect("/tuition/info/list");
            }
        } catch (Exception $e) {
            $this->view->message = $e->getMessage(); 
        }
        
    }

    public function editAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            die("参数id不能为空");
        }
        try {
            //学费记录
            $Tuition = new Application_Model_Tuitioninfo();
            $tuition = $Tuition->get_tuition_record($Params["id"]);
            $this->view->tuition = $tuition;
//             var_dump($tuition);exit();
            //院系列表
            $Dept = new Application_Model_Deptinfo();
            $parent_code = STUDENT_DEPT;
            $dept_list = $Dept->get_dept_list($parent_code);
            $this->view->dept_list = $dept_list;
            //学生类型列表
            $Stutype = new Application_Model_Stutype();
            $stu_type_list = $Stutype->get_stu_type_list();
            $this->view->stu_type_list = $stu_type_list;
        } catch (Exception $e) {
            throwException($e);
        }
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["tuition_id"]) && "" !== $Params["tuition_id"])) {
            $this->view->message = "参数tuition_id不能为空";
        }
        try {
            $data["tuition_id"] = $Params["tuition_id"];
            $data["dept_code"] = $Params["dept_code"];
            $data["grade"] = $Params["grade"];
            $data["year"] = $Params["year"];
            $data["stu_type"] = $Params["stu_type"];
            $data["tuition_1"] = floatval($Params["tuition_1"]);
            $data["tuition_2"] = floatval($Params["tuition_2"]);
            $data["tuition_3"] = floatval($Params["tuition_3"]);
            $auth = Zend_Auth::getInstance();
            $identity = $auth->getIdentity();
            $data["user_id"] = isset($identity->user_id) ? $identity->user_id : "admin";
            $Tuition = new Application_Model_Tuitioninfo();
            if ($Tuition->is_tuition_exist($data["tuition_id"])) {
                $Tuition->update_record($data, $data["tuition_id"]);
                $this->redirect("/tuition/info/list");
            } else {
                $this->view->message = "不存在{$data["tuition_id"]}的记录";
            }

        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }

    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "学费编码参数id不能为空";
        }
        $Tuition = new Application_Model_Tuitioninfo();
        if ($Tuition->delete_record($Params["id"]) > 0 ) {
            $this->redirect("/tuition/info/list");
        } else {
            $this->view->message = "删除失败，可能因不存在学费代码：{$Params["id"]}";
        }
         
    }

    public function copyAction()
    {
        // 复制一个
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            die("参数id不能为空");
        }
        try {
            //学费记录
            $Tuition = new Application_Model_Tuitioninfo();
            $where_array = array("t.tuition_id='".$Params["id"]."'");
            $tuition = $Tuition->get_tuition_record($where_array);
            $this->view->tuition = $tuition;
//             var_dump($tuition);exit();
            //院系列表
            $Dept = new Application_Model_Deptinfo();
            $parent_code = STUDENT_DEPT;
            $dept_list = $Dept->get_dept_list($parent_code);
            $this->view->dept_list = $dept_list;
            //学生类型列表
            $Stutype = new Application_Model_Stutype();
            $stu_type_list = $Stutype->get_stu_type_list();
            $this->view->stu_type_list = $stu_type_list;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function copyOkAction()
    {
        // action body
        try {
            $Params = $this->getAllParams();
            $data["tuition_id"] = md5(microtime());
            $data["dept_code"] = $Params["dept_code"];
            $data["grade"] = $Params["grade"];
            $data["year"] = $Params["year"];
            $data["stu_type"] = $Params["stu_type"];
            $data["tuition_1"] = floatval($Params["tuition_1"]);
            $data["tuition_2"] = floatval($Params["tuition_2"]);
            $data["tuition_3"] = floatval($Params["tuition_3"]);
            $auth = Zend_Auth::getInstance();
            $identity = $auth->getIdentity();
            $data["user_id"] = isset($identity->user_id) ? $identity->user_id : "admin";
            $Tuition = new Application_Model_Tuitioninfo();
            if ($Tuition->is_tuition_exist(null, $data["dept_code"], $data["grade"], $data["year"], $data["stu_type"])) {
                $this->view->message = "存在 该学院 " . $data["grade"] . "级 该类型学生的" . $data["year"] . "年学费记录";
                return false;
            } else {
                $Tuition->insert_record($data);
                $this->redirect("/tuition/info/list");
            }
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }


}

















