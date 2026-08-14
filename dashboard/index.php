<?php
$ADMIN_PAGE = true;
$PAGE = 'dashboard';
$PAGE_TITLE = 'Админ-панель';
$PAGE_TITLE_KEY = 'page_dashboard_title';
include "../includes/base.php";
include "../includes/dict.php";
include "../includes/data.php";

if (!isset($_SESSION['current_hid'])) { // проверка, существует ли текущий адрес
    header('Location: ' . $document_root . '/index.php');
    exit;
}

if(!isset($_SESSION['house_list']) || empty($_SESSION['house_list']) ){
    header('Location: ' . $document_root . '/dashboard/placeholder.php');
    exit;
}
$current_hid = $_SESSION['current_hid'];

$cameras_info = cameras_info($current_hid);
$_SESSION['data'] = $cameras_info;

$filtered_cameras = $cameras_info;
unset($filtered_cameras['camera_list']);

$all_cameras = array_merge(...array_values($filtered_cameras));

include $BASE_PATH . "/parts/head.php";




?>

    <div class="layout">
        <div class="layout_menu">
            <div class="layout_menu">
                <?php include $BASE_PATH . "/parts/sidebars/menu-left.php"; ?>
            </div>
        </div>
        <div class="layout_inner">
            <div class="layout_content">
                <div class="section section-base">
                    <div class="section_inner">

                        <?php if ($SHOW_ADDRESSES_ELEMENTS): ?>
                            <h1 class="section_title" >
                                <i class="bi bi-geo"></i>
                                <span><?= $_SESSION["address"][$_SESSION["current_hid"]]; ?></span>
                                <button type="button" class="btn btn-danger btn-text ms-auto" data-modal-btn="addresses">
                                    <i class="bi bi-pen"></i>
                                    <span class="hide-mobile" data-translate="word_edit_address"></span>
                                </button>
                            </h1>
                        <?php else: ?>
                            <h1 class="section_title" >
                                <i class="bi bi-geo"></i>
                                <span><?= $_SESSION["address"][$_SESSION["current_hid"]]; ?></span>
                            </h1>
                        <?php endif; ?>

                    </div>

                    <div class="section_separator"></div>

                    <div class="section_actions">
                        <div class="checkboxes-horizontal">
                            <label class="checkbox checkbox-text">
                                <input type="radio" name="camera_type" class="checkbox_input" data-filter="all"
                                       data-filter-target="camera_type" tabindex="-1" checked>
                                <span class="checkbox_text">
                                    <i class="bi bi-card-list"></i>
                                    <span data-translate="word_all" class="hide-mobile"></span>
                                </span>
                            </label>

                            <?php if (!empty($cameras_info["camera"])): ?>
                                <label class="checkbox checkbox-text">
                                    <input type="radio" name="camera_type" class="checkbox_input" data-filter="camera"
                                           data-filter-target="camera_type" tabindex="-1">
                                    <span class="checkbox_text">
                                        <i class="bi bi-camera-video"></i>
                                        <span data-translate="word_cameras" class="hide-mobile"></span>
                                    </span>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($cameras_info["gate"])): ?>
                                <label class="checkbox checkbox-text">
                                    <input type="radio" name="camera_type" class="checkbox_input" data-filter="gate"
                                           data-filter-target="camera_type" tabindex="-1">
                                    <span class="checkbox_text">
                                        <i class="bi bi-door-open"></i>
                                        <span data-translate="word_gates" class="hide-mobile"></span>
                                    </span>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($cameras_info["intercom"])): ?>
                                <label class="checkbox checkbox-text">
                                    <input type="radio" name="camera_type" class="checkbox_input" data-filter="intercom"
                                           data-filter-target="camera_type" tabindex="-1">
                                    <span class="checkbox_text">
                                        <i class="bi bi-phone-landscape"></i>
                                        <span data-translate="word_intercoms" class="hide-mobile"></span>
                                    </span>
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="camgrid" data-filter-content="camera_type">
                    <?php foreach ($all_cameras as $key => $value): ?>
                        <a
                                href="<?= $document_root ?>/dashboard/player.php?i_id=<?= $value['i_id'] ?>"
                                class="camgrid_item"
                                data-filter-type="<?= $value['camera_type']; ?>"
                                title="<?= $value['name']; ?>"
                        >
                            <div class="camgrid_preview">
                                <img src="<?= $value['screen']; ?>" alt="" loading="lazy">
                            </div>
                            <p class="camgrid_title">
                                <i class="bi <?= $cam_type_dict[$value['camera_type']]; ?> me-2"></i>
                                <span><?= $value['name']; ?></span>
                            </p>
                        </a>
                    <?php endforeach; ?>
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