<?php
$ADMIN_PAGE = true;
$PAGE = 'dashboard_addresses';
$PAGE_TITLE = 'Выберите адрес';
$PAGE_TITLE_KEY = 'page_addresses_title';
include "../includes/base.php";
include "../includes/data.php";

include $BASE_PATH . "/parts/head.php";
?>

<script>
    window.addEventListener('DOMContentLoaded', () => {
       MODAL.open("addresses");
    });
</script>

<div class="layout">
    <div class="layout_menu">
        <div class="layout_menu">
            <?php include $BASE_PATH . "/parts/sidebars/menu-left.php"; ?>
        </div>
    </div>
    <div class="layout_inner">
        <div class="layout_content">
            <div class="section section-base section-placeholder">
                <div class="section_inner">
                    <h1 class="section_title isHover" data-modal-btn="addresses">
                        <i class="bi bi-map me-3"></i>
                        <span>Выберите адрес</span>
                        <i class="bi bi-chevron-down"></i>
                    </h1>
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
