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


}







