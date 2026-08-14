// CAPTCHA
const CAPTCHA_WIDGET = document.querySelector("#cap");
const isMobile = window.innerWidth < 1280;

document.addEventListener('DOMContentLoaded', () => {

    // FIX HEIGHT
    setVh();
    window.addEventListener('resize', setVh);

    // MODAL
    MODAL.init();

    // CAPTCHA
    if (CAPTCHA_WIDGET) {
        CAPTCHA_WIDGET.closest('form').querySelector("button[type='submit']").disabled = true;
        CAPTCHA_WIDGET.addEventListener("solve", (e) => {
            CAPTCHA_WIDGET.closest('form').querySelector("button[type='submit']").disabled = false;
        });
    }


    // FETCH
    const FETCHFORMS = document.querySelectorAll('[data-js-form-fetch]');

    FETCHFORMS.forEach(form => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const result = await QUERY_API(event.target);

            if (result.success) {
                console.log('Данные получены:', result.data);
            } else {
                console.error('Не удалось отправить:', result.error);
            }
        });
    });


    // VIDEO ACTIONS
    const VIDEO_FORM = document.querySelectorAll('[data-js-video-form]');
    VIDEO_FORM.forEach(form => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const action = event.target.getAttribute('data-js-video-form');
            if (action === 'download') {
                form.querySelector('button[type="submit"]').disabled = true;
                const result = await QUERY_API(event.target);

                if (result.success) {
                    const link = document.createElement('a');
                    link.href = result.data.URL;
                    link.download = result.data.URL;
                    link.click();

                    link.remove();

                    showToast('Видео скачивается...', 'success')
                } else {
                    showToast('Видео не было создано', 'error')
                }
                form.querySelector('button[type="submit"]').disabled = false;
            }

        });

    });

    // INTERCOM ACTIONS
    const INTERCOM_FORMS = document.querySelectorAll('[data-js-intercom-form]');

    INTERCOM_FORMS.forEach(form => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const action = event.target.getAttribute('data-js-intercom-form');
            if (action === 'open_door') {
                form.querySelector('button[type="submit"]').disabled = true;
                const result = await QUERY_API(event.target);

                if (result.success) {
                    showToast('Домофон открыт!', 'success')
                } else {
                    showToast('Ошибка открытия домофона.', 'error')
                }
                form.querySelector('button[type="submit"]').disabled = false;
                return;
            }

        });

    });


    // GATE ACTIONS
    const GATE_FORMS = document.querySelectorAll('[data-js-gate-form]');
    GATE_FORMS.forEach(form => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const action = event.target.getAttribute('data-js-gate-form');
            if (action === 'open_gate') {
                form.querySelector('button[type="submit"]').disabled = true;
                const result = await QUERY_API(form);

                if (result.success) {
                    showToast('Шлагбаум открыт!', 'success')
                } else {
                    showToast('Ошибка открытия шлагбаума.', 'error')
                }
                form.querySelector('button[type="submit"]').disabled = false;
                return;
            }

        });

    });

    // Фильтр событий
    const FILTER_TABS = document.querySelectorAll('input[type="radio"][data-filter]');

    FILTER_TABS.forEach(radio => {
        radio.addEventListener('change', () => {

            const FILTER_TARGET_NAME = radio.getAttribute('data-filter-target');
            const FILTER_VALUE = radio.getAttribute('data-filter');

            const FILTER_CONTENT = document.querySelector(`[data-filter-content="${FILTER_TARGET_NAME}"]`);
            const FILTER_LIST = FILTER_CONTENT.querySelectorAll('[data-filter-type]');

            if (FILTER_VALUE === 'all') {
                FILTER_LIST.forEach(item => {
                    item.classList.remove('hide-filter');
                });
            } else {
                FILTER_LIST.forEach(item => {
                    if (item.getAttribute('data-filter-type') === FILTER_VALUE) {
                        item.classList.remove('hide-filter');
                    } else {
                        item.classList.add('hide-filter')
                    }
                });
            }


        });
    });


    // Поиск по элементам без перезагрузки
    const SEARCH_TABLE_INPUT = document.querySelectorAll("[data-search]");

    if (SEARCH_TABLE_INPUT.length > 0) {

        SEARCH_TABLE_INPUT.forEach(input => {

            input.addEventListener("input", function () {

                const SEARCH_TABLE_SELECTOR = this.dataset.search;

                if (!SEARCH_TABLE_SELECTOR) {
                    return;
                }

                const SEARCH_TABLE_ROW = document.querySelectorAll(`[data-search-row='${SEARCH_TABLE_SELECTOR}']`);

                // если таблицы не найдены
                if (!SEARCH_TABLE_ROW.length) {
                    console.warn("Таблицы по селектору не найдены:", SEARCH_TABLE_SELECTOR);
                    return;
                }

                const filter = this.value.toLowerCase();

                SEARCH_TABLE_ROW.forEach(element => {
                    const found = element.textContent.toLowerCase().includes(filter)
                    element.style.display = found ? "" : "none";

                });

            });

        });

    }


    // FULLSCREEN Видео

    const FULLSCREEN_VIDEO = document.querySelector('[data-js-fullscreen-element]');
    const FULLSCREEN_BTN = document.querySelector('[data-js-fullscreen-btn]');

    function openFullscreen(element) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) { /* Chrome, Safari, Opera */
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) { /* IE/Edge */
            element.msRequestFullscreen();
        } else if (element.webkitEnterFullscreen) { /* Специально для iOS Safari (iPhone) */
            element.webkitEnterFullscreen();
        }
    }

    function closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }

    if(FULLSCREEN_BTN) {
        FULLSCREEN_BTN.addEventListener('click', () => {
            const isFullscreen = document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.msFullscreenElement;
            if (!isFullscreen) {
                openFullscreen(FULLSCREEN_VIDEO);
            } else {
                closeFullscreen();
            }
        });
    }



    const COPY_BUTTONS = document.querySelectorAll("[data-copy]");
    if (COPY_BUTTONS.length > 0) {
        COPY_BUTTONS.forEach(button => {
            button.addEventListener("click", () => {
                const text =
                    button.dataset.copy ||
                    document.querySelector(button.dataset.target)?.value ||
                    '';
                copyToClipboard(text);
            });
        });
    }

    const SIDEBAR_BUTTONS = document.querySelectorAll("[data-sidebar-toggle-btn]");
    if (SIDEBAR_BUTTONS.length > 0) {
        SIDEBAR_BUTTONS.forEach(button => {
            button.addEventListener("click", () => {
                const target = document.querySelector(`[data-sidebar-toggle="${button.dataset.sidebarToggleBtn}"]`);
                if (target) {
                    target.classList.toggle("isHide");
                }
            });
        });
    }

})