<?php

class User_ResourceController extends Zend_Controller_Action
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
        $Resource = new Application_Model_SysResource();
        $resource_list = $Resource->get_resource_list();
        $len = count($resource_list);
        for ($i = 0; $i < $len; $i ++) {
            $ResourceAction = new Application_Model_SysResourceAction();
            $resource_list[$i]["action_list"] = $ResourceAction->get_action_list($resource_list[$i]["resource_id"]);
        }
        $this->view->resource_list = $resource_list;
    }

    public function resourceAddAction()
    {
        // action body
    }

    public function resourceAddOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        $resource_name = $Params["resource_name"];
        $Resource = new Application_Model_SysResource();
        if ($Resource->is_resource_name_exist($resource_name)) {
            $this->view->message = "已经存在该系统资源名称:" . $resource_name;
        } else {
            $data["resource_id"] = md5(microtime() + rand());
            $data["resource_name"] = $Params["resource_name"];
            $data["resource_comment"] = $Params["resource_comment"];
            $Resource->insert_record($data);
            $this->redirect("/user/resource/list");
        }
    }

    public function resourceEditAction()
    {
        // action body
        $Params = $this->getAllParams();
        $Resource = new Application_Model_SysResource();
        $resource_info = $Resource->get_resource_record($Params["resource_id"]);
        $this->view->resource_info = $resource_info;
    }

    public function resourceEditOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        $resource_comment = $Params["resource_comment"];
        $resource_name = $Params["resource_name"];
        $resource_id = $Params["resource_id"];
        $Resource = new Application_Model_SysResource();
        $data["resource_comment"] = $Params["resource_comment"];
        $data["resource_name"] = $Params["resource_name"];
        $Resource->update_record($data, $resource_id);
        $this->redirect("/user/resource/list/");
        
//         if ($Resource->is_resource_exist($resource_id, $resource_name)) {
//             $this->view->message = "已经存在该系统资源名称:" . $resource_name;
//         } else {

//         }
    }

    public function resourceDelAction()
    {
        // action body
        $Params = $this->getAllParams();
        $resource_id = $Params["resource_id"];
        $Privilege = new Application_Model_SysResourcePrivilege();
        if ($Privilege->is_action_privilege_exist(null, $resource_id)) {
            $Resource = new Application_Model_SysResource();
            $resource_info = $Resource->get_resource_record($Params["action_id"]);
            $this->view->message = $resource_info["resource_name"] . "下有方法已经被分配出去，删除前请收回权限！";
        } else {
            $Resource = new Application_Model_SysResource();
            $Resource->delete_record($resource_id);
            $this->redirect("/user/resource/list/");
        }
    }
    
    public function actionListAction()
    {
        // 方法列表
        $Params = $this->getAllParams();
        $resource_id = $Params["resource_id"];
        $Resource = new Application_Model_SysResource();
        $resource_info = $Resource->get_resource_record($resource_id);
        $this->view->resource_info = $resource_info;
        
        $ResourceAction = new Application_Model_SysResourceAction();
        $action_list = $ResourceAction->get_action_list($resource_id);
        $this->view->action_list = $action_list;
    }

    public function actionAddAction()
    {
        // action body
        $Params = $this->getAllParams();
        $resource_id = $Params["resource_id"];
        $Resource = new Application_Model_SysResource();
        $resource_info = $Resource->get_resource_record($resource_id);
        $this->view->resource_info = $resource_info;
    }

    public function actionAddOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        $action_name = $Params["action_name"];
        $resource_id = $Params["resource_id"];
        $ResourceAction = new Application_Model_SysResourceAction();
        if ($ResourceAction->is_action_name_exist($resource_id, $action_name)) {
            $this->view->message = "该资源下已经存在该方法名称:" . $action_name;
        } else {
            $data["action_id"] = md5(microtime() + rand());
            $data["resource_id"] = $Params["resource_id"];
            $data["action_name"] = $Params["action_name"];
            $data["action_comment"] = $Params["action_comment"];
            $ResourceAction->insert_record($data);
            $this->redirect("/user/resource/action-list/resource_id/".$resource_id);
        }
    }

    public function actionEditAction()
    {
        // action body
        $Params = $this->getAllParams();
        $ResourceAction = new Application_Model_SysResourceAction();
        $action_info = $ResourceAction->get_action_record($Params["action_id"]);
        $this->view->action_info = $action_info;
    }

    public function actionEditOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        $action_name = $Params["action_name"];
        $action_id = $Params["action_id"];
        $resource_id = $Params["resource_id"];
        
        $Resource = new Application_Model_SysResource();
        $resource_info = $Resource->get_resource_record($resource_id);
        
        $ResourceAction = new Application_Model_SysResourceAction();
        if (!$ResourceAction->is_action_name_exist($resource_id, $action_name)) {
            $this->view->message = $resource_info["resource_name"] . "资源下不存在存在该方法id:" . $action_id;
        } else {
//             $data["resource_id"] = $Params["resource_id"];
            $data["action_name"] = $Params["action_name"];
            $data["action_comment"] = $Params["action_comment"];
            $ResourceAction->update_record($data, $action_id);
            $this->redirect("/user/resource/action-list/resource_id/" . $resource_id);
        }
    }

    public function actionDelAction()
    {
        // action body
        $Params = $this->getAllParams();
        $action_id = $Params["action_id"];
        $Privilege = new Application_Model_SysResourcePrivilege();
        if ($Privilege->is_action_privilege_exist($action_id)) {
            $ResourceAction = new Application_Model_SysResourceAction();
            $action_info = $ResourceAction->get_action_record($Params["action_id"]);
            $this->view->message = $action_info["action_name"] . "方法已经被分配出去，删除前请收回权限！";
        } else {
            $ResourceAction = new Application_Model_SysResourceAction();
            $ResourceAction->delete_record($action_id);
            $this->redirect("/user/resource/list/");
        }
    }



}

























