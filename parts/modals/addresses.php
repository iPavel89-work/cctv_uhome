<div class="modal-overlay" data-modal="addresses">
    <div class="modal modal-side modal-right">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title" data-translate="modal_address_title"></h2>
                <div class="modal_close" data-modal-close="addresses">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_separator"></div>
            <div class="modal_content">
                <div class="items">
                <?php foreach ($_SESSION["address"] as $addres_id => $addres_name): ?>
                    <a href="<?= $document_root ?>/index.php?hid=<?= $addres_id; ?>" class="item item-hover">
                        <div class="item_icon">
                            <i class="bi bi-geo"></i>
                        </div>
                        <div class="item_content">
                            <p class="item_title" title="<?= $addres_name; ?>"><?= $addres_name; ?></p>
                            <div class="item_desc"  class="item_desc">
                                <p class="text-light text-small">Тут полный адрес</p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</div>
