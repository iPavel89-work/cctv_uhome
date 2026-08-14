// Получаем ссылку на скрытый инпут
const hiddenLangInput = document.querySelector('input[type="hidden"][name="language"]');

// Получаем язык из GET-параметра
const params = new URLSearchParams(window.location.search);
let currentLang = params.get("lang");

// Проверяем, поддерживается ли язык из GET
if (!translations || !translations[currentLang]) {

  let shortLang = "ru";

  const cookieLang = document.cookie.split("; ").find(row => row.startsWith("lang="));

  if (cookieLang) {
    const langValue = cookieLang.split("=")[1];
    shortLang = langValue;
  } else {
    // Берём язык браузера
    const browserLang = navigator.language || navigator.userLanguage; // e.g. "en-US"
    shortLang = browserLang.split("-")[0]; // "en"
  }

  
  currentLang = translations && translations[shortLang] ? shortLang : "ru";
}

// Обновляем значение скрытого поля (если оно есть)
if (hiddenLangInput) {
  hiddenLangInput.value = currentLang;
}


// Отмечаем радиокнопку и добавляем обработчик
document.querySelectorAll('[data-lang]').forEach(element => {

  element.addEventListener('click', e => {
    updateLanguage(e.currentTarget.dataset.lang);
    updateCaptchaLang(e.currentTarget.dataset.lang);
  });
});

// Функция обновления текста
function updateLanguage(lang) {

  document.querySelectorAll("[data-translate]").forEach(el => {
    const key = el.getAttribute("data-translate");
    if (translations[lang] && translations[lang][key] !== undefined) {
      el.textContent = translations[lang][key];
    }
  });


  document.querySelectorAll("[data-translate-placeholder]").forEach(el => {
    const key = el.getAttribute("data-translate-placeholder");
    if (translations[lang]?.[key] !== undefined) {
      el.placeholder = translations[lang][key];
    }
  });

  document.cookie = "lang=" + lang + "; path=/; max-age=" + (60 * 60 * 24 * 365);
  CURRENT_LANG_PAGE = lang;

}

// Инициализация
updateLanguage(currentLang);
updateCaptchaLang(currentLang);

function updateCaptchaLang(lang = 'ru') {

  const captchaLangs = ['en', 'ru', 'kk'];
  if (!captchaLangs.includes(lang)) {
    lang = 'ru';
  }

  const captchaData = {
    'en': {
      capI18nErrorLabel: 'Error',
      capI18nInitialState: 'I am not a robot',
      capI18nSolvedLabel: 'Verification passed',
      capI18nVerifyingLabel: 'Please wait...'
    },
    'ru': {
      capI18nErrorLabel: 'Ошибка',
      capI18nInitialState: 'Я не робот',
      capI18nSolvedLabel: 'Проверка пройдена',
      capI18nVerifyingLabel: 'Подождите...'
    },
    'kk': {
      capI18nErrorLabel: 'Qate',
      capI18nInitialState: 'Men robot emespin',
      capI18nSolvedLabel: 'Tekserý ótti',
      capI18nVerifyingLabel: 'Kúte turyńyz...'
    }
  };
  if(CAPTCHA_WIDGET){
    CAPTCHA_WIDGET.dataset.capI18nErrorLabel = captchaData[lang].capI18nErrorLabel;
    CAPTCHA_WIDGET.dataset.capI18nInitialState = captchaData[lang].capI18nInitialState;
    CAPTCHA_WIDGET.dataset.capI18nSolvedLabel = captchaData[lang].capI18nSolvedLabel;
    CAPTCHA_WIDGET.dataset.capI18nVerifyingLabel = captchaData[lang].capI18nVerifyingLabel;
  }

}


