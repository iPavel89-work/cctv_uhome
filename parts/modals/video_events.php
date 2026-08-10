<div class="modal-overlay" data-modal="video_events">
    <div class="modal modal-side modal-right">
        <div class="modal_inner">
            <div class="modal_header">
                <h2 class="modal_title">События</h2>
                <div class="modal_desc">Вы можете клинкуть на событие, чтобы перейти к нужной дате</div>

                <div class="modal_close" data-modal-close="video_events">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>

            <?php
                // Получение списка всех типов событий для фильтра
                $events_type = array_unique(array_column($all_events, 'EventSubjectID'));
            ?>

            <?php if(count($events_type) > 1): ?>
                <div class="modal_section">
                       <div class="checkboxes-horizontal">
                           <label class="checkbox checkbox-text">
                               <input type="radio" name="events_type" class="checkbox_input" data-filter="all" data-filter-target="events_type" checked>
                               <span class="checkbox_text">Все</span>
                           </label>


                           <?php foreach($events_type as $value): ?>
                               <label class="checkbox checkbox-text">
                                   <input type="radio" name="events_type" class="checkbox_input" data-filter="<?= $value; ?>" data-filter-target="events_type">
                                   <span class="checkbox_text"><?= $events_translate[$value]; ?></span>
                               </label>
                           <?php endforeach; ?>
                       </div>

                </div>

            <?php endif; ?>
            <div class="modal_separator"></div>

            <div class="modal_content">
                <?php if(!empty($all_events)): ?>

                    <div class="items" data-filter-content="events_type">
                        <?php foreach($all_events as $key=>$value): ?>
                            <div class="item item-hover" data-js-event="<?= $value['Time']; ?>" data-filter-type="<?= $value['EventSubjectID']; ?>">
                                <div class="item_icon">
                                    <i class="bi bi-exclamation-triangle text-danger"></i>
                                </div>
                                <div class="item_content">
                                    <div class="item_title"><?= $events_translate[$value['EventSubjectID']]; ?></div>
                                    <p class="item_desc"><?= date("H:i:s", $value['Time']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                <p>Нет событий</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
