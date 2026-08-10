<div class="modal-overlay" data-modal="lp_edit">
    <form action="api.php" method="POST" class="modal modal-side modal-right">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title">Редактирование ТС</h2>
                <div class="modal_desc">
                    Изменение данных транспортного средства
                </div>
                <div class="modal_close" data-modal-close="lp_edit">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_section">
                <div class="checkboxes-horizontal">
                    <label class="checkbox checkbox-text">
                        <input type="radio" name="group" value="2" class="checkbox_input" data-js-alrp-group-checkbox>
                        <span class="checkbox_text">Разрешённый список</span>
                    </label>

                    <label class="checkbox checkbox-text">
                        <input type="radio" name="group" value="1" class="checkbox_input" data-js-alrp-group-checkbox>
                        <span class="checkbox_text">Временный список</span>
                    </label>
                </div>

            </div>
            <div class="modal_separator"></div>
            <div class="modal_content">

                <div class="form_line">
                    <div class="input">
                        <div class="input_title">
                            Номер ТС
                        </div>
                        <div class="input_inner">
                            <input type="text" class="input_field" name="lp" readonly>
                            <div class="input_section">
                                <p class="text-danger text-small">
                                    Не изменяется!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form_line">
                    <div class="input">
                        <div class="input_title">
                            ФИО
                        </div>
                        <div class="input_inner">
                            <input type="text" class="input_field" name="description">
                        </div>
                    </div>
                </div>

                <div class="form_line">
                    <div class="select">
                        <div class="select_title">
                            Квартира:
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
                        <div class="input_title">
                            Дата начала
                        </div>
                        <div class="input_inner">
                            <input type="datetime-local" class="input_field" name="date_from" value="<?= date('Y-m-d\TH:i'); ?>">
                        </div>
                    </div>
                </div>

                <div class="form_line d-none" data-js-alrp-group-date>
                    <div class="input">
                        <div class="input_title">
                            Дата конца
                        </div>
                        <div class="input_inner">
                            <input type="datetime-local" class="input_field" name="date_to" value="<?= date('Y-m-d\TH:i', strtotime('+1 day') ); ?>" >
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="modal_actions">
            <input type="hidden" name="action" value="edit_alrp">
            <button type="submit" class="btn btn-modal btn-accent btn-full">Изменить</button>
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

<!--<form action="api.php" method="post">-->
<!--    <input type="hidden" name="action" value="remove_alrp">-->
<!--    <input type="hidden" name="lp" value="--><?php //= htmlspecialchars($value["alrp"]) ?><!--">-->
<!--    <button type="submit">Удалить</button>-->
<!--</form>-->


