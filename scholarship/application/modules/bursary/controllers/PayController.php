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
        // 奖学金信息
        $scholarship_id = $this->getParam("id");
        $Scholarship = new Application_Model_Bursaryinfo();
        $scholarship = $Scholarship->get_scholarship_record($scholarship_id);
        $this->view->scholarship = $scholarship;
        
        // 申请人
        $Apply = new Application_Model_Bursaryapply();
        // $where_array = array("a.is_paid != '1'", "a.is_pass = '1'", "a.scholarship_id='$scholarship_id'");
        $where_array = array(
            "a.scholarship_id='$scholarship_id'"
        );
        $order_array = array(
            "a.scholarship_id",
            "a.apply_time"
        );
        $apply_list = $Apply->get_apply_list($where_array, $order_array);
        $this->view->apply_list = $apply_list;
    }

    public function grantAction()
    {
        // 奖学金发放
        $scholarship_id = $this->getParam("id");
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array(
            "a.is_paid != '1'",
            "a.is_pass = '1'",
            "a.scholarship_id = '$scholarship_id'"
        );
        $order_array = array(
            "a.scholarship_id",
            "a.apply_time"
        );
        $apply_list = $Apply->get_apply_list($where_array, $order_array);
        // var_dump($apply_list);return false;
        $table = "";
        foreach ($apply_list as $apply) {
            $table .= $this->grant_detail($apply);
        }
        $this->redirect("/bursary/pay/applicant/id/{$scholarship_id}");
        // echo ($table);exit();
        $this->view->table = $table;
    }

    protected function grant_detail($apply)
    {
        date_default_timezone_set("PRC");
        $time = date("Y-m-d H:i:s");
        $stu_id = $apply["stu_id"];
        if (null == $stu_id) {
            return false;
        }
        $table = "";
        // if ($stu_id === $apply["stu_id"]) {
        $money = $money_ = floatval($apply["money"]); // 初始化奖金总金额、剩余金额
        $table .= "<h4>发放" . $stu_id . "的奖学金" . $apply["scholarship_name"] . $money . "元</b></h4>";
        $Deduct = new Application_Model_Tuitiondeduct();
        $where_array = array(
            "d.stu_id = '$stu_id'"
        );
        $deduct_info = $Deduct->get_deduct_record($stu_id);
        
        // $table .= "<table class=\"table table-striped table-bordered\">";
        // $table .= "<thead><tr><th>抵扣前奖学金金额</th><th>欠费情况</th><th>抵扣情况</th><th>抵扣后剩余欠费</th><th>抵扣后剩余奖学金</th></tr></thead>";
        // $table .= "<tbody>";
        for ($i = 1; $i < 8; $i ++) {
            if ($money <= 0.0)
                break; // 奖学金抵扣完了
            for ($j = 1; $j < 4; $j ++) {
                if (1 == $j) {
                    $str = "学费";
                } else 
                    if (2 == $j) {
                        $str = "学杂费";
                    } else 
                        if (3 == $j) {
                            $str = "住宿费";
                        }
                $key = "tuition_" . $i . $j;
                $deduct = $deduct_ = floatval($deduct_info[$key]); // 初始化欠费金额、剩余欠费金额
                if ($deduct > 0.0) { // 当前项目有欠费
                    if ($money > 0.0) { // 初始奖学金还有余额
                        $money_ = max($money - $deduct, 0.0); // 抵扣欠费后，剩余奖学金
                        if ($money_ > 0.0) { // 奖学金够抵扣的，还有余额
                            $deduct_ = 0.0; // 奖学金还有余额，那欠费就为0
                        } else { // 奖学金不够抵扣的
                            $deduct_ = floatval($deduct_info[$key]) - $money; // 抵扣完后，该项目还有欠费，计算欠费额
                        }
                        
                        // $table .= "<tr>";
                        // $table .= "<td>￥" . $money . "</td>"; //抵扣前奖学金金额
                        // $table .= "<td>第" . $i . "年" . $str . ":￥" . $deduct . "</td>"; //欠费情况
                        // $table .= "<td>第" . $i . "年" . $str . ":￥" . min($deduct, $money) . "</td>"; //抵扣情况
                        // $table .= "<td>第" . $i . "年" . $str . ":￥" . $deduct_. "</td>"; //抵扣后剩余奖学金
                        // $table .= "<td>￥" . $money_ . "</td>";
                        // $table .= "</tr>";
                        
                        // 学费信息
                        $Tuition = new Application_Model_Tuitioninfo();
                        $where_array = array(
                            "dept_code='" . $apply["dept_code"] . "'",
                            "grade='" . $apply["stu_grade"] . "'",
                            "year='" . ($apply["stu_grade"] + $i - 1) . "'",
                            "stu_type='" . $apply["stu_type"] . "'"
                        );
                        $tuition_info = $Tuition->get_tuition_record($where_array);
                        
                        // 记录扣费流水
                        $data = array(
                            "history_id" => $this->udate("YmdHisu"),
                            "stu_id" => $stu_id,
                            "tuition_id" => $tuition_info["tuition_id"],
                            "tuition_key" => $key,
                            "amount" => min($deduct, $money),
                            "scholarship_id" => $apply["scholarship_id"], // 由于奖学金抵扣，记录奖学金id
                            "datetime" => $time
                        );
                        // 记录下缴费信息（奖学金抵扣的）
                        $History = new Application_Model_Tuitionhistory();
                        $History->insert_record($data);
                        
                        // 更新欠费信息
                        $deduct_info[$key] = floatval($deduct_);
                        $Deduct->update_record($deduct_info, $stu_id);
                        
                        // 将该申请的奖学金标记为已发放
                        $Apply = new Application_Model_Bursaryapply();
                        $Apply->update_record(array(
                            "is_paid" => '1'
                        ), $apply["apply_id"]);
                        
                        $money = $money_; // 剩余奖学金
                    }
                }
            }
            // }
            // $table .= "</tbody>";
            // $table .= "</table>";
        }
        return $table;
    }

    private function udate($format = 'u', $utimestamp = null)
    {
        if (is_null($utimestamp)) {
            $utimestamp = microtime(true);
        }
        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);
        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }

    public function printAction()
    {
        // 奖学金信息
        $scholarship_id = $this->getParam("id");
        $Scholarship = new Application_Model_Bursaryinfo();
        $scholarship = $Scholarship->get_scholarship_record($scholarship_id);
        $this->view->scholarship = $scholarship;
        
        // 申请人
        $Apply = new Application_Model_Bursaryapply();
        $where_array = array(
            "a.is_paid = '1'",
            "a.is_pass = '1'",
            "a.scholarship_id='$scholarship_id'"
        );
        $order_array = array(
            "a.scholarship_id",
            "a.apply_time"
        );
        $apply_list = $Apply->get_apply_list($where_array, $order_array);
        $this->view->apply_list = $apply_list;
    }
}









