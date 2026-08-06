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

$filtered_cameras = $cameras_info;
unset($filtered_cameras['camera_list']);

$all_cameras = array_merge(...array_values($filtered_cameras));


?>


<br>
<h2>Доступные адреса</h2>
<?php foreach ($_SESSION["address"] as $addres_id => $addres_name): ?>
    <a href="<?= $document_root ?>/index.php?hid=<?= $addres_id; ?>"><?= $addres_name; ?></a>
<?php endforeach; ?>
<br><br>

<p>Текущий адрес: <?= $_SESSION["address"][$_SESSION["current_hid"]]; ?></p>

<label>
    <input type="radio" name="camera_type" data-filter="all" data-filter-target="camera_type" checked /> <span >Все</span>
</label>
<?php if(!empty($cameras_info["camera"])): ?>
    <label>
        <input type="radio" name="camera_type" data-filter="camera" data-filter-target="camera_type" /> <span>Камеры</span>
    </label>
<?php endif; ?>
<?php if(!empty($cameras_info["gate"])): ?>
    <label>
        <input type="radio" name="camera_type" data-filter="gate" data-filter-target="camera_type" /> <span>Шлагбаум</span>
    </label>
<?php endif; ?>
<?php if(!empty($cameras_info["intercom"])): ?>
    <label>
        <input type="radio" name="camera_type" data-filter="intercom" data-filter-target="camera_type" /> <span>Домофоны</span>
    </label>
<?php endif; ?>


<div data-filter-content="camera_type">
    <?php foreach($all_cameras as $key=> $value):?>
        <div data-filter-type="<?=$value['camera_type'];?>">
            <img src="<?=$value['screen'];?>" alt="" width="120" loading="lazy">
            <a href="<?=$document_root?>/dashboard/player.php?i_id=<?=$value['i_id']?>"><?=$value['camera_type'];?> <?=$value['name'];?>  </a>
        </div>
    <?php endforeach;?>
</div>

<script>
    const FILTER_TABS = document.querySelectorAll('input[type="radio"][data-filter]');

    FILTER_TABS.forEach(radio => {
        radio.addEventListener('change', () => {

            const FILTER_TARGET_NAME = radio.getAttribute('data-filter-target');
            const FILTER_VALUE = radio.getAttribute('data-filter');

            const FILTER_CONTENT = document.querySelector(`[data-filter-content="${FILTER_TARGET_NAME}"]`);
            const FILTER_LIST = FILTER_CONTENT.querySelectorAll('[data-filter-type]');

            if (FILTER_VALUE === 'all') {
                FILTER_LIST.forEach(item => {
                    item.style.display = 'flex';
                });
            } else {
                FILTER_LIST.forEach(item => {
                    if (item.getAttribute('data-filter-type') === FILTER_VALUE) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }


        });
    });

</script>




<!--<p>Домофоны</p><br>-->
<?php //foreach($cameras_info['intercom'] as $key=> $value):?>
<!--    <a href="--><?php //=$document_root?><!--/dashboard/player.php?i_id=--><?php //=$value['i_id']?><!--">--><?php //=$value['name'];?><!--  </a><br>-->
<?php //endforeach;?>
<!--<br>-->
<!--<p>Камеры</p><br>-->
<?php //foreach($cameras_info['camera'] as $key=> $value):?>
<!--    <a href="--><?php //=$document_root?><!--/dashboard/player.php?i_id=--><?php //=$value['i_id']?><!--">--><?php //=$value['name'];?><!--  </a><br>-->
<?php //endforeach;?>
<!--<br>-->
<!--<p>Доступы</p><br>-->
<?php //foreach($cameras_info['gate'] as $key=> $value):?>
<!--    <a href="--><?php //=$document_root?><!--/dashboard/player.php?i_id=--><?php //=$value['i_id']?><!--">--><?php //=$value['name'];?><!--  </a><br>-->
<?php //endforeach;?>
<!--<br>-->