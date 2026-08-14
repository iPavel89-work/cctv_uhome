let CURRENT_LANG_PAGE = 'ru';

const translations = {
  ru: {
    // Основные
    word_backpage: "Назад",
    word_exit: "Выход",
    word_yes: "Да",
    word_no: "Нет",
    word_on: "Вкл.",
    word_off: "Выкл.",
    word_clear: "Очистить",
    word_search: "Поиск...",
    word_edit_address: "Изменить",

    // Устройства
    word_all: "Все",
    word_intercoms: "Домофоны",
    word_cameras: "Камеры",
    word_gates: "Шлагбаумы",

    // Роли
    role_admin: "Администратор",

    // Форма входа
    page_auth_title: "Авторизация",
    page_auth_card_title: "Вход",
    page_auth_card_desc: "Введите логин и пароль для входа в панель управления",
    auth_form_input_login_title: "Логин",
    auth_form_input_password_title: "Пароль",
    auth_form_button_submit: "Войти",

    // Адрес - страница
    page_addressplaceholder_title: "Адрес",
    page_addressplaceholder_desc: "У вас доступно несколько адресов. Выберите один из них для доступа к камерам.",
    page_addressplaceholder_button: "Изменить адрес",

    // Изменение языка
    modal_language_title: "Язык интерфейса",
    modal_language_desc: "Язык будет применён без перезагрузки страницы",
    modal_language_notice: "Кликните, чтобы перевести",

    // Profile
    modal_profile_address_placeholder: "Выберите адрес",
    modal_profile_address_desc: "Нажмите, чтобы изменить адрес",
    modal_profile_theme_title: "Тёмный режим",
    modal_profile_theme_desc: "Изменить цветовую тему",
    modal_profile_btn_logout: "Выйти из аккаунта",

    // Изменение адреса
    modal_address_title: "Выберите адрес",

    // Управление
    widget_controls_title: "Управление",
    gate_actions_button_open: "Открыть шлагбаум",
    gate_actions_button_lpadd: "Добавить номер",
    gate_actions_button_lplist: "Список номеров",
    intercom_actions_button_open: "Открыть домофон",

    // Действия
    widget_actions_title: "Действия",
    video_button_screenshot: "Сделать скриншот",
    video_button_date: "Изменить дату",
    video_button_download: "Скачать видео",
    video_button_online: "Прямой эфир",

    // Типы событий
    eventtype_all: "Все",
    eventtype_open_door: "Открытие домофона",
    eventtype_open_gate: "Открытие шлагбаума",
    eventtype_lp_wl: "Разрешённое ТС",
    eventtype_lp_tl: "Временное ТС",
    eventtype_move: "Движение",

    // События
    widget_events_title: "События",
    widget_events_more: "Показать все",

    // События - Модальное окно
    modal_events_title: "События",
    modal_events_desc: "Вы можете клинкуть на событие, чтобы перейти к нужной дате",
    modal_events_placeholder: "Нет событий",

    // Изменение даты - Модальное окно
    modal_date_title: "Изменение даты",
    modal_date_desc: "После выбора даты произойдёт перезагрузка страницы",
    modal_date_input_title: "Дата",
    modal_date_btn_submit: "Перейти к дате",

    // Скачать видео - Модальное окно
    modal_download_title: "Скачать видео",
    modal_download_desc: "Выберите дату начала и продолжительность, чтобы скачать",
    modal_download_input_date_title: "Начальная дата",
    modal_download_input_duration_title: "Продолжительность",
    modal_download_btn_submit: "Скачать видео",

    // Добавление ТС
    modal_lpadd_title: "Новое ТС",
    modal_lpadd_desc: "Выберите список, в который будет добавлено новое транспортное средство",
    modal_lpadd_input_number_title: "Номер ТС",
    modal_lpadd_input_number_notice: "Не изменяется!",
    modal_lpadd_input_number_hint: "Номер считывается слева направо",
    modal_lpadd_input_description_title: "ФИО",
    modal_lpadd_input_flat_title: "Квартира",
    modal_lpadd_input_date_from_title: "Дата начала",
    modal_lpadd_input_date_to_title: "Дата окончания",
    modal_lpadd_button_submit: "Добавить ТС",

    // Изменение ТС
    modal_lpedit_title: "Редактирование ТС",
    modal_lpedit_desc: "Изменение данных транспортного средства",
    modal_lpedit_input_number_title: "Номер ТС",
    modal_lpedit_input_number_notice: "Не изменяется!",
    modal_lpedit_input_description_title: "ФИО",
    modal_lpedit_input_flat_title: "Квартира",
    modal_lpedit_input_date_from_title: "Дата начала",
    modal_lpedit_input_date_to_title: "Дата окончания",
    modal_lpedit_button_submit: "Изменить ТС",

    // Типы номеров
    lptype_all: 'Все',
    lptype_wl: 'Разрешённый список',
    lptype_tl: 'Временный список',
    lptype_wl_short: 'Разрешённые',
    lptype_tl_short: 'Временные',

    // Список номеров
    modal_lplist_title: "Список ТС",
    modal_lplist_desc: "Номера транспортных средств, имеющих доступ к автоматическому открытию шлагбаума",
    modal_lplist_button_add: "Добавить ТС",
    modal_lplist_button_edit: "Изменить",
    modal_lplist_placeholder: "Нет номеров",

    // Всплывающие сообщения
    notice_access_denied: "Упс, что-то пошло не так...",
    notice_perm: "Недостаточно прав!",
    notice_success: "Выполнено!",
    notice_error: "Ошибка!",
    notice_empty: "Данные не были получены!",
    notice_incorrect: "Неверные данные!",

    // Фильтры и таблицы
    table_placeholder_empty: "Информация отсутствует за выбранный период или за последний месяц",


    // Конвертация даты
    date_today: 'Сегодня', 
    date_yesterday: 'Вчера', 
    date_beforeYesterday: 'Позавчера',
    date_month_1: "янв.",
    date_month_2: "фев.",
    date_month_3: "мар.",
    date_month_4: "апр.",
    date_month_5: "мая",
    date_month_6: "июн.",
    date_month_7: "июл.",
    date_month_8: "авг.",
    date_month_9: "сен.",
    date_month_10: "окт.",
    date_month_11: "ноя.",
    date_month_12: "дек.",

  },
  kk: {

    // Модальные окна
    modal_translate_title: "Language",

    date_today: 'Búgin', 
    date_yesterday: 'Keshe', 
    date_beforeYesterday: 'Aldyńǵy kúni',
    date_month_1: "qań.",
    date_month_2: "aqp.",
    date_month_3: "naý.",
    date_month_4: "sáý.",
    date_month_5: "mam.",
    date_month_6: "mau.",
    date_month_7: "shil.",
    date_month_8: "tam.",
    date_month_9: "qyr.",
    date_month_10: "qaz.",
    date_month_11: "qar.",
    date_month_12: "jel.",
  },
};
