<?php
$ADMIN_PAGE = true;
$PAGE = 'index';
$PAGE_TITLE = 'Главная страница';
$PAGE_TITLE_KEY = 'page_index_title';
include "../includes/base.php";
include "../includes/data.php";
include "../includes/forpost.php";

if(!isset($_GET['i_id'])){
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
$date = date('Y-m-d');
if(isset($_GET['date'])){
    $date = sanitize_text($_GET['date']);
}

$i_id = sanitize_text($_GET['i_id']);
if(!in_array($i_id, $_SESSION['data']['camera_list'])){

    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}

if(array_key_exists($i_id, $_SESSION['data']['intercom'])){
    $_SESSION['current_camera']['id']=$i_id;
    $_SESSION['current_camera']['type']='intercom';
    $camera_data=$_SESSION['data']['intercom'][$i_id];
    $camera_type = "intercom";
    echo json_encode($_SESSION['data']['intercom'][$i_id],JSON_UNESCAPED_UNICODE);
    echo "<br><br>";
}
if(array_key_exists($i_id,$_SESSION['data']['camera'])){
    $_SESSION['current_camera']['id']=$i_id;
    $_SESSION['current_camera']['type']='camera';
    $camera_data=$_SESSION['data']['camera'][$i_id];
    $camera_type = "camera";
    echo json_encode($_SESSION['data']['camera'][$i_id],JSON_UNESCAPED_UNICODE);
    echo "<br><br>";
}
if(array_key_exists($i_id,$_SESSION['data']['gate'])){
    $_SESSION['current_camera']['id']=$i_id;
    $_SESSION['current_camera']['type']='gate';
    $camera_data=$_SESSION['data']['gate'][$i_id];
    $camera_type = "gate";
    echo json_encode($_SESSION['data']['gate'][$i_id],JSON_UNESCAPED_UNICODE);
    echo "<br><br>";
}
$fp_auth = fp_auth($camera_data['fp_login'],$camera_data['fp_pass']);
if($fp_auth['result']=='error'){
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
$fp_session_id = $fp_auth['SessionID']; //id сессии форпоста
$fp_cameraID = $camera_data['cameraID'];
$data['session_id']=$fp_session_id;
$data['camera_id']=$fp_cameraID;
$data['upper_date'] = $date." 23:59:59";
$data['lower_date'] = $date." 00:00:00";
$data['date']=$date;
$data['i_id']=$i_id;
$fp_events =  fp_get_events($data);
$intercom_events = intercom_get_events($data);
$all_events = array_merge($intercom_events,$fp_events); //все события

$last_event = array_slice($all_events,0,5); //5 последних событий
$times = array_column($all_events, 'Time'); // работает и с объектами, и с массивами
array_multisort($times, SORT_DESC, $all_events);

echo json_encode($last_event,JSON_UNESCAPED_UNICODE);
echo "<br><br>";
echo json_encode($all_events,JSON_UNESCAPED_UNICODE);
echo "<br><br>";
if($fp_events['result']=='error'){
    echo "Ошибка получения событий";
    exit;
}
//echo json_encode($fp_events,JSON_UNESCAPED_UNICODE);


$get_translation_url = fp_online_url($data);
if($get_translation_url['result']=='error'){
    echo "Ошибка получения потока";
}
$url = $get_translation_url['URL'];
echo $url." ".$camera_data['name'];


?>
