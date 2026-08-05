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
    echo json_encode($camera_data,JSON_UNESCAPED_UNICODE);
    echo "<br><br>";
}
if(array_key_exists($i_id,$_SESSION['data']['camera'])){
    $_SESSION['current_camera']['id']=$i_id;
    $_SESSION['current_camera']['type']='camera';
    $camera_data=$_SESSION['data']['camera'][$i_id];
    $camera_type = "camera";
    echo json_encode($camera_data,JSON_UNESCAPED_UNICODE);
    echo "<br><br>";
}
if(array_key_exists($i_id,$_SESSION['data']['gate'])){
    $_SESSION['current_camera']['id']=$i_id;
    $_SESSION['current_camera']['type']='gate';
    $camera_data=$_SESSION['data']['gate'][$i_id];
    $camera_type = "gate";
    echo json_encode($camera_data,JSON_UNESCAPED_UNICODE);
    echo "<br><br>";
}
$fp_auth = fp_auth($camera_data['fp_login'],$camera_data['fp_pass']);
if($fp_auth['result']=='error'){
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
$fp_session_id = $fp_auth['SessionID']; //id сессии форпоста
$fp_cameraID = $camera_data['cameraID'];
$_SESSION['current_camera']['session_id']=$fp_session_id;
$_SESSION['current_camera']['camera_id']=$camera_data['cameraID'];
$_SESSION['current_camera']['camera_ip']=$camera_data['camera_ip'];
$_SESSION['current_camera']['camera_login']=$camera_data['camera_login'];
$_SESSION['current_camera']['camera_password']=$camera_data['camera_password'];
$_SESSION['current_camera']['camera_model']=$camera_data['camera_model'];


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

//echo json_encode($last_event,JSON_UNESCAPED_UNICODE);
//echo "<br><br>";
//echo json_encode($all_events,JSON_UNESCAPED_UNICODE);
//echo "<br><br>";
if($fp_events['result']=='error'){
    echo "Ошибка получения событий";
    exit;
}
//echo json_encode($fp_events,JSON_UNESCAPED_UNICODE);


$get_translation_url = fp_online_url($data);
if($get_translation_url['result']=='error'){
    echo "Ошибка получения потока";
}
$translation_url = $get_translation_url['URL'];
$_SESSION['current_camera']['url']=$translation_url;
echo "<br>";
//echo $url." ".$camera_data['name'];

echo json_encode($_SESSION['current_camera'],JSON_UNESCAPED_UNICODE);

include "../parts/head.php";
echo "<br>";
echo json_encode(get_alrp(["i_id"=>$i_id]),JSON_UNESCAPED_UNICODE);
echo "<br>";
?>
<?php
foreach($_SESSION['address'] as $key=>$value):
    ?>
    <a href="../index.php?hid=<?=$key;?>"><?=$value;?></a>
<?php
endforeach;
?>
<form action="api.php" method="POST">
    <select name="action" id="">
        <option value="add_alrp">Добавить</option>
        <option value="edit_alrp">Редактировать</option>
        <option value="remove_alrp">Удалить</option>
        <option value="open_door">Открыть дверь</option>
        <option value="open_gate">Открыть ворота</option>
    </select>
    <input type="hidden" name="i_id" value="<?= $i_id; ?>">
    <!--    <input type="hidden" name="ts" value="2026-07-30 08:00:00">-->
    <input type="submit" value="открыть дверь">

</form>
