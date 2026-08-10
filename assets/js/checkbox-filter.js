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
