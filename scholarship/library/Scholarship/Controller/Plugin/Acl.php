<?php
class Scholarship_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $User_Type = new Application_Model_Usertype();
        $user_type_list = $User_Type->get_type_list();
        //实例化Zend_Acl
        $acl = new Zend_Acl();
        foreach ($user_type_list as $user_type)
        {
            if (null == $user_type["parent_code"]) {
                $acl->addRole($user_type["type_code"]);                  //游客
            } else {
                $role_array = explode('_', $user_type["parent_code"]);
                if (count($role_array) > 1) {
                    $acl->addRole($user_type["type_code"], $role_array);                //组合用户
                } else {
                    $acl->addRole($user_type["type_code"], $user_type["parent_code"]);
                }
            }
        }
        
        //获取系统资源
        $Resource = new Application_Model_SysResource();
        $resource_list = $Resource->get_resource_name_list();
        foreach ($resource_list as $res)
        {
            //添加资源
            $acl->addResource($res['resource_name']);     //默认模块，默认控制器
        }
        //获取资源权限
        $Privilege = new Application_Model_SysResourcePrivilege();
        $privilege_list = $Privilege->get_privilege_list();
        //游客权限
        $acl->deny('0', null, null);
        foreach ($privilege_list as $privilege)
        {
            if ('1' == $privilege['privilege']) {
                $acl->allow($privilege["type_code"], $privilege["resource_name"], $privilege["action_name"]);
            } else {
                $acl->deny($privilege["type_code"], $privilege["resource_name"], $privilege["action_name"]);
            }
        }
        
        //当前用户
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $role = isset($identity->type_code) ? $identity->type_code : "0";
        } else {
            $role = '0';
        }
        $GLOBALS["acl"]  = $acl;
        $GLOBALS["role"]  = $role;
        $GLOBALS["user_id"]  = isset($identity->user_id) ? $identity->user_id : "游客";;
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $resource = "{$module}-{$controller}";
        if ($acl->has($resource)) {
            if (!isset($identity->type_code)) {
                $router = $resource . "-" . $action;
                if ($router != "default-account-valid" && $router != "default-account-forget" ) {
                    $request->setModuleName('default');
                    $request->setControllerName('account');
                    $request->setActionName('login');
                }
            } else if(!$acl->isAllowed($role, $resource, $action)) {
                $request->setModuleName('default');
                $request->setControllerName('error');
                $request->setActionName('deny');
            }
        }
    }
//         exit();
        
//         //添加默认资源
//         $acl->addResource('default-index');     //默认模块，默认控制器
//         $acl->addResource('default-error');     //默认模块，错误控制器
//         $acl->addResource('default-account');    //默认模块，账号控制器
//         $acl->addResource('default-bursary');    //默认模块，奖学金控制器
//         $acl->addResource('default-condition');  //默认模块，条件假控制器

//         //添加奖学金模块资源
//         $acl->addResource('bursary-check');      //奖学金模块，审核控制器
//         $acl->addResource('bursary-dept');       //奖学金模块，学院名额分配控制器
//         $acl->addResource('bursary-flow');       //奖学金模块，流程控制器
//         $acl->addResource('bursary-info');      //奖学金模块，基本信息控制器
//         $acl->addResource('bursary-pay');       //奖学金模块，发放控制器
//         $acl->addResource('bursary-type');       //奖学金模块，类型控制器
        
//         //添加教职工模块资源
//         $acl->addResource('faculty-info');       //教职工模块，基本信息控制器
        
//         //添加学生模块资源
//         $acl->addResource('stutent-condition');   //学生模块，条件控制器
//         $acl->addResource('stutent-info');        //学生模块，基本信息控制器
//         $acl->addResource('stutent-type');       //学生模块，类型控制器
        
//         //添加学费模块资源
//         $acl->addResource('tuition-bank');        //学费模块，银行控制器
//         $acl->addResource('tuition-charging');    //学费模块，工资导入控制器
//         $acl->addResource('tuition-expense');     
//         $acl->addResource('tuition-fee');         
//         $acl->addResource('tuition-info');        //学费模块，学费基本信息控制器
        
