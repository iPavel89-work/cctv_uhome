<?php
$ADMIN_PAGE = true;
$PAGE = 'index';
$PAGE_TITLE = 'Главная страница';
$PAGE_TITLE_KEY = 'page_index_title';
include "../includes/base.php";
include "../includes/data.php";

if(!isset($_SESSION['current_hid'])){ //проверка, существует ли текущий адрес
    header('Location: ' . $document_root . '/index.php');
    exit;
}
$current_hid = $_SESSION['current_hid'];
//echo json_encode(cameras_info($current_hid),JSON_UNESCAPED_UNICODE);
$cameras_info = cameras_info($current_hid);
$_SESSION['data']=$cameras_info;
?>
<p>Домофоны</p><br>
<?php foreach($cameras_info['intercom'] as $key=> $value):?>
    <a href="<?=$document_root?>/dashboard/player.php?i_id=<?=$value['i_id']?>"><?=$value['name'];?>  </a><br>
<?php endforeach;?>
<br>
<p>Камеры</p><br>
<?php foreach($cameras_info['camera'] as $key=> $value):?>
    <a href="<?=$document_root?>/dashboard/player.php?i_id=<?=$value['i_id']?>"><?=$value['name'];?>  </a><br>
<?php endforeach;?>
<br>
<p>Доступы</p><br>
<?php foreach($cameras_info['gate'] as $key=> $value):?>
    <a href="<?=$document_root?>/dashboard/player.php?i_id=<?=$value['i_id']?>"><?=$value['name'];?>  </a><br>
<?php endforeach;?>
<br>