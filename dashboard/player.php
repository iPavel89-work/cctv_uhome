<?php
$ADMIN_PAGE = true;
$PAGE = 'player';
$PAGE_TITLE = 'Плеер';
$PAGE_TITLE_KEY = 'page_player_title';
include "../includes/base.php";
include "../includes/data.php";
include "../includes/dict.php";
include "../includes/forpost.php";

if(!isset($_GET['i_id'])){
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
$date = date('Y-m-d');
if(isset($_GET['date'])){
    $date = sanitize_text($_GET['date']);
}

$i_id = sanitize_text($_GET['i_id']);
if(!in_array($i_id, $_SESSION['data']['camera_list'])){

    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}

include_once $BASE_PATH . "/parts/head.php";

if(array_key_exists($i_id, $_SESSION['data']['intercom'])){
    $_SESSION['current_camera']['id']=$i_id;
    $_SESSION['current_camera']['type']='intercom';
    $camera_data=$_SESSION['data']['intercom'][$i_id];
    $camera_type = "intercom";
//    echo json_encode($camera_data,JSON_UNESCAPED_UNICODE);
}
if(array_key_exists($i_id,$_SESSION['data']['camera'])){
    $_SESSION['current_camera']['id']=$i_id;
    $_SESSION['current_camera']['type']='camera';
    $camera_data=$_SESSION['data']['camera'][$i_id];
    $camera_type = "camera";
//    echo json_encode($camera_data,JSON_UNESCAPED_UNICODE);
}
if(array_key_exists($i_id,$_SESSION['data']['gate'])){
    $_SESSION['current_camera']['id']=$i_id;
    $_SESSION['current_camera']['type']='gate';
    $camera_data=$_SESSION['data']['gate'][$i_id];
    $camera_type = "gate";
//    echo json_encode($camera_data,JSON_UNESCAPED_UNICODE);
}
$fp_auth = fp_auth($camera_data['fp_login'],$camera_data['fp_pass']);
if($fp_auth['result']=='error'){
    header('Location: ' . $document_root . '/dashboard/index.php?notice=incorrect');
    exit;
}
$fp_session_id = $fp_auth['SessionID']; //id сессии форпоста
$fp_cameraID = $camera_data['cameraID'];
$_SESSION['current_camera']['session_id']=$fp_session_id;
$_SESSION['current_camera']['camera_id']=$camera_data['cameraID'];
$_SESSION['current_camera']['camera_ip']=$camera_data['camera_ip'];
$_SESSION['current_camera']['camera_login']=$camera_data['camera_login'];
$_SESSION['current_camera']['camera_password']=$camera_data['camera_password'];


$data['session_id']=$fp_session_id;
$data['camera_id']=$fp_cameraID;
$data['upper_date'] = $date." 23:59:59";
$data['lower_date'] = $date." 00:00:00";
$data['date']=$date;
$data['i_id']=$i_id;
$fp_events =  fp_get_events($data);
$intercom_events = intercom_get_events($data);
$all_events = array_merge($intercom_events,$fp_events); //все события

$last_event = array_slice($all_events,0,5); //5 последних событий
$times = array_column($all_events, 'Time'); // работает и с объектами, и с массивами
array_multisort($times, SORT_DESC, $all_events);


if($fp_events['result']=='error'){
    echo "Ошибка получения событий";
    exit;
}


if(isset($_GET['date'])){
    $date = sanitize_text($_GET['date']);
    $data['ts']= strtotime($date);
    $get_translation_url = fp_archive_url($data);
}
else{
    $get_translation_url = fp_online_url($data);
}

if($get_translation_url['result']=='error'){
    echo "Ошибка получения потока";
}

$translation_url = $get_translation_url['URL'];

$_SESSION['current_camera']['url']=$translation_url;

//echo json_encode($_SESSION['current_camera'],JSON_UNESCAPED_UNICODE);

$get_alrp = get_alrp(["i_id"=>$i_id]);

$get_customers = get_customers($_SESSION['current_hid']);

echo $fp_session_id;
?>

<a href="<?= $document_root ?>/dashboard/index.php">Домой</a>
<span><?= $_SESSION["address"][$_SESSION["current_hid"]]; ?></span> <span><?= $camera_data['name'] ?></span>

<br>
<h2>Доступные адреса</h2>
<?php foreach ($_SESSION["address"] as $addres_id => $addres_name): ?>
    <a href="<?= $document_root ?>/index.php?hid=<?= $addres_id; ?>"><?= $addres_name; ?></a>
<?php endforeach; ?>
<br><br>


<div class="layout">
    <div class="layout_inner">
        <div class="layout_content">
<!--            <video autoplay muted data-js-timeline-video></video>-->
            <video
                    id="video"

                    autoplay
                    muted
                    style="width:100%">
            </video>
            <div data-js-timeline></div>
        </div>
        <div class="layout_widgets">
            <div class="widget">
                <div class="widget_title">
                    Управление
                </div>
                <div class="widget_content">
                    <?php if($_SESSION['current_camera']['type']=='gate'): ?>

                        <?php if($permitions['control_gate']): ?>
                            <button type="button">
                                Открыть шлагбаум
                            </button>
                        <?php endif; ?>

                        <?php if($permitions['lp_view']): ?>
                            <button type="button">
                                Список номеров
                            </button>
                        <?php endif; ?>

                        <?php if($permitions['lp_add']): ?>
                            <button type="button">
                                Добавить номер
                            </button>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php if($_SESSION['current_camera']['type']=='intercom'): ?>
                        <button type="button">
                            Открыть двери
                        </button>
                    <?php endif; ?>


                </div>
            </div>

            <?php if($permitions['video_events']): ?>
                <div class="widget">
                    <div class="widget_title">
                        Последние события
                    </div>
                    <div class="widget_content">
                        <?php foreach($all_events as $key=>$value): ?>
                            <p data-js-event="<?= $value['Time']; ?>"><?= date("H:i:s", $value['Time']); ?> <?= $events_translate[$value['EventSubjectID']]; ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/flv.js/dist/flv.min.js"></script>


<script>

    // 1. Объявляем глобальную переменную для плеера и находим элемент видео
    let flvPlayer = null;
    const videoElement = document.getElementById('video'); // ID должен совпадать с вашим тегом <video id="video">
    // 2. Универсальная функция для запуска и смены трансляции
    function changeStream(newUrl) {
        if (!flvjs.isSupported()) return;

        // Правильно уничтожаем старый плеер, если он уже работал
        if (flvPlayer) {
            flvPlayer.pause();
            flvPlayer.unload();
            flvPlayer.detachMediaElement();
            flvPlayer.destroy();
            flvPlayer = null;
        }

        // Создаем новый плеер с переданным URL
        flvPlayer = flvjs.createPlayer({
            type: 'flv',
            url: newUrl,
            isLive: true,
            cors: true
        }, {
            enableStashBuffer: false, // Важно для Live: убирает растущую задержку
            fixAudioTimestampGap: false
        });

        // Привязываем к HTML-элементу и запускаем
        flvPlayer.attachMediaElement(videoElement);
        flvPlayer.load();
        flvPlayer.play().catch(error => {
            console.log("Автозапуск заблокирован браузером, нужен клик пользователя:", error);
        });
    }

    // 3. ПЕРВЫЙ ЗАПУСК: Передаем ссылку из PHP прямо в нашу функцию
    const initialUrl = '<?= htmlspecialchars($translation_url, ENT_QUOTES, 'UTF-8'); ?>';
    changeStream(initialUrl);


    const BUTTON_EVENTS = document.querySelectorAll('[data-js-event]');
    if (BUTTON_EVENTS.length > 0) {
        BUTTON_EVENTS.forEach(button => {
            button.addEventListener('click', (e) => {
                const TIME_EVENT = button.getAttribute('data-js-event'); // Берём значение времени у кнопки в дата-атрибуте
                handleChangeTime(TIME_EVENT); // Передаём время в функцию
            });
        });
    }

    // Функция
    async function handleChangeTime(time) {

        let TIME = time; // Значение полученного времени
        let TIME_QUERY = Math.round(Date.parse(TIME) / 1000); // Конвертируем время в секунды
        // Отправляем запрос и передаём туда время, в src вернётся строка со значением, либо просто ""
        const src = await QUERY_VIDEO_API(TIME);

        // Провеярем, чтобы строка не была пустой
        if (src.length > 0) {
            changeStream(src);
        }
    }
    const API_URL = "api.php"; // Путь до API (сейчас лежит в корне)
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


</script>







<br><br>
<?php
$lp_types = array_unique(array_column($get_alrp, 'alrp_group'));
?>




<?php if($camera_type === "gate"): ?>

    <h1>Номера телефонов</h1>

    <?php if(!empty($get_alrp)): ?>

        <?php if(count($get_alrp) > 1): ?>
            <label>
                <input type="radio" name="lp_types" data-filter="all" data-filter-target="lp_types" checked /> <span >Все</span>
            </label>
            <?php foreach($lp_types as $value): ?>
                <label>
                    <input type="radio" name="lp_types" data-filter="<?= $value; ?>" data-filter-target="lp_types" /> <span><?= $value; ?></span>
                </label>
            <?php endforeach; ?>

            <br><br>
        <?php endif; ?>

            <div data-filter-content="lp_types">
                <?php foreach($get_alrp as $key=>$value): ?>
                <div data-filter-type="<?= $value["alrp_group"]; ?>">
                    <p><?= htmlspecialchars($value["alrp"]) ?></p>

                    <?php if($value["alrp_group"] === 1): ?>
                     Временный
                    <?php endif;?>

                    <form action="api.php" method="post">
                        <input type="hidden" name="action" value="remove_alrp">
                        <input type="hidden" name="lp" value="<?= htmlspecialchars($value["alrp"]) ?>">
                        <button type="submit">Удалить</button>
                    </form>
                    <br>
                </div>
                <?php endforeach; ?>
            </div>

            <br><br>

    <?php else: ?>
        <p>Нет номеров</p>
    <?php endif; ?>

<?php endif; ?>




<!---->
<!--<h1>Все события</h1>-->
<!---->
<!---->
<?php
//    $events_type = array_unique(array_column($all_events, 'EventSubjectID'));
//?>
<!---->
<!---->
<?php //if(!empty($all_events)): ?>
<!---->
<!--    --><?php //if(count($events_type) > 1): ?>
<!--        <label>-->
<!--        <input type="radio" name="events_type" data-filter="all" data-filter-target="events_type" checked /> <span >Все</span>-->
<!--        </label>-->
<!--        --><?php //foreach($events_type as $value): ?>
<!--            <label>-->
<!--                <input type="radio" name="events_type" data-filter="--><?php //= $value; ?><!--" data-filter-target="events_type" /> <span>--><?php //= $events_translate[$value]; ?><!--</span>-->
<!--            </label>-->
<!--        --><?php //endforeach; ?>
<!---->
<!--        <br><br>-->
<!--    --><?php //endif; ?>
<!---->
<!--    <div data-filter-content="events_type">-->
<!--    --><?php //foreach($all_events as $key=>$value): ?>
<!--        <p data-filter-type="--><?php //= $value['EventSubjectID']; ?><!--">--><?php //= date("H:i:s", $value['Time']); ?><!-- --><?php //= $events_translate[$value['EventSubjectID']]; ?><!--</p>-->
<!--    --><?php //endforeach; ?>
<!--    </div>-->
<!--    <br><br>-->
<?php //else: ?>
<!--    <p>Нет событий</p>-->
<?php //endif; ?>

<script>
    const FILTER_TABS = document.querySelectorAll('input[type="radio"][data-filter]');

    FILTER_TABS.forEach(radio => {
        radio.addEventListener('change', () => {

            const FILTER_TARGET_NAME = radio.getAttribute('data-filter-target');
            const FILTER_VALUE = radio.getAttribute('data-filter');

            const FILTER_CONTENT = document.querySelector(`[data-filter-content="${FILTER_TARGET_NAME}"]`);
            const FILTER_LIST = FILTER_CONTENT.querySelectorAll('[data-filter-type]');

            if (FILTER_VALUE === 'all') {
                FILTER_LIST.forEach(item => {
                    item.style.display = 'flex';
                });
            } else {
                FILTER_LIST.forEach(item => {
                    if (item.getAttribute('data-filter-type') === FILTER_VALUE) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }


        });
    });

</script>

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


<form action="api.php" method="POST">
    <select name="action">
        <option value="add_alrp">Добавить</option>
        <option value="edit_alrp">Редактировать</option>
        <option value="remove_alrp">Удалить</option>
    </select>

    <select name="customer_id">
        <?php foreach ($get_customers as $key => $value): ?>
            <option value="<?= $value['id']; ?>"><?= $value['flat']; ?></option>
        <?php endforeach; ?>
    </select>

    <input type="hidden" name="i_id" value="<?= $i_id; ?>">
    <input type="text" name="lp" value="777MBI09">
    <input type="text" name="customer_id" value="1">
    <input type="text" name="group" value="2">
    <input type="text" name="date_to" value="2026-08-30 23:59:59">
    <input type="text" name="date_from" value="2026-08-30 00:00:00">
    <input type="text" name="description" value="тестовый абон2">
<!--    <input type="hidden" name="ts" value="2026-07-30 08:00:00">-->
    <input type="submit" value="изменить номер">

</form>
