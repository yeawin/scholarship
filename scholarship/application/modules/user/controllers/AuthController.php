<?php
//角色管理
class User_AuthController extends Zend_Controller_Action
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
        $Usertype = new Application_Model_Usertype();
        $type_list = $Usertype->get_type_list();
        $this->view->type_list = $type_list;
    }

    public function addAction()
    {
        // action body
        $Usertype = new Application_Model_Usertype();
        $type_list = $Usertype->get_type_list();
        $this->view->type_list = $type_list;
    }

    public function addOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["type_code"]) && "" !== $Params["type_code"])) {
            $this->view->message = "角色代码不能为空";
            return false;
        }
        if (!(isset($Params["type_name"]) && "" !== $Params["type_name"])) {
            $this->view->message = "角色名字不能为空";
            return false;
        }
        if (!(isset($Params["parent_code"]) && "" !== $Params["parent_code"])) {
            $this->view->message = "父级角色不能为空";
            return false;
        }
        try {
            $data["type_code"] = $Params["type_code"];
            $data["type_name"] = $Params["type_name"];
            foreach ($Params["parent_code"] as $parent_code) {
                $data["parent_code"] = $parent_code;
                $Usertype = new Application_Model_Usertype();
                if ($Usertype->is_user_type_exist($data["type_code"])) {
                    $this->view->message = "角色代码：{$data["type_code"]} 已经存在";
                    return false;
                } 
                $Usertype->insert_record($data);
            }
            $this->redirect("/user/auth/list");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function editAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["code"]) && "" !== $Params["code"])) {
            $this->view->message = "参数code不能为空";
        }
        try {
            $Usertype = new Application_Model_Usertype();
            $type_record = $Usertype->get_type_record($Params["code"]);
            //             var_dump($scholarship_flow);exit();
            $this->view->type_record = $type_record;
        
            $type_list = $Usertype->get_type_list($Params["code"]);
            $this->view->type_list = $type_list;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["type_code"]) && "" !== $Params["type_code"])) {
            $this->view->message = "角色代码不能为空";
            return false;
        }
        if (!(isset($Params["type_name"]) && "" !== $Params["type_name"])) {
            $this->view->message = "角色名字不能为空";
            return false;
        }
        if (!(isset($Params["parent_code"]) && "" !== $Params["parent_code"])) {
            $this->view->message = "父级角色不能为空";
            return false;
        }
        try {
            $data["type_code"] = $Params["type_code"];
            $data["type_name"] = $Params["type_name"];
            foreach ($Params["parent_code"] as $parent_code) {
                $data["parent_code"] = $parent_code;
                $Usertype = new Application_Model_Usertype();
                $Usertype->update_record($data, $Params["type_code"]);
            }
            $this->redirect("/user/auth/list");
        } catch (Exception $e) {
            $this->view->message = $e->getMessage();
        }
    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["code"]) && "" !== $Params["code"])) {
            $this->view->message = "角色参数code不能为空";
            return false;
        }
        
        $User = new Application_Model_User();
        if ($User->is_user_type_exist($Params["code"])) {
            $this->view->message = "删除失败，因为存在该角色的用户，请先清空该角色的用户！";
            return false;
        }
        
        $Usertype = new Application_Model_Usertype();
        if ($Usertype->delete_record($Params["code"]) > 0 ) {
            $this->redirect("/user/auth/list");
        } else {
            $this->view->message = "删除失败，可能因不存在角色，对应code：{$Params["code"]}";
        }
        
    }


}













