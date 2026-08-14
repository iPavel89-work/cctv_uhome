<?php
    $PAGE = 'auth';
    $PAGE_TITLE = 'Авторизация';
    $PAGE_TITLE_KEY = 'page_auth_title';
    include "../includes/base.php";
    include $BASE_PATH . "/parts/head.php";
?>


<div class="layout">
    <div class="layout_menu">
        <?php include $BASE_PATH . "/parts/sidebars/menu-left.php"; ?>
    </div>
    <div class="layout_inner">
        <form class="card card-login" action="login_control.php" method="POST">
            <div class="card_header">
                <div class="card_img">
                    <img src="https://hotspot.uhome.kz/assets/svg/uhome-mini.svg" alt="Logo">
                </div>
                <h1 class="card_title" data-translate="page_auth_card_title"></h1>
                <p class="card_desс text-small text-light" data-translate="page_auth_card_desc"></p>
            </div>
            <div class="card_inner">
                <div class="form_line">
                    <div class="input">
                        <p class="input_title" data-translate="auth_form_input_login_title"></p>
                        <div class="input_inner">
                            <input type="text" class="input_field" name="login" required>
                        </div>
                    </div>
                </div>

                <div class="form_line">
                    <div class="input">
                        <p class="input_title" data-translate="auth_form_input_password_title"></p>
                        <div class="input_inner">
                            <input type="text" class="input_field" name="password" required>
                        </div>
                    </div>
                </div>

                <div class="form_line">
                    <cap-widget id="cap" data-cap-api-endpoint="https://captcha.uhome.kz/21e58db659/" data-cap-i18n-verifying-label="Подождите..." data-cap-i18n-initial-state="Я не робот" data-cap-i18n-solved-label="Проверка пройдена" data-cap-i18n-error-label="Ошибка" data-cap-i18n-wasm-disabled="Enable WASM for significantly faster solving"></cap-widget>
                </div>


            </div>
            <div class="card_actions">
                <button type="submit" class="btn btn-accent btn-full btn-xl" data-translate="auth_form_button_submit"></button>
            </div>
        </form>
    </div>
    <div class="layout_menu">
        <?php include $BASE_PATH . "/parts/sidebars/menu-right.php"; ?>
    </div>
</div>

<script src="https://captcha.uhome.kz/assets/widget.js"></script>

<?php
include $BASE_PATH . "/parts/footer.php";
?>