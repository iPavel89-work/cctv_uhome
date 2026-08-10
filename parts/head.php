<?php

// Получаем код ошибки из GET-параметра
$ERROR_CODE = $_GET['notice'] ?? '';
$SHOW_TOAST = false;

// Если в ссылке есть ?notice= и внутри страницы есть это значение в $PAGE_ERRORS, то выставляем переменную для отображения ошибки в true;
if (!empty($ERROR_CODE) && isset($PAGE_ERRORS[$ERROR_CODE])) {
    $ERROR_NOTICE = $PAGE_ERRORS[$ERROR_CODE];
    $SHOW_TOAST = true;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= "uHome"; ?></title>

    <link rel="icon" type="image/png" href="<?= $document_root; ?>/assets/favicons/favicon-96x96.png" sizes="96x96"/>
    <link rel="icon" type="image/svg+xml" href="<?= $document_root; ?>/assets/favicons/favicon.svg"/>
    <link rel="shortcut icon" href="<?= $document_root; ?>/assets/favicons/favicon.ico"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $document_root; ?>/assets/favicons/apple-touch-icon.png"/>
    <meta name="apple-mobile-web-app-title" content="uHome"/>
    <link rel="manifest" href="<?= $document_root; ?>/assets/favicons/site.webmanifest"/>

    <link rel="stylesheet" href="<?= $document_root; ?>/assets/css/font.css">
    <link rel="stylesheet" href="<?= $document_root; ?>/assets/css/icons.css">
    <link rel="stylesheet" href="<?= $document_root; ?>/assets/css/grid.css">
    <link rel="stylesheet" href="<?= $document_root; ?>/assets/css/index.css">
    <link rel="stylesheet" href="<?= $document_root; ?>/assets/css/modal.css">
    <link rel="stylesheet" href="<?= $document_root; ?>/assets/css/captcha.css">
    <script src="<?= $document_root; ?>/assets/js/toast2.1.js" defer></script>
    <script src="<?= $document_root; ?>/assets/js/functions.js" defer></script>
    <script src="<?= $document_root; ?>/assets/js/modal.js" defer></script>
    <script src="<?= $document_root; ?>/assets/js/checkbox-filter.js" defer></script>
    <script src="<?= $document_root; ?>/assets/js/index.js" defer></script>
    <script src="<?= $document_root; ?>/assets/js/translations.js" defer></script>
    <script src="<?= $document_root; ?>/assets/js/translate.js" defer></script>

<!--    <link rel="prefetch" href="https://cdn.jsdelivr.net/npm/@cap.js/wasm@0.0.6/browser/cap_wasm.min.js" as="script">-->
<!--    <link rel="prefetch" href="https://cdn.jsdelivr.net/npm/@cap.js/wasm@0.0.6/browser/cap_wasm_bg.wasm" as="fetch">-->
</head>
<body>


<?php if ($SHOW_TOAST): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            showToast(translations[CURRENT_LANG_PAGE]['<?= $ERROR_NOTICE["message"]; ?>'], "<?= htmlspecialchars($ERROR_NOTICE['class']) ?>");
        });
    </script>
<?php endif; ?>


<div class="wrapper">