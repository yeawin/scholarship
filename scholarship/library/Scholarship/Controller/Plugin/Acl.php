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
        /*****添加后台角色*****/
        //管理员，拥有一个系统的权限
        $acl->addRole('admin-110000', 'regular');        //工资管理员salary
        $acl->addRole('admin-101000', 'regular');        //证明管理员certify
        $acl->addRole('admin-100100', 'regular');        //探亲管理员leave
        $acl->addRole('admin-100010', 'regular');        //自聘管理员ykt
        $acl->addRole('admin-100001', 'regular');        //工资导入管理员ykt
        
        //管理员，拥有两个系统权限，其中一个为salary
        $acl->addRole('admin-111000', array('admin-110000', 'admin-101000'));//salary, certify
        $acl->addRole('admin-110100', array('admin-110000', 'admin-100100'));//salary, leave
        $acl->addRole('admin-110010', array('admin-110000', 'admin-100010'));//salary, ykt
        $acl->addRole('admin-110001', array('admin-110000', 'admin-100001'));//salary, gzdr
        //管理员，拥有两个系统权限，其中一个为certify
        $acl->addRole('admin-101100', array('admin-101000', 'admin-100100')); //cerity, leave
        $acl->addRole('admin-101010', array('admin-101000', 'admin-100010')); //cerity, ykt
        $acl->addRole('admin-101001', array('admin-101000', 'admin-100001')); //cerity, gzdr
        //管理员，拥有两个系统权限，其中一个为leave
        $acl->addRole('admin-100110', array('admin-100100', 'admin-100010')); //leave, ykt
        $acl->addRole('admin-100101', array('admin-100100', 'admin-100001')); //leave, gzdr
        //管理员，拥有两个系统权限，其中一个为ykt
        $acl->addRole('admin-100011', array('admin-100010', 'admin-100001')); //ykt, gzdr
        
        //管理员，拥有三个系统权限，其中salary, cetify
        $acl->addRole('admin-111100', array('admin-111000', 'admin-100100'));//salary, certify, leave
        $acl->addRole('admin-111010', array('admin-111000', 'admin-100010'));//salary, certify, ykt
        $acl->addRole('admin-111001', array('admin-111000', 'admin-100001'));//salary, certify, gzdr
        //管理员，拥有三个系统权限，其中salary, leave
        $acl->addRole('admin-110110', array('admin-110100', 'admin-100010'));//salary, leave, ykt
        $acl->addRole('admin-110101', array('admin-110100', 'admin-100001'));//salary, leave, gzdr
        //管理员，拥有三个系统权限，其中salary, ykt
        $acl->addRole('admin-110011', array('admin-110010', 'admin-100001'));//salary, ykt, gzdr
        //管理员，拥有三个系统权限，其中certify, leave
        $acl->addRole('admin-101110', array('admin-101100', 'admin-100010'));//certify, leave, ykt
        $acl->addRole('admin-101101', array('admin-101100', 'admin-100001'));//certify, leave, gzdr
        //管理员，拥有三个系统权限，其中certify, ykt
        $acl->addRole('admin-101011', array('admin-101010', 'admin-100001'));//leave, ykt, gzdr
        //管理员，拥有三个系统权限，其中leave, ykt
        $acl->addRole('admin-100111', array('admin-100110', 'admin-100001'));//leave, ykt, gzdr
        
        //管理员，拥有四个系统权限
        $acl->addRole('admin-111110', array('admin-111100', 'admin-100010'));//salary, cerify, leave, ykt
        $acl->addRole('admin-111101', array('admin-111100', 'admin-100001'));//salary, cerify, leave, gzdr
        $acl->addRole('admin-111011', array('admin-111010', 'admin-100001'));//salary, cerify, ykt, gzdr
        $acl->addRole('admin-110111', array('admin-110110', 'admin-100001'));//salary, leave, ykt, gzdr
        $acl->addRole('admin-101111', array('admin-101110', 'admin-100001'));//certify, leave, ykt, gzdr
        
        $acl->addRole('admin-111111', array('admin-111110', 'admin-100001'));

        
        //添加默认资源
        $acl->addResource('default-index');     //默认模块，默认控制器
        $acl->addResource('default-error');     //默认模块，错误控制器
        $acl->addResource('default-salary');    //默认模块，工资控制器
        $acl->addResource('default-certify');   //默认模块，证明控制器
        $acl->addResource('default-leave');     //默认模块，探亲假控制器
        $acl->addResource('default-staff');     //默认模块，教职工控制器
        //添加工资模块资源
        $acl->addResource('pay-index');         //工资模块，默认控制器
        $acl->addResource('pay-regular');       //工资模块，在职控制器
        $acl->addResource('pay-chart');         //工资模块，图表控制器
        $acl->addResource('wage-regular');      //2016工资改革后工资模块，在职控制器
        $acl->addResource('wage-retire');       //2016工资改革后工资模块，离休控制器
        
        //添加管理员资源
        $acl->addResource('admin-login');       //管理员模块，登陆控制器
        $acl->addResource('admin-index');       //管理员模块，默认控制器
        
        $acl->addResource('admin-salary');      //管理员模块，工资控制器
        $acl->addResource('admin-certify');     //管理员模块，证明控制器
        $acl->addResource('admin-leave');       //管理员模块，探亲控制器
        $acl->addResource('admin-ykt');         //管理员模块，自聘人员控制器
        $acl->addResource('admin-gzdr');        //管理员模块，工资导入控制器
        //$acl->addResource('admin-super');       //管理员模块，超级管理员控制器
        $acl->addResource('admin-manager');       //管理员模块，超级管理员控制器
        
        //游客权限
        $acl->deny('guest', null, null);
        $acl->allow('guest', array('default-index', 'default-error', 'default-salary', 'default-certify', 'default-leave', 'pay-index', 'pay-regular'), 'index');       
        $acl->allow('guest', 'default-error', null);
        $acl->allow('guest', 'default-index', 'access-list');
        $acl->allow('guest', 'admin-login', null);
        $acl->allow('guest', 'admin-index', null);
        $acl->allow('guest', array('default-salary', 'default-certify', 'default-leave'), array('teacher-checkin', 'teacher-logout'));
        $acl->allow('guest', 'default-staff', null);
        //$acl->deny('guest', 'default-staff', 'setting');
        $acl->allow('guest', 'default-staff', 'setting');
        
        //离退教职工用户权限
        $acl->allow('retire', 'default-index', null);
        $acl->allow('retire', array('default-salary', 'pay-regular', 'pay-chart', 'wage-regular', 'wage-retire'), null);
        $acl->allow('retire', array('default-certify'), array("certify"));
       
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