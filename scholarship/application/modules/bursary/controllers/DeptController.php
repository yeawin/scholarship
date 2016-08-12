<?php

class Bursary_DeptController extends Zend_Controller_Action
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
        
        $ScholarshipDept = new Application_Model_Bursarydept();
        $scholarship_distribute = $ScholarshipDept->get_scholarship_distribute($scholarship_id);
        $this->view->scholarship_distribute = $scholarship_distribute;
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
        
        $Dept = new Application_Model_Deptinfo();
        $parent_code = STUDENT_DEPT;
        $dept_list = $Dept->get_dept_list($parent_code);
        $this->view->dept_list = $dept_list;
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["dept_code"]) && "" !== $Params["dept_code"])) {
            $this->view->message = "院系不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_dept_num"]) && "" !== $Params["scholarship_dept_num"])) {
            $this->view->message = "奖学金名额不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_year"]) && "" !== $Params["scholarship_year"])) {
            $this->view->message = "奖学金年级不能为空";
            return false;
        }
        try {
            $scholarship_id = $Params["scholarship_id"];
            $data["scholarship_dept_id"] = md5(microtime() + rand());
            $data["scholarship_id"] = $scholarship_id;
            $data["dept_code"] = $Params["dept_code"];
            $data["scholarship_dept_num"] = intval($Params["scholarship_dept_num"]);
            
            $ScholarInfo = new Application_Model_Bursaryinfo();
            $scholarship_info = $ScholarInfo->get_scholarship_record($scholarship_id);
            
            $ScholoarDept= new Application_Model_Bursarydept();
            $num = $ScholoarDept->count_scholarship_dept($scholarship_id);
            if ($num >= $scholarship_info["num"]) {
                $this->view->message = "分配出去的名额 {$num} 已经不小于总名额 {$scholarship_info["num"]}！";
                return false;
            } elseif (($num + $data["scholarship_dept_num"]) > $scholarship_info["num"]) {
                $this->view->message = "加上当前要分配名额：{$data["scholarship_dept_num"]}，已经不小于总名额{$scholarship_info["num"]}！";
                return false;
            }
            if ($ScholoarDept->is_scholarship_dept_distribute_exist($scholarship_id, $data["dept_code"])) {
                $this->view->message = "已经存在该学院奖学金名额:{$data["scholarship_name"]}";
                return false;
            }
            $ScholoarDept->insert($data);
            $this->redirect("/bursary/dept/list/id/{$Params["scholarship_id"]}");
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
            $ScholarshipDept = new Application_Model_Bursarydept();
            $scholarship_dept_record = $ScholarshipDept->get_scholarship_dept_record($Params["id"]);
            $this->view->scholarship_dept_record = $scholarship_dept_record;
            
            $Dept = new Application_Model_Deptinfo();
            $parent_code = STUDENT_DEPT;
            $dept_list = $Dept->get_dept_list($parent_code);
            $this->view->dept_list = $dept_list;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["dept_code"]) && "" !== $Params["dept_code"])) {
            $this->view->message = "院系不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_dept_num"]) && "" !== $Params["scholarship_dept_num"])) {
            $this->view->message = "奖学金名额不能为空";
            return false;
        }
        if (!(isset($Params["scholarship_year"]) && "" !== $Params["scholarship_year"])) {
            $this->view->message = "奖学金年级不能为空";
            return false;
        }
        try {
            $scholarship_id = $Params["scholarship_id"];
            $scholarship_dept_id = $Params["scholarship_dept_id"];
            $data["scholarship_dept_id"] = $scholarship_dept_id;
            $data["scholarship_id"] = $scholarship_id;
            $data["dept_code"] = $Params["dept_code"];
            $data["scholarship_dept_num"] = intval($Params["scholarship_dept_num"]);
        
            $ScholarshipInfo = new Application_Model_Bursaryinfo();
            $scholarship_info = $ScholarshipInfo->get_scholarship_record($scholarship_id);
        
            $ScholoarshipDept= new Application_Model_Bursarydept();
            $scholarship_dept_record = $ScholoarshipDept->get_scholarship_dept_record($scholarship_dept_id);
            $num = $ScholoarshipDept->count_scholarship_dept($scholarship_id);
            if (($num - $scholarship_dept_record["scholarship_dept_num"]) >= $scholarship_info["num"]) { //扣除原来的数据
                $this->view->message = "分配出去的名额 {$num} 已经不小于总名额 {$scholarship_info["num"]}！";
                return false;
            } elseif (($num - $scholarship_dept_record["scholarship_dept_num"] + $data["scholarship_dept_num"]) > $scholarship_info["num"]) {
                $this->view->message = "已经分配了 {$num} 加上当前要分配名额：{$data["scholarship_dept_num"]}，已经不小于总名额{$scholarship_info["num"]}！";
                return false;
            }
            $ScholoarshipDept->update_record($data, $scholarship_dept_id);
            $this->redirect("/bursary/dept/list/id/{$scholarship_id}");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
//         var_dump($Params);exit();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            $this->view->message = "参数id不能为空";
            return false;
        }
        $ScholarshipDept = new Application_Model_Bursarydept();
        $scholarship_dept_record = $ScholarshipDept->get_scholarship_dept_record($Params["id"]);
        if ($ScholarshipDept->delete_record($Params["id"]) > 0 ) {
            $this->redirect("/bursary/dept/list/id/{$scholarship_dept_record["scholarship_id"]}");
        } else {
            $this->view->message = "删除失败，可能因不存在对应记录id号为：{$Params["id"]}";
        }
    }


}













