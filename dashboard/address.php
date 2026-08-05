<?php
$ADMIN_PAGE = true;
$PAGE = 'index';
$PAGE_TITLE = 'Главная страница';
$PAGE_TITLE_KEY = 'page_index_title';
include "../includes/base.php";
include "../includes/data.php";

foreach($_SESSION['address'] as $key=>$value):
    ?>
    <a href="../index.php?hid=<?=$key;?>"><?=$value;?></a>
<?php
endforeach;
?>