<div class="modal-overlay" data-modal="video_download">
    <form class="modal modal-center" data-js-form="video-download">
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
                            <input class="input_field" type="datetime-local" name="date" value="<?= htmlspecialchars($date); ?>T00:00:00" onclick="this.showPicker && this.showPicker()">
                        </div>
                    </div>
                </div>
                <div class="form_line">
                    <div class="input">
                        <p class="input_title">Продолжительность (Часы:Минуты)</p>
                        <div class="input_inner">
                            <input class="input_field" type="time" name="duration" value="00:10" onclick="this.showPicker && this.showPicker()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal_actions">
            <button type="submit" class="btn btn-full btn-danger btn-modal">
                Получить ссылку на скачивание
            </button>
        </div>
    </form>
</div>
