<div class="modal-overlay" data-modal="video_download">
    <form action="<?= $document_root; ?>/dashboard/api.php" method="POST" class="modal modal-center" data-js-video-form="download">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title" data-translate="modal_download_title"></h2>
                <p class="modal_desc" data-translate="modal_download_desc"></p>

                <div class="modal_close" data-modal-close="video_download">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_separator"></div>
            <div class="modal_content">
                <div class="form_line">
                    <div class="input">
                        <p class="input_title" data-translate="modal_download_input_date_title"></p>
                        <div class="input_inner">
                            <input class="input_field" type="datetime-local" name="ts" value="<?= date('Y-m-d\TH:i:s'); ?>" onclick="this.showPicker && this.showPicker()" data-js-video-download-date>
                        </div>
                    </div>
                </div>
                <div class="form_line">
                    <div class="select">
                        <p class="select_title" data-translate="modal_download_input_duration_title"></p>
                        <div class="select_inner">
                            <select name="duration" class="select_field">
                                <option value="1">1 мин.</option>
                                <option value="5">5 мин.</option>
                                <option value="10">10 мин.</option>
                                <option value="15">15 мин.</option>
                                <option value="30">30 мин.</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal_actions">
            <input type="hidden" name="action" value="download_archive">
            <button type="submit" class="btn btn-full btn-danger btn-modal" data-translate="modal_download_btn_submit">
            </button>
        </div>
    </form>
</div>
