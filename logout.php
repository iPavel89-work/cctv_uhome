<?php
include_once "includes/base.php";
session_destroy();
// Перенаправление на главную страницу
header('Location: login/index.php');
exit;
