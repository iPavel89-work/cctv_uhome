<?php
$ADMIN_PAGE = true;
$PAGE = 'index';
$PAGE_TITLE = 'Главная страница';
$PAGE_TITLE_KEY = 'page_index_title';
include "../includes/base.php";
include "../includes/data.php";
include "../includes/forpost.php";

//TODO добавить проверку прав на все действия
//TODO При добавлении и редактировании слать признак навсегда, если есть
//TODO Выводить в ошибке key а не текст


if(!isset($_POST['action'])){
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
//echo json_encode($permitions,JSON_UNESCAPED_UNICODE);
//echo "<br><br>";
//echo json_encode($_SESSION,JSON_UNESCAPED_UNICODE);

if($_POST['action']=='get_archive'){ // прыжок по архиву и таймлайну
    if($permitions['video_archive']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав']);
        exit;
    }
    $data['camera_id']=$_SESSION['current_camera']['camera_id'];
    $data['session_id']=$_SESSION['current_camera']['session_id'];
    $data['ts'] =  $_POST['ts'];
    $data['format']="HLS";
    $data['url']=$_SESSION['current_camera']['url'];
    $get_archive=fp_archive_url($data);
    if($get_archive['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Невозможно получить ссылку на архив'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    fp_close_translation($data);
    $_SESSION['current_camera']['url'] =  $get_archive['URL'];
    echo json_encode($get_archive,JSON_UNESCAPED_UNICODE);
    exit;


}
//Блок форпоста
elseif($_POST['action']=='get_screen'){ //получение скрина
    if($permitions['video_archive']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав']);
        exit;
    }
    $data['camera_id']=$_SESSION['current_camera']['camera_id'];
    $data['session_id']=$_SESSION['current_camera']['session_id'];
    $data['ts'] =  $_POST['ts'];
    $data['format']="JPG";
    $data['url']=$_SESSION['current_camera']['url'];
    $get_archive=fp_archive_url($data);
    if($get_archive['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Невозможно получить ссылку на архив']);
        exit;
    }
    fp_close_translation($data);
    $_SESSION['current_camera']['url'] =  $get_archive['URL'];
    echo json_encode($get_archive,JSON_UNESCAPED_UNICODE);
    echo "<br>";
    echo $get_archive['URL'];
    exit;
}
elseif($_POST['action']=='download_archive'){ //скачка архива
    if($permitions['video_download']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    $data['camera_id']=$_SESSION['current_camera']['camera_id'];
    $data['session_id']=$_SESSION['current_camera']['session_id'];
    $data['ts'] =  $_POST['ts'];
    $data['duration']="2";
    $data['url']=$_SESSION['current_camera']['url'];
    $get_archive=fp_download_url($data);
    if($get_archive['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Невозможно получить ссылку на архив'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    fp_close_translation($data);
    $_SESSION['current_camera']['url'] =  $get_archive['URL'];
    echo json_encode($get_archive,JSON_UNESCAPED_UNICODE);
    echo "<br>";
    echo $get_archive['URL'];
    exit;
}
//Конец блока форпоста
//Блок номеров
elseif($_POST['action']=='get_alrp'){
    if($permitions['lp_view']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав']);
        exit;
    }
    $data['i_id']=sanitize_text($_POST['i_id']);
    $get_alrp=get_alrp($data);
    if($get_alrp['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Невозможно получить список номеров'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo json_encode($get_alrp,JSON_UNESCAPED_UNICODE);
    exit;

}
elseif($_POST['action']=='add_alrp'){
    if($permitions['lp_add']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    $data['i_id']=$_SESSION['current_camera']['id'];
    $data['lp'] = sanitize_text($_POST['lp']);
    $data['customer_id'] = sanitize_text($_POST['customer_id']);
    $data['group'] = sanitize_text($_POST['group']);
    $data['date_to']=sanitize_text($_POST['date_to']);
    $data['date_from'] = sanitize_text($_POST['date_from']);
    $data['description'] = sanitize_text($_POST['description']);
    $data['camera_ip']=$_SESSION['current_camera']['camera_ip'];
    $data['camera_login'] = $_SESSION['current_camera']['camera_login'];
    $data['camera_password'] = $_SESSION['current_camera']['camera_password'];

    $add_alrp=add_alrp($data);
    if($add_alrp['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Не удалось добавить номер'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo json_encode($add_alrp,JSON_UNESCAPED_UNICODE);
    exit;

}
elseif($_POST['action']=='edit_alrp'){
    if($permitions['lp_edit']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    $data['i_id']=$_SESSION['current_camera']['id'];
    $data['lp'] = sanitize_text($_POST['lp']);
    $data['customer_id'] = sanitize_text($_POST['customer_id']);
    $data['group'] = sanitize_text($_POST['group']);
    $data['date_to']=sanitize_text($_POST['date_to']);
    $data['date_from'] = sanitize_text($_POST['date_from']);
    $data['description'] = sanitize_text($_POST['description']);
    $data['camera_ip']=$_SESSION['current_camera']['camera_ip'];
    $data['camera_login'] = $_SESSION['current_camera']['camera_login'];
    $data['camera_password'] = $_SESSION['current_camera']['camera_password'];

    $edit_alrp=edit_alrp($data);
    if($edit_alrp['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Не удалось изменить номер'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo json_encode($edit_alrp,JSON_UNESCAPED_UNICODE);
    exit;

}

elseif($_POST['action']=='remove_alrp'){
    if($permitions['lp_remove']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    $data['i_id']=$_SESSION['current_camera']['id'];
    $data['lp'] = sanitize_text($_POST['lp']);
    $data['camera_ip']=$_SESSION['current_camera']['camera_ip'];
    $data['camera_login'] = $_SESSION['current_camera']['camera_login'];
    $data['camera_password'] = $_SESSION['current_camera']['camera_password'];

    $remove_alrp=remove_alrp($data);
    if($edit_alrp['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Не удалось изменить номер'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo json_encode($remove_alrp,JSON_UNESCAPED_UNICODE);
    exit;

}

//Конец блока номеров

//Блок работы с клиентами и дверьми
elseif($_POST['action']=='open_door'){
    if($permitions['control_intercom']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    $data['i_id']=$_SESSION['current_camera']['id'];
    $data['camera_model']=$_SESSION['current_camera']['camera_model'];
    $data['camera_ip']=$_SESSION['current_camera']['camera_ip'];
    $data['camera_login'] = $_SESSION['current_camera']['camera_login'];
    $data['camera_password'] = $_SESSION['current_camera']['camera_password'];

    $open_door=open_door($data);
    if($open_door['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Не удалось открыть'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    $data['action']='maindoor';
    log_data($data);
    echo json_encode($open_door,JSON_UNESCAPED_UNICODE);
    exit;

}
//Блок работы с клиентами и дверьми
elseif($_POST['action']=='open_gate'){
    if($permitions['control_gate']!==1){
        echo json_encode(['result'=>'error','message'=>'Недостаточно прав'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    $data['i_id']=$_SESSION['current_camera']['id'];
    $data['camera_model']=$_SESSION['current_camera']['camera_model'];
    $data['camera_ip']=$_SESSION['current_camera']['camera_ip'];
    $data['camera_login'] = $_SESSION['current_camera']['camera_login'];
    $data['camera_password'] = $_SESSION['current_camera']['camera_password'];

    $open_gate=open_gate($data);
    if($open_gate['result']=='error'){
        echo json_encode(['result'=>'error','message'=>'Не удалось открыть'],JSON_UNESCAPED_UNICODE);
        exit;
    }
    $data['action']='gate';
    log_data($data);
    echo json_encode($open_gate,JSON_UNESCAPED_UNICODE);
    exit;

}

//Конец блока работы с клиентами и дверьми