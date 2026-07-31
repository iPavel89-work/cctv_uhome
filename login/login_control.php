<?php
include_once "../includes/base.php";
//if(!isset($_POST['login']) || !isset($_POST['password']) || !isset($_POST['cap-token'])) {
//    header('Location: index.php?notice=incorrect');
//    exit;
//}
if(empty($_POST['login']) || empty($_POST['password']) || empty($_POST['cap-token'])) {
    header('Location: index.php?notice=empty');
    exit;
}
//Блок проверки токена каптчи
//$cap_token = $_POST['cap-token'];
//$check_captcha = check_captcha($cap_token);
//if(isset($check_captcha['error']) ){
//    header("location: index.php?notice=captcha");
//    exit;
//}
$login = sanitize_text($_POST['login']);
$passwd = $_POST['password'];

$auth = auth($login, $passwd);
$data['login']=$login;
$data['ip']=$_SERVER['REMOTE_ADDR'];
$data['user_agent']=get_ua($_SERVER['HTTP_USER_AGENT']);
if($auth===false){
    $data['status']='fail';
    header('Location: index.php?notice=error');
    exit;
}
// Регенерация сессии после авторизации
session_regenerate_id(true);
$_SESSION['auth_state'] = true;
$_SESSION['user_login'] = $login;
$_SESSION['user_id'] = $auth['id'];
$_SESSION['user_role'] = $auth['role'];
$_SESSION['fullname'] = $auth['fullname'];
$_SESSION['house_list'] = $auth['house_list'];
$_SESSION['active'] = $auth['active'];
$_SESSION['session_version'] = $auth['session'];  //чтобы убивать сессии при смене пароля или блокировке
//
if ($_SESSION['auth_state']) {
    $data['status']='success';
    header('Location: ../index.php');
    exit;
}
//echo json_encode($_SESSION, JSON_UNESCAPED_UNICODE);
//exit;