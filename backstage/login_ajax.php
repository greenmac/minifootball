<?php

include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';
// $req = isset($_POST['req']) && trim($_POST['req']) ? trim($_POST['req']) : 0;
// $pass = isset($_POST['pass']) && trim($_POST['pass']) ? trim($_POST['pass']) : 0;
$start_time = date('Y-m-d H:i:s');
$end_time = date('Y-m-d H:i:s');

// pre($_POST); exit;
$_SESSION['manager_name'] = isset($_POST['manager_name']) && trim($_POST['manager_name']) ? trim($_POST['manager_name']) : '';
$_SESSION['manager_password'] = isset($_POST['manager_password']) && trim($_POST['manager_password']) ? trim($_POST['manager_password']) : '';
$_captcha = isset($_POST['ct_captcha']) && trim($_POST['ct_captcha']) ? trim($_POST['ct_captcha']) : '';
$_SESSION['session_id'] = isset($_POST['session_id']) && trim($_POST['session_id']) ? trim($_POST['session_id']) : '';
$session_id = 0;
if (isset($_SESSION['session_id']) && trim($_SESSION['session_id'])) {
    $session_id = $_SESSION['session_id'];
} else {
    $session_id = (time() % 10000).''.mt_rand(10000000, 99999999);
    $_SESSION['session_id'] = $session_id;
}

$manager_name = $_SESSION['manager_name'];
$manager_password = $_SESSION['manager_password'];
$check_err_msg = '';
$check_err_code = 0;

$managerSql = "SELECT count(*) as logincount from manager where manager_name='$manager_name' and manager_password='$manager_password'";
$managerResult = $link->prepare($managerSql);
$managerResult->execute();
$managerRows = $managerResult->fetchall();

if ($managerRows[0]['logincount'] == 1) {
    // $check_err_code = 1;
    if ($_captcha && $session_id) {
        if ($session_id != $_SESSION['session_id']) {
            $check_err_code = 3;
        // $check_err_msg = '禁止不正常發送動作，即將返回首頁！';
        } else {
            require_once 'js/securimage/securimage.php';
            $securimage = new Securimage();
            if ($securimage->check($_captcha)) {
                $check_err_code = 1;
            } else {
                $check_err_code = 3;
            }
            unset($securimage);
        }
    }
} elseif ($managerRows[0]['logincount'] != 1) {
    $check_err_code = 2;
}
// pre($check_err_code);
// exit;
$status = !empty($manager_name) && !empty($check_err_code) ? 'success' : 'error';
$arr = array('status' => $status, 'check_err_code' => $check_err_code);
echo json_encode($arr);
