

<?php
    if(isset($_SESSION["address"])) {
        include $BASE_PATH . '/parts/modals/addresses.php';
    }

    include $BASE_PATH . '/parts/modals/languages.php';
    include $BASE_PATH . '/parts/modals/user_profile.php';
?>

</div>
</body>
</html>