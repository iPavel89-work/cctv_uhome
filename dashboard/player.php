<?php
$ADMIN_PAGE = true;
$PAGE = 'player';
$PAGE_TITLE = 'Плеер';
$PAGE_TITLE_KEY = 'page_player_title';
include "../includes/base.php";
include "../includes/data.php";
include "../includes/dict.php";
include "../includes/forpost.php";

if (!isset($_GET['i_id'])) {
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
$date = date('Y-m-d');
if (isset($_GET['date'])) {
    $date = sanitize_text($_GET['date']);
}

$i_id = sanitize_text($_GET['i_id']);
if (!in_array($i_id, $_SESSION['data']['camera_list'])) {

    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}

include_once $BASE_PATH . "/parts/head.php";

if (array_key_exists($i_id, $_SESSION['data']['intercom'])) {
    $_SESSION['current_camera']['id'] = $i_id;
    $_SESSION['current_camera']['type'] = 'intercom';
    $camera_data = $_SESSION['data']['intercom'][$i_id];
    $camera_type = "intercom";
}
if (array_key_exists($i_id, $_SESSION['data']['camera'])) {
    $_SESSION['current_camera']['id'] = $i_id;
    $_SESSION['current_camera']['type'] = 'camera';
    $camera_data = $_SESSION['data']['camera'][$i_id];
    $camera_type = "camera";
}
if (array_key_exists($i_id, $_SESSION['data']['gate'])) {
    $_SESSION['current_camera']['id'] = $i_id;
    $_SESSION['current_camera']['type'] = 'gate';
    $camera_data = $_SESSION['data']['gate'][$i_id];
    $camera_type = "gate";
}
$fp_auth = fp_auth($camera_data['fp_login'], $camera_data['fp_pass']);
if ($fp_auth['result'] == 'error') {
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
$fp_session_id = $fp_auth['SessionID']; //id сессии форпоста
$fp_cameraID = $camera_data['cameraID'];
$_SESSION['current_camera']['session_id'] = $fp_session_id;
$_SESSION['current_camera']['camera_id'] = $camera_data['cameraID'];
$_SESSION['current_camera']['camera_ip'] = $camera_data['camera_ip'];
$_SESSION['current_camera']['camera_login'] = $camera_data['camera_login'];
$_SESSION['current_camera']['camera_password'] = $camera_data['camera_password'];

if ($permitions['video_events']) {
    $data['session_id'] = $fp_session_id;
    $data['camera_id'] = $fp_cameraID;
    $data['upper_date'] = $date . " 23:59:59";
    $data['lower_date'] = $date . " 00:00:00";
    $data['date'] = $date;
    $data['i_id'] = $i_id;
    $fp_events = fp_get_events($data);
    $intercom_events = intercom_get_events($data);
    $all_events = array_merge($intercom_events, $fp_events); //все события
    $last_events = array_slice($all_events, 0, 5); //5 последних событий
    $times = array_column($all_events, 'Time'); // работает и с объектами, и с массивами
    array_multisort($times, SORT_DESC, $all_events);
}

//if($fp_events['result']=='error'){
//    echo "Ошибка получения событий";
//    exit;
//}

if (isset($_GET['date'])) {
    $data['date'] = $date;
    $check_records = fp_get_records($data);

    if (empty($check_records['records']) || strtotime($date) > strtotime($check_records['records'][0]['FinishDate'])) {
        header('Location: ' . $document_root . '/dashboard/player.php?i_id=' . $i_id . '&notice=incorrect');
        exit;
    }

    $data['ts'] = strtotime($check_records['records'][0]['CreationDate']);
    $get_translation_url = fp_archive_url($data);
} else {
    $get_translation_url = fp_online_url($data);
}


//if($get_translation_url['result']=='error'){
//    echo "Ошибка получения потока";
//}


// Получение актуальной ссылки на онлайн-трансляцию
$translation_url = $get_translation_url['URL'];
$_SESSION['current_camera']['url'] = $translation_url;

if ($_SESSION['current_camera']['type'] == 'gate') {
    $get_alrp = get_alrp(["i_id" => $i_id]); // Получение номеров транспортных средств
    $lp_types = array_unique(array_column($get_alrp, 'alrp_group')); // Массив групп для фильтра
}

$get_customers = get_customers($_SESSION['current_hid']); // Получение номеров квартир клиентов по выбранному адресу


?>


    <div class="layout">
        <div class="layout_menu">
            <?php
            include $BASE_PATH . "/parts/sidebars/menu-left.php";
            ?>
        </div>
        <div class="layout_inner">
            <div class="layout_content">
                <div class="section section-info">
                    <a href="index.php" class="badge badge-base badge-s">
                        <i class="bi bi-chevron-left"></i>
                        <span>Назад</span>
                    </a>
                    <div class="badge badge-base badge-s hide-mobile" data-modal-btn="addresses">
                        <?= $_SESSION["address"][$_SESSION["current_hid"]]; ?>
                    </div>
                    <div class="badge badge-s">
                        <?= $camera_data['name'] ?>
                    </div>

                    <div class="badge badge-base badge-s ms-auto" data-modal-btn="video_date">
                        <i class="bi bi-calendar"></i>
                        <span><?= htmlspecialchars($date); ?></span>
                    </div>
                </div>

                <div class="section section-video">
                    <video
                            id="video"

                            autoplay
                            muted
                            style="width:100%">
                    </video>
                </div>

                <div class="section section-base section-timeline">
                    <div class="section_inner">
                        <div data-js-timeline></div>
                    </div>
                    <div class="section_actions hide-mobile">
                        <div class="btns-horizontal">
                            <button type="button" class="btn btn-text btn-accent" data-modal-btn="video_date">
                                <i class="bi bi-calendar"></i>
                                <span>Выбрать дату</span>
                            </button>
                            <button type="button" class="btn btn-text btn-accent" onclick="takeFlvScreenshot()">
                                <i class="bi bi-image"></i>
                                <span>Сделать скриншот</span>
                            </button>
                            <button type="button" class="btn btn-text btn-accent" data-modal-btn="video_download">
                                <i class="bi bi-download"></i>
                                <span>Скачать видео</span>
                            </button>

                            <a href="player.php?i_id=<?= $i_id; ?>" class="btn btn-text btn-danger ms-auto">
                                <i class="bi bi-circle-fill"></i>
                                <span>Прямой эфир</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="layout_widgets">

                <?php if($camera_type !== 'camera'): ?>
                    <div class="widget widget-controls">
                        <div class="widget_title">
                            Управление
                        </div>
                        <div class="widget_content">
                            <div class="widget_buttons">
                                <?php if ($_SESSION['current_camera']['type'] == 'gate'): ?>

                                    <?php if ($permitions['control_gate']): ?>
                                        <button class="btn btn-circle" type="button">
                                        <span class="btn-circle_icon">
                                            <i class="bi bi-door-open"></i>
                                        </span>
                                            <span class="btn-circle_text">
                                            Открыть шлагбаум
                                        </span>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($permitions['lp_view']): ?>
                                        <button class="btn btn-circle" type="button" data-modal-btn="lp_list">
                                        <span class="btn-circle_icon">
                                            <i class="bi bi-card-checklist"></i>
                                        </span>
                                            <span class="btn-circle_text">
                                            Список номеров
                                        </span>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($permitions['lp_add']): ?>
                                        <button class="btn btn-circle" type="button" data-modal-btn="lp_add">
                                        <span class="btn-circle_icon">
                                            <i class="bi bi-plus-lg"></i>
                                        </span>
                                            <span class="btn-circle_text">
                                            Добавить номер
                                        </span>
                                        </button>
                                    <?php endif; ?>

                                <?php endif; ?>

                                <?php if ($_SESSION['current_camera']['type'] == 'intercom'): ?>
                                    <button class="btn btn-circle" type="button">
                                    <span class="btn-circle_icon">
                                        <i class="bi bi-door-open"></i>
                                    </span>
                                        <span class="btn-circle_text">
                                        Открыть двери
                                    </span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="widget widget-controls">
                    <div class="widget_title">
                        Действия
                    </div>
                    <div class="widget_content">
                        <div class="widget_buttons">

                            <button class="btn btn-circle" type="button" data-modal-btn="video_date">
                                <span class="btn-circle_icon">
                                    <i class="bi bi-calendar"></i>
                                </span>
                                    <span class="btn-circle_text">
                                    Изменить дату
                                </span>
                            </button>

                            <?php if ($permitions['video_download']): ?>
                                <button class="btn btn-circle" type="button" data-modal-btn="video_download">
                                    <span class="btn-circle_icon">
                                        <i class="bi bi-cloud-download"></i>
                                    </span>
                                        <span class="btn-circle_text">
                                        Скачать видео
                                    </span>
                                </button>
                            <?php endif; ?>

                            <button class="btn btn-circle" type="button" onclick="takeVideoScreenshot()">
                                <span class="btn-circle_icon">
                                    <i class="bi bi-card-image"></i>
                                </span>
                                    <span class="btn-circle_text">
                                    Сделать скриншот
                                </span>
                            </button>

                        </div>
                    </div>
                </div>


                <?php if(!empty($last_events)): ?>
                    <?php if ($permitions['video_events']): ?>
                        <div class="widget widget-levents pb-0">
                            <div class="widget_title">
                                Последние события
                            </div>


                            <div class="widget_content">
                                <div class="items">
                                    <?php foreach ($last_events as $key => $value): ?>
                                        <div class="item item-hover" data-js-event="<?= $value['Time']; ?>">
                                            <div class="item_icon">
                                                <i class="bi bi-exclamation-triangle text-danger"></i>
                                            </div>
                                            <div class="item_content">
                                                <div class="item_title"><?= $events_translate[$value['EventSubjectID']]; ?></div>
                                                <div class="item_desc">
                                                    <p class="text-small text-light"><?= date("H:i:s", $value['Time']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if (count($all_events) > 1): ?>
                                <div class="widget_action">
                                    <button class="btn btn-text btn-accent btn-full btn-widget"
                                            data-modal-btn="video_events">
                                        Показать все
                                    </button>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
        <div class="layout_menu">
            <?php
            include $BASE_PATH . "/parts/sidebars/menu-right.php";
            ?>
        </div>
    </div>


    data-js-fullscreen-btn
    data-js-fullscreen-element

    <link rel="stylesheet" href="<?= $document_root; ?>/assets/css/vis-timeline.css">
    <script src="<?= $document_root; ?>/assets/js/vis-timeline.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flv.js/dist/flv.min.js"></script>


    <script>

        window.addEventListener('DOMContentLoaded', () => {


            const TIMELINE_VIDEO = document.getElementById('video'); // ID должен совпадать с вашим тегом <video id="video">
            const TIMELINE_ELEMENT = document.querySelector("[data-js-timeline]"); // Блок таймлайна
            let TIMELINE_LOADING = false; // Переменная текущего статуса запроса
            const BUTTON_EVENTS = document.querySelectorAll("[data-js-event]");
            const API_URL = 'api.php';

            // Переход по событиям
            if (BUTTON_EVENTS.length > 0) {
                BUTTON_EVENTS.forEach(button => {
                    button.addEventListener('click', (e) => {
                        const TIME_EVENT = button.getAttribute('data-js-event'); // Берём значение времени у кнопки в дата-атрибуте
                        handleChangeTime(TIME_EVENT); // Передаём время в функцию
                    });
                });
            }


            let flvPlayer = null;

            function changeStream(newUrl) {
                if (!flvjs.isSupported()) return;

                if (flvPlayer) {
                    flvPlayer.pause();
                    flvPlayer.unload();
                    flvPlayer.detachMediaElement();
                    flvPlayer.destroy();
                    flvPlayer = null;
                }

                flvPlayer = flvjs.createPlayer({
                    type: 'flv',
                    url: newUrl,
                    isLive: true,
                    cors: true
                }, {
                    enableStashBuffer: false,
                    fixAudioTimestampGap: false
                });

                // Привязываем к HTML-элементу и запускаем
                flvPlayer.attachMediaElement(TIMELINE_VIDEO);
                flvPlayer.load();
                flvPlayer.play().catch(error => {
                    console.log("Автозапуск заблокирован браузером, нужен клик пользователя:", error);
                });
            }

            const initialUrl = '<?= htmlspecialchars($translation_url, ENT_QUOTES, 'UTF-8'); ?>';
            changeStream(initialUrl);



            // Начало таймлайна и конец
            const TIMELINE_OPTIONS = {
                showCurrentTime: true,
                height: 64,
                start: new Date("<?= $date . ' 00:00:00'?>"), // PHP: Начальная дата всей полосы
                end: new Date("<?= $date . ' 23:59:59'?>"), // PHP: Конечная дата всей полосы
                min: new Date("<?= $date . ' 00:00:00'?>"), // PHP: Минимальная дата всей полосы
                max: new Date("<?= $date . ' 23:59:59'?>"), // PHP: Максимальная дата всей полосы
            };

            const TIMELINE_ITEMS = new vis.DataSet([]);

            // Инициализация таймлайна
            const TIMELINE = new vis.Timeline(TIMELINE_ELEMENT, TIMELINE_ITEMS, TIMELINE_OPTIONS);

            // Добавляем синий маркер
            TIMELINE.addCustomTime(new Date(), "vis-selectedTime");

            // Обработка клика на таймлайне
            // {time} вытаскиваем переменную time из event-a и передаём в функцию
            TIMELINE.on("click", ({time}) => {
                let TIME_QUERY = Math.round(Date.parse(time) / 1000); // Конвертируем время в секунды
                handleChangeTime(TIME_QUERY);
            });


            // Функция
            // async function handleChangeTime(time) {
            //
            //     let TIME = time; // Значение полученного времени
            //     let TIME_QUERY = Math.round(Date.parse(TIME) / 1000); // Конвертируем время в секунды
            //     // Отправляем запрос и передаём туда время, в src вернётся строка со значением, либо просто ""
            //     const src = await QUERY_VIDEO_API(TIME);
            //
            //     // Провеярем, чтобы строка не была пустой
            //     if (src.length > 0) {
            //         changeStream(src);
            //         MODAL.close('video_events')
            //     }
            // }

            // Функция
            async function handleChangeTime(time) {

                let TIME = Number(time); // Значение полученного времени

                // Если запрос уже отправлен, то ничего не делать
                if (TIMELINE_LOADING) {
                    return;
                }

                // Если кликнули на время, которое больше текущего
                if (TIME * 1000 > Date.now()) {
                    TIME = Date.now() / 1000; // Устанавливаем значение на текущее время
                }

                // Устанавливаем, что статус запроса в процессе получения данных
                TIMELINE_LOADING = true;
                // Добавляем класс таймлайну
                TIMELINE_ELEMENT.classList.add('timeline-loading');

                // Если есть кнопки с событиями, то блокируем их
                if (BUTTON_EVENTS.length > 0) {
                    BUTTON_EVENTS.forEach(btn => {
                        btn.setAttribute('disabled', 'true');
                    })
                }

                // Отправляем запрос и передаём туда время, в src вернётся строка со значением, либо просто ""
                const src = await QUERY_VIDEO_API(TIME);

                // Провеярем, чтобы строка не была пустой
                if (src.length > 0) {

                    // Устанавливаем позицию маркера по полученному TIME
                    TIMELINE.setCustomTime(TIME * 1000, "vis-selectedTime");

                    // Запускаем стрим
                    changeStream(src);

                    // Перемещаем визуально таймлайн к маркеру
                    TIMELINE.moveTo(TIME * 1000);

                }

                // Устанавливаем, что ответ получен
                TIMELINE_LOADING = false;

                // Удаляем класс у таймлайна
                TIMELINE_ELEMENT.classList.remove('timeline-loading');

                // Если есть кнопки с событиями, то разблокируем их
                if (BUTTON_EVENTS.length > 0) {
                    BUTTON_EVENTS.forEach(btn => {
                        btn.removeAttribute('disabled');
                    })
                }
                if(MODAL) {
                    MODAL.close('video_events')
                }

            }

            // Запрос на получения ссылки для видео
            async function QUERY_VIDEO_API(TIME) {

                let data = {};
                let formdata = new FormData();

                formdata.append("ts", TIME);
                formdata.append("tz", "18000");
                formdata.append("action", "get_archive");

                try {

                    const response = await fetch(API_URL, {
                        method: "POST",
                        body: formdata,
                        redirect: "follow"
                    });
                    data = await response.json();

                    if (data.ErrorCode !== undefined) {
                        toast.show('Ошибка получения архива', 'warning')
                    }

                } catch (error) {
                    console.log('Catch error ' + error)
                } finally {
                    return data.URL !== undefined ? data.URL : ""; // В конце в любом случае возвращаем ссылку или пустую строку
                }

            }

        });

    </script>


    <?php if($camera_type === 'gate'): ?>
    <script>

        const ALRP_DATA = {
            <?php foreach($get_alrp as $key=>$value): ?>
            "<?= $value["alrp"]; ?>": {
                "alrp": "<?= $value["alrp"]; ?>",
                "fullname": "<?= $value["fullname"]; ?>",
                "full_address": "<?= $value["full_address"]; ?>",
                "flat": "<?= $value["flat"]; ?>",
                "description": "<?= $value["description"]; ?>",
                "date_from": "<?= $value["date_from"]; ?>",
                "date_to": "<?= $value["date_to"]; ?>",
                "alrp_group": "<?= $value["alrp_group"]; ?>",
                "customer_id": "<?= $value["customer_id"]; ?>",
            },
            <?php endforeach; ?>
        };

    </script>
    <?php endif; ?>

<?php
if ($camera_type === "gate") {
    if ($permitions['lp_add']) {
        include $BASE_PATH . '/parts/modals/lp_add.php';
    }

    if ($permitions['lp_view']) {
        include $BASE_PATH . '/parts/modals/lp_list.php';
    }

    if ($permitions['lp_edit']) {
        include $BASE_PATH . '/parts/modals/lp_edit.php';
    }
}

if ($permitions['video_events']) {
    include $BASE_PATH . '/parts/modals/video_events.php';
}

if ($permitions['video_archive']) {
    include $BASE_PATH . '/parts/modals/video_date.php';
}

if ($permitions['video_download']) {
    include $BASE_PATH . '/parts/modals/video_download.php';
}

?>

<?php
include $BASE_PATH . '/parts/footer.php';
?>