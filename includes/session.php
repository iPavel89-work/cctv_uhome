<?php
date_default_timezone_set('Asia/Aqtau');
session_start([
    'cookie_httponly' => true, // Запретить доступ к cookie через JavaScript
    'cookie_samesite' => 'Strict', // Защита от CSRF
    'use_strict_mode' => true, // Строгий режим сессий
    'cookie_secure' => true
]);