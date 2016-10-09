<?php

class User_AccountController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function searchAction()
    {
        // action body
    }

    public function resetAction()
    {
        // action body
        $Params = $this->getAllParams();
        if ("" == $Params["user_id"]) {
            $this->view->message = "用户名不能为空！";
            $View = new Zend_View();
            $this->view->addScriptPath(APPLICATION_PATH . "/views/scripts");
            $this->_helper->viewRenderer("/error/warning", null, true);
            return false;
        }
        $user_id = $Params["user_id"];
        $User = new Application_Model_User();
        $user_info = $User->get_stu_info($user_id);
        if (null == $user_info) {
            $this->view->message = "用户名/学号/工号： <b> {$user_id} </b> 查无结果。";
            $View = new Zend_View();
            $this->view->addScriptPath(APPLICATION_PATH . "/views/scripts");
            $this->_helper->viewRenderer("/error/warning", null, true);
            return false;
        }
        $this->view->user_info = $user_info;
    }

    public function resetOkAction()
    {
        // action body
        $Params = $this->getAllParams();
 
        if ($Params["n_pwd"] == "") {
            $this->view->message = "新密码不能为空！";
            $View = new Zend_View();
            $this->view->addScriptPath(APPLICATION_PATH . "/views/scripts");
            $this->_helper->viewRenderer("/error/warning", null, true);
            return false;
        }
        if ($Params["n_pwd"] !== $Params["n_pwd_"]) {
            $this->view->message = "两次新密码不一致！";
            $View = new Zend_View();
            $this->view->addScriptPath(APPLICATION_PATH . "/views/scripts");
            $this->_helper->viewRenderer("/error/warning", null, true);
            return false;
        }
        $user_id = $Params["user_id"];
        $User = new Application_Model_User();
        if ($User->update_record(array("password"=>md5($Params["n_pwd"])), $user_id) > 0) {
            $this->view->message = "修改成功！";
            return true;
        } else {
            $this->view->message = "修改失败！";
            return false;
        }
    }

    public function listAction()
    {
        // 用户列表
        $Params = $this->getAllParams();
        $type = (isset($Params["type"]) && $Params["type"] !== "")? $Params["type"] : null;
        $where_array = null;
        if (null !== $type) {
            $where_array = array("u.type_code = '$type'");
        }
        $User = new Application_Model_User();
        $user_list = $User->get_user_list($where_array);
        $this->view->user_list = $user_list;
    }

    public function setAuthAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!isset($Params["id"])) {
            die("没有设定用户id");
        }
        $this->view->user_id = $Params["id"];
        $Usertype = new Application_Model_Usertype();
        $user_type_list = $Usertype->get_type_list();
        $this->view->user_type_list = $user_type_list;
    }

    public function setAuthOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        $data["type_code"] = $Params["type_code"];
        $User = new Application_Model_User();
        $User->update_record($data, $Params["user_id"]);
        $this->redirect("/user/account/list");
    }


}













