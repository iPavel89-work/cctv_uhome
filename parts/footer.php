
<?php

    include $BASE_PATH . '/parts/modals/languages.php';

    if($SHOW_ADDRESSES_ELEMENTS) {
        include $BASE_PATH . '/parts/modals/addresses.php';
    }

    if (isset($_SESSION['auth_state']) || $_SESSION['auth_state'] === true) {
        include $BASE_PATH . '/parts/modals/user_profile.php';
    }

?>

</div>
</body>
</html>