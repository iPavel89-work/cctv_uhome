<div class="modal-overlay" data-modal="lp_list">
    <div class="modal modal-side modal-right">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title">Список ТС</h2>

                <div class="modal_close" data-modal-close="lp_list">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_section">
                <button class="btn btn-link" type="button" data-modal-btn="lp_add">
                    <i class="bi bi-plus-lg"></i>
                    <span>Добавить ТС</span>
                </button>
            </div>
            <div class="modal_separator"></div>
            <div class="modal_content">
                <?php if(!empty($get_alrp)): ?>

                    <?php if(count($get_alrp) > 1): ?>
                        <label>
                            <input type="radio" name="lp_types" data-filter="all" data-filter-target="lp_types" checked /> <span >Все</span>
                        </label>
                        <?php foreach($lp_types as $value): ?>
                            <label>
                                <input type="radio" name="lp_types" data-filter="<?= $value; ?>" data-filter-target="lp_types" /> <span><?= $value; ?></span>
                            </label>
                        <?php endforeach; ?>

                        <br><br>
                    <?php endif; ?>

                    <div data-filter-content="lp_types">
                        <?php foreach($get_alrp as $key=>$value): ?>
                            <div data-filter-type="<?= $value["alrp_group"]; ?>">
                                <p><?= htmlspecialchars($value["alrp"]) ?></p>

                                <?php if($value["alrp_group"] === 1): ?>
                                    Временный
                                <?php endif;?>

                                <form action="api.php" method="post">
                                    <input type="hidden" name="action" value="remove_alrp">
                                    <input type="hidden" name="lp" value="<?= htmlspecialchars($value["alrp"]) ?>">
                                    <button type="submit">Удалить</button>
                                </form>
                                <br>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <br><br>

                <?php else: ?>
                    <p>Нет номеров</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
