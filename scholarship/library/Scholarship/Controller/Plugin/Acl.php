<?php
include 'Yeawin/Staff.php';
class Yeawin_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        //实例化Zend_Acl
        $acl = new Zend_Acl();
        //添加前台角色
        $acl->addRole('0');                  //游客
        $acl->addRole('1', '0');             //学生
        $acl->addRole('2', '1');             //辅导员
        $acl->addRole('3', '2');             //教学秘书
        $acl->addRole('9', '3');             //学生处
        $acl->addRole('3', '2');             //财务处
        $acl->addRole('3', '2');             //管理员
        $acl->addRole('3', '2');             //超级管理员

        
        //添加默认资源
        $acl->addResource('default-index');     //默认模块，默认控制器
        $acl->addResource('default-error');     //默认模块，错误控制器
        $acl->addResource('default-account');    //默认模块，账号控制器
        $acl->addResource('default-bursary');    //默认模块，奖学金控制器
        $acl->addResource('default-condition');  //默认模块，条件假控制器

        //添加奖学金模块资源
        $acl->addResource('bursary-check');      //奖学金模块，审核控制器
        $acl->addResource('bursary-dept');       //奖学金模块，学院名额分配控制器
        $acl->addResource('bursary-flow');       //奖学金模块，流程控制器
        $acl->addResource('bursary-info');      //奖学金模块，基本信息控制器
        $acl->addResource('bursary-pay');       //奖学金模块，发放控制器
        $acl->addResource('bursary-type');       //奖学金模块，类型控制器
        
        //添加教职工模块资源
        $acl->addResource('faculty-info');       //教职工模块，基本信息控制器
        
        //添加学生模块资源
        $acl->addResource('stutent-condition');   //学生模块，条件控制器
        $acl->addResource('stutent-info');        //学生模块，基本信息控制器
        $acl->addResource('stutent-type');       //学生模块，类型控制器
        
        //添加学费模块资源
        $acl->addResource('tuition-bank');        //学费模块，银行控制器
        $acl->addResource('tuition-charging');    //学费模块，工资导入控制器
        $acl->addResource('tuition-expense');     
        $acl->addResource('tuition-fee');         
        $acl->addResource('tuition-info');        //学费模块，学费基本信息控制器
        
        //添加用户模块资源
        $acl->addResource('user-account');        //用户模块，账号控制器
        $acl->addResource('user-auth');           //用户模块，权限控制器
        $acl->addResource('user-manager');        //用户模块，管理员控制器
        
        
        //游客权限
        $acl->deny('0', null, null);
        $acl->allow('0', array('default-index', 'default-error', 'default-bursary'), 'index');  
        
        $acl->allow('0', 'default-error', null);
        $acl->allow('0', 'default-index', 'access-list');
        $acl->allow('0', 'admin-login', null);
        $acl->allow('0', 'admin-index', null);
        $acl->allow('0', array('default-salary', 'default-certify', 'default-leave'), array('teacher-checkin', 'teacher-logout'));
        $acl->allow('0', 'default-staff', null);
        //$acl->deny('0', 'default-staff', 'setting');
        $acl->allow('0', 'default-staff', 'setting');
        
        //离退教职工用户权限
        $acl->allow('1', 'default-index', null);
        $acl->allow('1', array('default-salary', 'pay-regular', 'pay-chart', 'wage-regular', 'wage-retire'), null);
        $acl->allow('1', array('default-certify'), array("certify"));
       
        //派遣教职工权限
        $acl->allow('dispatch', 'default-index', null);
        $acl->allow('dispatch', array('default-salary', 'pay-regular', 'pay-chart', 'wage-regular', 'wage-retire'), null);
        
        //在职教职工权限
        $acl->allow('regular', array('default-certify', 'default-leave'), null);    
            
        
        //管理员资源分配
        $acl->allow('admin-110000', array('admin-salary'), null);
        $acl->allow('admin-101000', array('admin-certify'), null);
        $acl->allow('admin-100100', array('admin-leave'), null);
        $acl->allow('admin-100010', array('admin-ykt'), null);
        $acl->allow('admin-100001', array('admin-gzdr'), null);
        //$acl->allow('admin-111111', array('admin-super'), null); 
        $acl->allow('admin-111111', array('admin-manager'), null);
               
        //当前用户
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();     
            $role = $identity->role;         
            if ('admin' == $role) {
                $role .= '-' . $identity->authcode;
//                 //初始化一个教职工对象
//                 $Faculty = new Faculty($identity->identity);
//                 //判断教职工的类型
//                 $_identity_role = $Faculty->getIdentity();
//                 $_identity_authcode = $Faculty->getAuthCode();
//                 $auth_array = array(
//                         "identity"=>$identity->identity,
//                         "role"=>$_identity_role,
//                         "authcode"=>$_identity_authcode,
//                 );
//                 //存储用户信息
//                 $storage = $auth->getStorage();
//                 $storage->write((object)$auth_array);
//                 $role .= '-' . $_identity_authcode;
            }

        } else {
            $role = 'guest';
        }    
        $module = $request->getModuleName();        
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $resource = "{$module}-{$controller}";
        if ($acl->has($resource)){                       
            if(!$acl->isAllowed($role, $resource, $action)) {              
                if ("default" == $module || "pay" == $module || "wage" == $module) {                    
                    $request->setModuleName('default');
                    $request->setControllerName('error');
                    $request->setActionName('noauth');
                } else {                    
                    $request->setModuleName('default');
                    $request->setControllerName('error');
                    $request->setActionName('deny');
                }

//                 if (("retire" == $role || "dispatch" == $role) && ("default-certify" == $resource || "default-leave" == $resource)) {
//                     //获取存储用户信息
//                     $request->setControllerName('error');
//                     $request->setActionName('noauth');
//                 } else             
//                     if ('guest' != $role) {
//                         if ("default" == $module) {
//                             $request->setModuleName($module);
//                             $request->setControllerName($controller);
//                             $request->setActionName('index');
//                         } else {
//                             $request->setControllerName('index');
//                             $request->setActionName('index');
//                         }
//                     } else {
//                         $request->setModuleName('default');
//                         $request->setControllerName('error');
//                         $request->setActionName('noauth');
//                     }
            }        
        }
    }
}