//         //添加用户模块资源
//         $acl->addResource('user-account');        //用户模块，账号控制器
//         $acl->addResource('user-auth');           //用户模块，权限控制器
//         $acl->addResource('user-manager');        //用户模块，管理员控制器
        
        
//         //游客权限
//         $acl->deny('0', null, null);
//         $acl->allow('0', array('default-index', 'default-error', 'default-bursary'), 'index');  
        
//         $acl->allow('0', 'default-error', null);
//         $acl->allow('0', 'default-index', 'access-list');
//         $acl->allow('0', 'admin-login', null);
//         $acl->allow('0', 'admin-index', null);
//         $acl->allow('0', array('default-salary', 'default-certify', 'default-leave'), array('teacher-checkin', 'teacher-logout'));
//         $acl->allow('0', 'default-staff', null);
//         //$acl->deny('0', 'default-staff', 'setting');
//         $acl->allow('0', 'default-staff', 'setting');
        
//         //离退教职工用户权限
//         $acl->allow('1', 'default-index', null);
//         $acl->allow('1', array('default-salary', 'pay-regular', 'pay-chart', 'wage-regular', 'wage-retire'), null);
//         $acl->allow('1', array('default-certify'), array("certify"));
       
//         //派遣教职工权限
//         $acl->allow('dispatch', 'default-index', null);
//         $acl->allow('dispatch', array('default-salary', 'pay-regular', 'pay-chart', 'wage-regular', 'wage-retire'), null);
        
//         //在职教职工权限
//         $acl->allow('regular', array('default-certify', 'default-leave'), null);    
            
        
//         //管理员资源分配
//         $acl->allow('admin-110000', array('admin-salary'), null);
//         $acl->allow('admin-101000', array('admin-certify'), null);
//         $acl->allow('admin-100100', array('admin-leave'), null);
//         $acl->allow('admin-100010', array('admin-ykt'), null);
//         $acl->allow('admin-100001', array('admin-gzdr'), null);
//         //$acl->allow('admin-111111', array('admin-super'), null); 
//         $acl->allow('admin-111111', array('admin-manager'), null);
               
//         //当前用户
//         $auth = Zend_Auth::getInstance();
//         if ($auth->hasIdentity()) {
//             $identity = $auth->getIdentity();     
//             $role = $identity->role;         
//             if ('admin' == $role) {
//                 $role .= '-' . $identity->authcode;
// //                 //初始化一个教职工对象
// //                 $Faculty = new Faculty($identity->identity);
// //                 //判断教职工的类型
// //                 $_identity_role = $Faculty->getIdentity();
// //                 $_identity_authcode = $Faculty->getAuthCode();
// //                 $auth_array = array(
// //                         "identity"=>$identity->identity,
// //                         "role"=>$_identity_role,
// //                         "authcode"=>$_identity_authcode,
// //                 );
// //                 //存储用户信息
// //                 $storage = $auth->getStorage();
// //                 $storage->write((object)$auth_array);
// //                 $role .= '-' . $_identity_authcode;
//             }

//         } else {
//             $role = 'guest';
//         }    
//         $module = $request->getModuleName();        
//         $controller = $request->getControllerName();
//         $action = $request->getActionName();
//         $resource = "{$module}-{$controller}";
//         if ($acl->has($resource)){                       
//             if(!$acl->isAllowed($role, $resource, $action)) {              
//                 if ("default" == $module || "pay" == $module || "wage" == $module) {                    
//                     $request->setModuleName('default');
//                     $request->setControllerName('error');
//                     $request->setActionName('noauth');
//                 } else {                    
//                     $request->setModuleName('default');
//                     $request->setControllerName('error');
//                     $request->setActionName('deny');
//                 }

// //                 if (("retire" == $role || "dispatch" == $role) && ("default-certify" == $resource || "default-leave" == $resource)) {
// //                     //获取存储用户信息
// //                     $request->setControllerName('error');
// //                     $request->setActionName('noauth');
// //                 } else             
// //                     if ('guest' != $role) {
// //                         if ("default" == $module) {
// //                             $request->setModuleName($module);
// //                             $request->setControllerName($controller);
// //                             $request->setActionName('index');
// //                         } else {
// //                             $request->setControllerName('index');
// //                             $request->setActionName('index');
// //                         }
// //                     } else {
// //                         $request->setModuleName('default');
// //                         $request->setControllerName('error');
// //                         $request->setActionName('noauth');
// //                     }
//             }        
//         }
//     }
}