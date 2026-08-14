<?php
$ADMIN_PAGE = true;
$PAGE = 'dashboard';
$PAGE_TITLE = 'Стартовая страница';
$PAGE_TITLE_KEY = 'page_dashboard_title';
include "includes/base.php";
include "includes/data.php";


if(isset($_GET['hid']) && !empty($_GET['hid'])){ //Проверка, передан ли Гет параметр
    $house_id = sanitize_text($_GET['hid']);
    unset($_SESSION['current_hid']);
    $_SESSION['current_hid'] = $house_id;

}
if(isset($_SESSION['current_hid'])){ //проверка, есть ли ид в сессии
    $house_id = $_SESSION['current_hid'];
    if(!in_array($house_id,$_SESSION['house_list'])){ //проверяем есть ли дом в списке разрешенных
        unset($_SESSION['current_hid']);
        header('Location: ' . $document_root . '/index.php?notice=incorrect');
        exit;
    }
    header('Location: ' . $document_root . '/dashboard/index.php');  //редирект на главную
    exit;
}
$len = count($_SESSION['house_list']);
if($len ==1){
    $house_id = $_SESSION['house_list'][0];
    $_SESSION['current_hid'] = $house_id;
    header('Location: ' . $document_root . '/dashboard/index.php'); //редирект на главную
    exit;
}
if($len>1){
    header('Location: ' . $document_root . '/dashboard/address.php'); // редирект на список адресов
    exit;
}




