<div class="modal-overlay" data-modal="user_profile">
    <div class="modal modal-side modal-right">
        <div class="modal_inner">
            <div class="modal_header">
                <?php
                    $user_role_dict = [
                        "0" => "Admin",
                    ];
                ?>
                <h2 class="modal_title" ><?= $_SESSION['user_login'] ?></h2>
                <p class="modal_desc"><?= $user_role_dict[$_SESSION['user_role']]; ?></p>

                <div class="modal_close" data-modal-close="user_profile">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_separator"></div>
            <div class="modal_content">


                <div class="items">
                    <div class="item item-hover" data-modal-btn="addresses">
                        <div class="item_icon">
                            <i class="bi bi-geo"></i>
                        </div>
                        <div class="item_content">
                            <?php if(isset($_SESSION["address"][$_SESSION["current_hid"]])): ?>
                                <p class="item_title"><?= $_SESSION["address"][$_SESSION["current_hid"]]; ?></p>
                            <?php else: ?>
                                <p class="item_title">Выберите адрес</p>
                            <?php endif; ?>
                            <divp class="item_desc">
                                <p class="text-light text-small">Нажмите, чтобы изменить адрес</p>
                            </divp>
                        </div>
                    </div>
                </div>


                <div class="items mt-auto">
                    <div class="item item-hover">
                        <div class="item_icon">
                            <i class="bi bi-moon-stars"></i>
                        </div>
                        <div class="item_content">
                            <p class="item_title">Тёмный режим</p>
                            <divp class="item_desc">
                                <p class="text-light text-small">Изменить цветовую тему</p>
                            </divp>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="modal_actions">
            <a href="<?= $document_root; ?>/logout.php" class="btn btn-modal btn-full btn-danger" >Выйти из аккаунта</a>
        </div>
    </div>
</div>
