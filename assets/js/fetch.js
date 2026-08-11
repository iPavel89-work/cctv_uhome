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