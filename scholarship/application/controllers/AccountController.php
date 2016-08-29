<?php

class AccountController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body
        $this->_helper->layout()->disableLayout();
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->redirect("/");
    }

    public function validAction()
    {
        // action body
        $Params = $this->getAllParams();
        $user_id = $Params["user_id"];
        $password = $Params["password"];
//         $db = Zend_Registry::get("db");
        //实例化一个Auth适配器
        $authAdapter = new Zend_Auth_Adapter_DbTable(null, 'tb_users', 'user_id', 'password');
        
        //设置认证用户的账号密码
        $authAdapter->setIdentity($user_id)
                    ->setCredential(md5($password));
        //实现authenticate方法
        $result = $authAdapter->authenticate();
        if ($result->isValid()) {
            //通过认证
            $User = new Application_Model_User();
            $user_info = $User->get_user_record($user_id);
            //判断教职工的类型
            $auth_array = array(
                "user_id"=>$user_id,
                "user_type"=>isset($user_info["user_type"]) ? $user_info["user_type"] : null,
            );
            //获得getInstance实例
            $auth = Zend_Auth::getInstance();
            //存储用户信息
            $storage = $auth->getStorage();
            $storage->write((object)$auth_array);
            $this->redirect("/default");
        } else {
            $this->view->message = "登陆失败，账号密码错误账号不存在！";
            return false;
        }
    }

    public function forgetAction()
    {
        // action body
    }

    public function resetAction()
    {
        // action body
    }

    public function resetOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        if ("" == $Params["c_pwd"]) {
            $this->view->message = "当前密码不能为空！";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        if ($Params["n_pwd"] == "") {
            $this->view->message = "新密码不能为空！";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        if ($Params["n_pwd"] !== $Params["n_pwd_"]) {
            $this->view->message = "两次新密码不一致！";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        //获得getInstance实例
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        
        $User = new Application_Model_User();
        $user_info = $User->get_user_record($user_id);
        if (md5($Params["c_pwd"]) !== $user_info["password"]) {
            $this->view->message = "当前密码不正确！";
            $this->renderScript("/error/warning.phtml");
            return false;
        }
        if ($User->update_record(array("password"=>md5($Params["n_pwd"])), $user_id) > 0) {
            $this->view->message = "修改成功！";
            return true;
        } else {
            $this->view->message = "修改失败！";
            return false;
        }
    }


}













