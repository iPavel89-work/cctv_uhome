<div class="modal-overlay" data-modal="lp_add">
    <form action="<?= $document_root ?>/dashboard/api.php" method="POST" class="modal modal-side modal-right" data-js-form-fetch="lp_add">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title" data-translate="modal_lpadd_title"></h2>
                <div class="modal_desc" data-translate="modal_lpadd_desc"></div>
                <div class="modal_close" data-modal-close="lp_add">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_section">
                <div class="checkboxes-horizontal">
                    <label class="checkbox checkbox-text">
                        <input type="radio" name="group" value="2" class="checkbox_input" checked data-js-alrp-group-checkbox>
                        <span class="checkbox_text" data-translate="lptype_wl"></span>
                    </label>

                    <label class="checkbox checkbox-text">
                        <input type="radio" name="group" value="1" class="checkbox_input" data-js-alrp-group-checkbox>
                        <span class="checkbox_text" data-translate="lptype_tl"></span>
                    </label>
                </div>

            </div>
            <div class="modal_separator"></div>
            <div class="modal_content">

                <div class="form_line">
                    <div class="input">
                        <div class="input_title" data-translate="modal_lpadd_input_number_title">
                        </div>
                        <div class="input_inner">
                            <input type="text" class="input_field" name="lp">
                            <div class="input_section">
                                <p class="text-danger text-small" data-translate="modal_lpadd_input_number_notice">
                                </p>
                            </div>
                        </div>
                        <div class="input_notice">
                            <p class="text-small text-light" data-tanslate="modal_lpadd_input_number_hint"></p>
                        </div>
                    </div>
                </div>

                <div class="form_line">
                    <div class="input">
                        <div class="input_title" data-translate="modal_lpadd_input_description_title">
                        </div>
                        <div class="input_inner">
                            <input type="text" class="input_field" name="description">
                        </div>
                    </div>
                </div>

                <div class="form_line">
                    <div class="select">
                        <div class="select_title" data-translate="modal_lpadd_input_flat_title">
                        </div>
                        <div class="select_inner">
                            <select name="customer_id" class="select_field">
                                <?php foreach ($get_customers as $key => $value): ?>
                                    <option value="<?= $value['id']; ?>"><?= $value['flat']; ?> кв.</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form_line d-none" data-js-alrp-group-date>
                    <div class="input">
                        <div class="input_title" data-translate="modal_lpadd_input_date_from_title">
                        </div>
                        <div class="input_inner">
                            <input type="datetime-local" class="input_field" name="date_from" value="<?= date('Y-m-d\TH:i'); ?>">
                        </div>
                    </div>
                </div>

                <div class="form_line d-none" data-js-alrp-group-date>
                    <div class="input">
                        <div class="input_title" data-translate="modal_lpadd_input_date_to_title">
                        </div>
                        <div class="input_inner">
                            <input type="datetime-local" class="input_field" name="date_to" value="<?= date('Y-m-d\TH:i', strtotime('+1 day') ); ?>" >
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="modal_actions">
            <input type="hidden" name="action" value="add_alrp">
            <button type="submit" class="btn btn-modal btn-accent btn-full" data-translate="modal_lpadd_button_submit"></button>
        </div>
    </form>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-js-alrp-group-checkbox]').forEach((checkbox) => {
            checkbox.addEventListener('change', (event) => {
                const group = event.target.value;
                const dateFields = document.querySelectorAll('[data-js-alrp-group-date]');
                if (group === '1') {
                    dateFields.forEach((field) => field.classList.remove('d-none'));
                } else {
                    dateFields.forEach((field) => field.classList.add('d-none'));
                }
            });
        });
    })
</script>


