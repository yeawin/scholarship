<?php

class Bursary_PayController extends Zend_Controller_Action
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
        // 奖学金列表
        $Bursaryinfo = new Application_Model_Bursaryinfo();
        $scholarship_list = $Bursaryinfo->get_scholarship_list();
        $this->view->scholarship_list = $scholarship_list;
    }

    public function applicantAction()
    {
        // 申请人列表
        //奖学金信息
        $scholarship_id = $this->getParam("id");
        $Scholarship = new Application_Model_Bursaryinfo();
        $scholarship = $Scholarship->get_scholarship_record($scholarship_id);
        $this->view->scholarship = $scholarship;
        
        //申请人
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.is_paid != '1'", "a.is_pass = '1'", "a.scholarship_id='$scholarship_id'");
        $order_array = array("a.scholarship_id", "a.apply_time");
        $apply_list = $Apply->get_apply_list($where_array, $order_array);
        $this->view->apply_list = $apply_list;
    }

    public function grantAction()
    {
        // action body
        $scholarship_id = $this->getParam("id");
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array("a.is_paid != '1'", "a.is_pass = '1'", "a.scholarship_id = '$scholarship_id'");
        $order_array = array("a.scholarship_id", "a.apply_time");
        $apply_list = $Apply->get_apply_list($where_array, $order_array);
        $stu_id = '2012100001';
        foreach ($apply_list as $apply) {
            if ($stu_id === $apply["stu_id"]) {
                $money = $money_ = floatval($apply["money"]);   //初始化奖金总金额、剩余金额
                echo "<b>发放奖学金".$apply["scholarship_name"] . $money . "元</b>:<br>";
                $Deduct = new Application_Model_Tuitiondeduct();
                $where_array = array("d.stu_id = '2012100001'");
                $deduct_info = $Deduct->get_deduct_record($stu_id);
                var_dump($deduct_info);
                $table = "<table class=\"table table-striped table-bordered\">";
                $table .=    "<thead><tr><th>抵扣前奖学金金额</th><th>欠费情况</th><th>抵扣情况</th><th>抵扣后剩余欠费</th><th>抵扣后剩余奖学金</th></tr></thead>";
                $table .=    "<tbody>";
                for($i = 1; $i < 8; $i ++)
                {
                    if ($money <= 0.0) break;   //奖学金抵扣完了
                    for($j = 1; $j < 4; $j ++)
                    {
                        if (1 == $j) {
                            $str = "学费";
                        } else if (2 == $j) {
                            $str = "学杂费";
                        } else if (3 == $j) {
                            $str = "住宿费";
                        }
                        $key = "tuition_". $i . $j;
                        $deduct = $deduct_ = floatval($deduct_info[$key]); //初始化欠费金额、剩余欠费金额
                        if ($deduct > 0.0) {    //当前项目有欠费
                            if ($money > 0.0) { //当前奖学金还有余额
                                $money_ = max($money - $deduct, 0.0);//奖学金扣掉欠费后剩余
                                if ($money_ > 0.0) {    //奖学金还有余额，那欠费就为0
                                    $deduct_ = 0.0;     //抵扣完后，该项目还欠费金额
                                } else {                //奖学金不够抵扣的
                                    $deduct_ = floatval($deduct_info[$key]) - $money; //抵扣完后，该项目还欠费金额
                                }
                                
                                
                                $table .=  "<tr>";
                                $table .=  "<td>￥" . $money . "</td>";      //抵扣前奖学金金额
                                $table .=  "<td>第" . $i . "年" . $str . ":￥". $deduct."</td>";   //欠费情况
//                                 $deduct = min($deduct, $money);
                                $table .=  "<td>第" . $i . "年" . $str . ":￥". min($deduct, $money) . "</td>"; //抵扣情况
                                $table .=  "<td>第" . $i . "年" . $str . ":￥". $deduct_."</td>";  //抵扣后剩余奖学金
                                $table .=  "<td>￥" . $money_ . "</td>";
                                $table .=  "</tr>";
                                $money = $money_;
                                $deduct_info[$key] = floatval($deduct_);

                            }
                        }
                    }
                }
                $table .=     "</tbody>";
                $table .=  "</table>";
                var_dump($deduct_info);
            }
        }
        $this->view->table = $table;
    }

}







