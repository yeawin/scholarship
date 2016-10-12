<?php

class User_AclController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body 
        $Usertype = new Application_Model_Usertype();
        $type_list = $Usertype->get_type_list();
        $len = count($type_list);
//         $privilege = array();
        for($i = 0; $i < $len; $i ++) {
            $type_code = $type_list[$i]["type_code"];
            $Privilege = new Application_Model_SysResourcePrivilege();
            $privilege_list = $Privilege->get_privilege_list($type_code, true);
            $type_list[$i]["privilege"] = $privilege_list;
        }
        $this->view->privilege = $type_list;
    }

    public function editAction()
    {
        // action body
        $Params = $this->getAllParams();
        $type_code = $Params["type_code"];
        $UserType = new Application_Model_Usertype();
        $type_record = $UserType->get_type_record($type_code);
        $this->view->type_record = $type_record;
        
        $Resource = new Application_Model_SysResource();
        $resource_list = $Resource->get_resource_list();
        $len = count($resource_list);
        for($i = 0; $i < $len; $i ++) {
            $Action = new Application_Model_SysResourceAction();
            $resource_list[$i]["action_list"] = $Action->get_action_list($resource_list[$i]["resource_id"]);
            
            $Privilege = new Application_Model_SysResourcePrivilege();
            $action_id_list = $Privilege->get_privilege_action_id_list($type_code, null, $resource_list[$i]["resource_id"]);
            
            $j = 0;
            $privilege_list = array();
            foreach ($action_id_list as $action_id) {
                $privilege_list[$j ++] = $action_id["action_id"];
            }
            $resource_list[$i]["privilege_list"] = $privilege_list;
        }
        $this->view->resource_list = $resource_list;
        
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        $type_code = $Params["type_code"];
        $Privilege = new Application_Model_SysResourcePrivilege();
        $Privilege->delete_record(null, null, $type_code);
        foreach ($Params as $key=>$val)
        {
            if ($key == "module" || $key == "controller" || $key == "action") {
                continue;
            } else {
                if(strpos($key, "action") !== false) {  //找到了资源变量
                    $resource_id = substr($key, 7);
                    $action_list = $Params["action_" . $resource_id];
                    foreach ($action_list as $action) {
                        $data["privilege_id"] = md5(microtime() + rand());
                        $data["type_code"] = $type_code;
                        $data["resource_id"] = $resource_id;
                        $data["action_id"] = $action;
                        $data["privilege"] = '1';
                        $Privilege->insert_record($data);
                    }
                }
            }
        }
        $this->redirect("/user/acl/index");
    }


}





