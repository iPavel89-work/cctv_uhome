<div class="modal-overlay" data-modal="lp_list">
    <div class="modal modal-side modal-right">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title" data-translate="modal_lplist_title"></h2>
                <p class="modal_desc" data-translate="modal_lplist_desc"></p>

                <div class="modal_close" data-modal-close="lp_list">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_section pb-3">
                <button class="btn btn-text btn-accent" type="button" data-modal-btn="lp_add">
                    <i class="bi bi-plus-lg"></i>
                    <span data-translate="modal_lplist_button_add"></span>
                </button>
            </div>
            <div class="modal_separator"></div>
            <?php if (count($get_alrp) > 1): ?>
                <div class="modal_section pt-3">
                    <div class="checkboxes-horizontal">
                        <label class="checkbox checkbox-pill">
                            <input type="radio" name="lp_types" class="checkbox_input" data-filter="all" data-filter-target="lp_types"
                                   checked/>
                            <span class="checkbox_text" data-translate="lptype_all"></span>
                        </label>

                        <?php if(in_array('2', $lp_types)): ?>
                            <label class="checkbox checkbox-pill">
                                <input type="radio" name="lp_types" class="checkbox_input" data-filter="2"
                                       data-filter-target="lp_types"/>
                                <span class="checkbox_text" data-translate="lptype_wl_short"></span>
                            </label>
                        <?php endif; ?>

                        <?php if(in_array('1', $lp_types)): ?>
                            <label class="checkbox checkbox-pill">
                                <input type="radio" name="lp_types" class="checkbox_input" data-filter="1"
                                       data-filter-target="lp_types"/>
                                <span class="checkbox_text" data-translate="lptype_tl_short"> </span>
                            </label>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($get_alrp)): ?>
            <div class="modal_section">
                <div class="input flex-grow-1">
                    <div class="input_inner">
                        <div class="input_icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <input type="text" class="input_field" name="search" data-search="alrp">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="modal_content">

                <?php if (empty($get_alrp)): ?>
                    <p class="text-light" data-translate="modal_lplist_placeholder"></p>
                <?php endif; ?>

                <?php if (!empty($get_alrp)): ?>


                    <div class="lp-list" data-filter-content="lp_types">
                        <?php foreach ($get_alrp as $key => $value): ?>
                            <div class="lp" data-filter-type="<?= $value["alrp_group"]; ?>" data-search-row="alrp">
                                <p class="lp_number">
                                    <?php $result = preg_replace('/(?<=\p{L})(?=\d)|(?<=\d)(?=\p{L})/u', ' ', $value["alrp"]); ?>
                                    <?= htmlspecialchars($result); ?>
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
                                    <i class="bi bi-pen text-accent" data-modal-btn="lp_edit" data-js-lp-edit-number="<?= htmlspecialchars($value["alrp"]); ?>"></i>
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
