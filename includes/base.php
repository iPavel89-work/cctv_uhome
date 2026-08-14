<?php
include_once 'session.php'; // Сессии
include_once 'functions.php'; // Функции

$DEV_MODE = false; // Режим разработки
$theme = 'uhome';
$captcha_url = 'https://captcha.uhome.kz/21e58db659/';

if($DEV_MODE){
    $document_root = "http://" . $_SERVER['SERVER_NAME'] . "/cctv_uhome"; // Полный путь до коневой папки
    $BASE_PATH = $_SERVER["DOCUMENT_ROOT"] . '/cctv_uhome'; // Использовать только для include или include_once
}
else{
    // Полный путь до коневой папки
    $document_root = "https://" . $_SERVER['SERVER_NAME'];
    $BASE_PATH = $_SERVER["DOCUMENT_ROOT"] ;
}
