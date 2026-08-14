<?php
$ADMIN_PAGE = true;
$PAGE = 'dashboard_placeholder';
$PAGE_TITLE = 'Нет доступных камер';
$PAGE_TITLE_KEY = 'page_placeholder_title';
include "../includes/base.php";
include "../includes/data.php";

include $BASE_PATH . "/parts/head.php";

if(!empty($_SESSION['house_list']) ){
    header('Location: ' . $document_root . '/dashboard/index.php');
    exit;
}

?>

<div class="layout">
    <div class="layout_menu">
        <div class="layout_menu">
            <?php include $BASE_PATH . "/parts/sidebars/menu-left.php"; ?>
        </div>
    </div>
    <div class="layout_inner">
        <div class="layout_content">
            <div class="section section-base section-placeholder section-list-empty">
                <div class="s-placeholder_icon">
                    <i class="bi bi-camera-video"></i>
                </div>

                <h1 class="section_title">
                    Упс, что-то пошло не так
                </h1>

                <div class="section_desc">
                    <p class="text-small text-light">У вас нет доступных устройств</p>
                </div>

            </div>
        </div>
    </div>
    <div class="layout_menu">
        <div class="layout_menu">
            <?php include $BASE_PATH . "/parts/sidebars/menu-right.php"; ?>
        </div>
    </div>
</div>

<?php
include $BASE_PATH . "/parts/footer.php";
?>
