<?php
$ADMIN_PAGE = true;
$PAGE = 'dashboard_addresses';
$PAGE_TITLE = 'Адрес';
$PAGE_TITLE_KEY = 'page_addresses_title';
include "../includes/base.php";
include "../includes/data.php";

include $BASE_PATH . "/parts/head.php";

if($SHOW_ADDRESSES_ELEMENTS === false) {
    header('Location: ' . $document_root . '/dashboard/index.php');
    exit;
}

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
            <div class="section section-base section-placeholder section-addresses">
                    <div class="s-placeholder_icon">
                        <i class="bi bi-geo"></i>
                    </div>

                    <h1 class="section_title" data-translate="page_addressplaceholder_title">

                    </h1>

                    <div class="section_desc">
                        <p class="text-small text-light" data-translate="page_addressplaceholder_desc">
                         </p>
                    </div>

                    <div class="s-placeholder_link">
                        <button class="btn btn-accent btn-text" type="submit" data-modal-btn="addresses">
                            <i class="bi bi-cursor"></i>
                            <span data-translate="page_addressplaceholder_button"></span>
                        </button>
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
