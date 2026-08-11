<div class="modal-overlay" data-modal="video_download">
    <form action="<?= $documnet_root; ?>/dashboard/api.php" method="POST" class="modal modal-center" data-js-form-fetch="video_download">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title">Скачать видео</h2>
                <p class="modal_desc">Выберите дату начала и продолжительность, чтобы скачать</p>

                <div class="modal_close" data-modal-close="video_download">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_separator"></div>
            <div class="modal_content">
                <div class="form_line">
                    <div class="input">
                        <p class="input_title">Начальная дата</p>
                        <div class="input_inner">
                            <input class="input_field" type="datetime-local" name="ts" value="<?= htmlspecialchars($date); ?>T00:00:00" onclick="this.showPicker && this.showPicker()">
                        </div>
                    </div>
                </div>
                <div class="form_line">
                    <div class="select">
                        <p class="select_title">Продолжительность:</p>
                        <div class="select_inner">
                            <select name="duration" class="select_field">
                                <option value="1">1 минута</option>
                                <option value="5">5 минут</option>
                                <option value="10">10 минут</option>
                                <option value="15">15 минут</option>
                                <option value="30">30 минут</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal_actions">
            <input type="hidden" name="action" value="download_archive">
            <button type="submit" class="btn btn-full btn-danger btn-modal">
                Получить ссылку на скачивание
            </button>
        </div>
    </form>
</div>
