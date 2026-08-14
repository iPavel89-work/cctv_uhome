<div class="modal-overlay" data-modal="video_date">
    <form method="get" action="player.php" class="modal modal-center">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title" data-translate="modal_date_title"></h2>
                <p class="modal_desc" data-translate="modal_date_desc"></p>

                <div class="modal_close" data-modal-close="video_date">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <div class="modal_separator"></div>
            <div class="modal_content">
                <input type="hidden" name="i_id" value="<?= $_SESSION['current_camera']['id'] ?>">
                <div class="input">
                    <p class="input_title" data-translate="modal_date_input_title"></p>
                    <div class="input_inner">
                        <input class="input_field" type="date" name="date" value="<?= htmlspecialchars($date); ?>" max="<?= date('Y-m-d'); ?>" onclick="this.showPicker && this.showPicker()">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal_actions">
            <button type="submit" class="btn btn-full btn-accent btn-modal" data-translate="modal_date_btn_submit">
            </button>
        </div>
    </form>
</div>
