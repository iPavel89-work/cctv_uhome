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


