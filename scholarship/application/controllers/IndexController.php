<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $identity= $auth->getIdentity();
        if (!isset($identity->user_id)) {
            $this->forward("login", "account", "default");
            $this->redirect("/default/account/login");
        }
    }

    public function indexAction()
    {
        // action body
    }


}

