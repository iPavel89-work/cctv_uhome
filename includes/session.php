<?php
date_default_timezone_set('Asia/Aqtau');

session_start([
//    'cookie_lifetime' => 86400, // cookie живет 24 часа
//    'gc_maxlifetime' => 86400, // данные сессии хранятся 24 часа
    'cookie_httponly' => true, // Запретить доступ к cookie через JavaScript
    'cookie_samesite' => 'Strict', // Защита от CSRF
    'use_strict_mode' => true, // Строгий режим сессий
    'cookie_secure' => true
]);