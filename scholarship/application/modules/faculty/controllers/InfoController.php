<?php

class Faculty_InfoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        // action body
        $Dept = new Application_Model_Deptinfo();
        $parent_code = STUDENT_DEPT;
        $dept_list = $Dept->get_dept_list($parent_code);
        $this->view->dept_list = $dept_list;
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        try {
            $data["faculty_id"] = $Params["faculty_id"];
            $data["faculty_name"] = $Params["faculty_name"];
            $data["faculty_sex"] = $Params["faculty_sex"];
            $data["dept_code"] = $Params["dept_code"];
            $Facultyinfo = new Application_Model_Facultyinfo();
            if ($Facultyinfo->is_faculty_exist($data["faculty_id"])) {
                $this->view->message = "已经存在工号：{$data["faculty_id"]} 的教工";
                return false;
            } else {
                $Facultyinfo->insert_record($data);
                $User = new Application_Model_User();
                $data = array(
                    "user_id"=>$data["faculty_id"],
                    "password"=>md5('123456'),
                    "type_code"=>'2',
                    "email"=>$Params["email"],
                    "phone"=>$Params["phone"],
                );
                $User->insert_record($data);
            }
        
            $this->redirect("/faculty/info/list");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function editAction()
    {
        // action body
    }

    public function editOkAction()
    {
        // action body
    }

    public function listAction()
    {
        // action body
        $Facultyinfo = new Application_Model_Facultyinfo();
        $where_array = null;
        $order_array = array(
            "dept_name"=>"ASC",
        );
        $faculty_list = $Facultyinfo->get_faculty_list($where_array, $order_array);
        $this->view->faculty_list = $faculty_list;
    }


}











