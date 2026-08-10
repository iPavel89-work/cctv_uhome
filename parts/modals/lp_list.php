<div class="modal-overlay" data-modal="lp_list">
    <div class="modal modal-side modal-right">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title">Список ТС</h2>
                <p class="modal_desc">Номера транспортных средств, имеющих доступ к автоматическому открытию шлагбаума</p>

                <div class="modal_close" data-modal-close="lp_list">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_section pb-3">
                <button class="btn btn-text btn-accent" type="button" data-modal-btn="lp_add">
                    <i class="bi bi-plus-lg"></i>
                    <span>Добавить ТС</span>
                </button>
            </div>
            <div class="modal_separator"></div>
            <?php if (count($get_alrp) > 1): ?>
                <div class="modal_section pt-3">
                    <div class="checkboxes-horizontal">
                        <label class="checkbox checkbox-pill">
                            <input type="radio" name="lp_types" class="checkbox_input" data-filter="all" data-filter-target="lp_types"
                                   checked/>
                            <span class="checkbox_text">Все</span>
                        </label>
                        <?php foreach ($lp_types as $value): ?>
                            <label class="checkbox checkbox-pill">
                                <input type="radio" name="lp_types" class="checkbox_input" data-filter="<?= $value; ?>"
                                       data-filter-target="lp_types"/>
                                <span class="checkbox_text"><?= $value == 2 ? 'Постоянные' : 'Временные'; ?> </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="modal_content">

                <?php if (empty($get_alrp)): ?>
                    <p class="text-small text-light">Нет номеров</p>
                <?php endif; ?>

                <?php if (!empty($get_alrp)): ?>

                    <div class="lp-list" data-filter-content="lp_types">
                        <?php foreach ($get_alrp as $key => $value): ?>
                            <div class="lp" data-filter-type="<?= $value["alrp_group"]; ?>">
                                <p class="lp_number">
                                    <?= htmlspecialchars($value["alrp"]); ?>
                                </p>
                                <p class="lp_customer text-small">
                                    <?= htmlspecialchars($value["description"]); ?>
                                </p>
                                <?php if ($value["alrp_group"] === 1): ?>
                                    <p class="text-small text-danger" title="Временный список. Доступен с <?= htmlspecialchars($value["date_from"]); ?> по <?= htmlspecialchars($value["date_to"]); ?>">
                                        <i class="bi bi-clock"></i>
                                    </p>
                                <?php endif; ?>
                                <div class="lp_actions">
                                    <p class="text-accent" data-modal-btn="lp_edit" data-js-lp-edit-number="<?= htmlspecialchars($value["alrp"]); ?>">Изменить</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-modal-btn="lp_edit"]').forEach(button => {
            button.addEventListener('click', (event) => {
                const LPNUMBER = event.currentTarget.dataset.jsLpEditNumber;
                const LPDATA = ALRP_DATA[LPNUMBER];

                clearInputsLpEdits();
                if(LPDATA) {
                    const modal = document.querySelector('[data-modal="lp_edit"]');

                    if(!modal) {
                        console.log('Не найдено модальное окно');
                        return;
                    }

                    modal.querySelector('input[name="lp"]').value = LPDATA.alrp;
                    modal.querySelector('input[name="description"]').value = LPDATA.description;
                    modal.querySelector('select[name="customer_id"]').value = LPDATA.customer_id;
                    modal.querySelector(`input[name="group"][value="${LPDATA.alrp_group}"]`).checked = true;

                    if(LPDATA.alrp_group == 1) {
                        modal.querySelectorAll('[data-js-alrp-group-date]').forEach(el => el.classList.remove('d-none'));
                        modal.querySelector('input[name="date_from"]').value = LPDATA.date_from;
                        modal.querySelector('input[name="date_to"]').value = LPDATA.date_to;
                    } else {
                        modal.querySelectorAll('[data-js-alrp-group-date]').forEach(el => el.classList.add('d-none'));
                    }

                }
            })
        })

        function clearInputsLpEdits() {
            const modal = document.querySelector('[data-modal="lp_edit"]');
            modal.querySelector('input[name="lp"]').value = '';
            modal.querySelector('input[name="description"]').value = '';
            modal.querySelector('select[name="customer_id"]').value = '';
            modal.querySelectorAll('[data-js-alrp-group-date]').forEach(el => el.classList.add('d-none'));
            modal.querySelector('input[name="date_from"]').value = '<?= date('Y-m-d\TH:i'); ?>';
            modal.querySelector('input[name="date_to"]').value = '<?= date('Y-m-d\TH:i', strtotime('+1 day') ); ?>';
        }
    })
</script>
