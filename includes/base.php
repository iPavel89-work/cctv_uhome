<?php
include_once 'session.php'; // Сессии
include_once 'functions.php'; // Функции


$DEV_MODE = false; // Режим разработки
$theme = 'uhome';
$select_connection_time = array(15,30,45,60,120,180); //лимит сессии
$select_speed_limit = array(10,0); //скорость соединения
$select_reconnect = array(0, 1);
$role_dictionary = [
    "0" => "role_admin",
    "1" => "role_director",
    "2" => "role_director_readonly",
    "3" => "role_manager",
    "4" => "role_accountant",
    "5" => "role_user",
    "6" => "role_sale",
];


$profiles = array("guest","internet","staff","unlimited");

if($DEV_MODE){
    $document_root = "http://" . $_SERVER['SERVER_NAME'] . "/cctv_uhome"; // Полный путь до коневой папки
    $BASE_PATH = $_SERVER["DOCUMENT_ROOT"] . '/cctv_uhome'; // Использовать только для include или include_once
}
else{
    // Полный путь до коневой папки
    $document_root = "https://" . $_SERVER['SERVER_NAME'];
    $BASE_PATH = $_SERVER["DOCUMENT_ROOT"] ;
}
