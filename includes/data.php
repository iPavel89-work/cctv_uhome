<?php

if (!isset($_SESSION['auth_state']) || $_SESSION['auth_state'] !== true) {
    header('Location: ' . $document_root . '/login/index.php?notice=auth');
    exit;
}
//Блок прав пользователя
$permitions = get_permitions($_SESSION['user_id']);
$check_version = get_session_version($_SESSION['user_id']);
if(!isset($_SESSION['house_list']) || empty($_SESSION['house_list']) ){
    header('Location: ' . $document_root . '/dashboard/placeholder.php');
    exit;
}
//unset
unset ($_SESSION['current_camera']);

//end unset
$session_version = $_SESSION['session_version'];
if ($session_version !== $check_version['session']) {
    session_destroy();
// Перенаправление на главную страницу
    header('Location: ' . $document_root . '/login/index.php?notice=auth');
    exit;
}

