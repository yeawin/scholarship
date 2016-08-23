<?php

class Tuition_BankController extends Zend_Controller_Action
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
        $Bank = new Application_Model_StuBank();
        $bank_list = $Bank->get_stu_bank_list();
//         var_dump($bank_list);
        $this->view->bank_list = $bank_list;
    }

    public function editAction()
    {
        // action body
        $Params = $this->getAllParams();
        if (!(isset($Params["id"]) && "" !== $Params["id"])) {
            die("参数id不能为空");
        }
        try {
            $stu_id = $Params["id"];
            $Bank = new Application_Model_StuBank();
            $stu_bank_info = $Bank->get_stu_bank_info($stu_id);
            $this->view->stu_bank_info = $stu_bank_info;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function delAction()
    {
        // action body
        $Params = $this->getAllParams();
        $card_id = $Params["id"];
        $Bank = new Application_Model_StuBank();
        $Bank->delete_record($card_id);
        $this->redirect("/tuition/bank/list");
    }

    public function editOkAction()
    {
        // action body
        $Params = $this->getAllParams();
        $stu_id = $Params["stu_id"];
        date_default_timezone_set("PRC");
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        $data["stu_id"] = $stu_id;
        $data["cardno"] = $Params["cardno"];
        $data["add_time"] = date("Y-m-d H:i:s");
        $data["operator"] = $user_id;
        $Bank = new Application_Model_StuBank();
        if (!$Bank->is_bank_cardno_exist($stu_id)) {
            $data["card_id"] = md5(time() + rand());
            $Bank->insert_record($data);
        } else {
            $Bank->update_record($data, $stu_id);
        }
        $this->redirect("/tuition/bank/list");
    }


}









