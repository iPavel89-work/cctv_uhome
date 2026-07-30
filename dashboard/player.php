<?php
$ADMIN_PAGE = true;
$PAGE = 'index';
$PAGE_TITLE = 'Главная страница';
$PAGE_TITLE_KEY = 'page_index_title';
include "../includes/base.php";
include "../includes/data.php";

if(!isset($_GET['i_id'])){
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
$i_id = sanitize_text($_GET['i_id']);
if(!in_array($i_id, $_SESSION['data']['camera_list'])){
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
if(array_key_exists($i_id, $_SESSION['data']['intercom'])){
    echo "intercom";
    echo json_encode($_SESSION['data']['intercom'][$i_id],JSON_UNESCAPED_UNICODE);
}
if(array_key_exists($i_id,$_SESSION['data']['camera'])){
    echo "camera";
    echo json_encode($_SESSION['data']['camera'][$i_id],JSON_UNESCAPED_UNICODE);
}
if(array_key_exists($i_id,$_SESSION['data']['gate'])){
    echo "gate";
    echo json_encode($_SESSION['data']['gate'][$i_id],JSON_UNESCAPED_UNICODE);
}
?>
<p>Домофоны</p><br>
<?php foreach($_SESSION['data']['intercom'] as $key=> $value):?>
    <a href="<?=$document_root?>/dashboard/player.php?i_id=<?=$value['i_id']?>"><?=$value['name'];?>  </a><br>
<?php endforeach;?>
<br>
<p>Камеры</p><br>
<?php foreach($_SESSION['data']['camera'] as $key=> $value):?>
    <a href="<?=$document_root?>/dashboard/player.php?i_id=<?=$value['i_id']?>"><?=$value['name'];?>  </a><br>
<?php endforeach;?>
<br>
<p>Доступы</p><br>
<?php foreach($_SESSION['data']['gate'] as $key=> $value):?>
    <a href="<?=$document_root?>/dashboard/player.php?i_id=<?=$value['i_id']?>"><?=$value['name'];?>  </a><br>
<?php endforeach;?>
<br>