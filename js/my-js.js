/// Послуги на странице послуги
jQuery(document).ready(function ($) {
  // Получаем ссылку на контейнер постов
  const postContainer = jQuery(".post-service__items");
  // Получаем ссылку на индикатор загрузки
  const loading = jQuery(".loading-indicator");
  // Создаем пустой массив для хранения постов
  let posts = [];

  // Создаем новый XMLHttpRequest объект
  const xhr = new XMLHttpRequest();

  // Настраиваем запрос к WordPress admin-ajax.php
  xhr.open("POST", `http://dr-reva/wp-admin/admin-ajax.php`, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  // Функция, которая будет вызвана после завершения запроса
  xhr.onload = function () {
    // Проверяем успешность запроса
    if (xhr.status === 200) {
      // Получаем ответ и парсим его из JSON формата
      const response = JSON.parse(xhr.responseText);
      // Присваиваем массиву постов данные из ответа
      posts = response.data.data; // Обращаемся к свойству data только один раз

      // Скрываем индикатор загрузки и вызываем функцию загрузки постов с категорией "Обличчя"
      loading.style = "display: none;";
      loadPostsPageJs("Обличчя");
    }
  };

  // Отправляем запрос на сервер WordPress
  xhr.send("action=my_custom_action");

  // Функция загрузки постов
  function loadPostsPageJs(category = "Обличчя") {
    // Очищаем контейнер перед добавлением нового контента
    postContainer.empty();
    // Фильтруем посты по выбранной категории
    let filterPosts = posts.filter(function (item) {
      return item.categories[0].name == category;
    });
    // Добавляем отфильтрованные посты в контейнер
    postContainer.append(
      filterPosts.map((item) => {
        return `<div class="post-service__item">
      
      <div class="item_block">
      <a class="item_link" href="${item.permalink}"> 
      <div class="item_text">${item.title}</div>
      </a>
           <div class="item_services">${
             item.servicesPage != null ? item.servicesPage : " "
           }</div>
      </div>
      <div class="item_pice">${
        item.pricePage != null ? item.pricePage : " "
      }</div>
      
      <a class="item_link" href="${item.permalink}"> 
      <p>Детальніше</p>
      <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M11 10.5H1M11 20.5V10.5V20.5ZM11 10.5V0.5V10.5ZM11 10.5H21H11Z" stroke="#ECD8BD" stroke-width="2" stroke-linecap="round"/>
      </svg>
      </a>
           
      </div>`;
      })
    );
  }

  // Обработчик клика для кнопок категорий на странице послуги
  jQuery(".button-category-page").click(function () {
    // Получаем выбранную категорию из атрибута data-category кнопки и вызываем функцию загрузки постов с этой категорией
    var category = jQuery(this).data("category");
    loadPostsPageJs(category);
  });
  // Обработчик клика для кнопок категорий на странице послуги
  $(".button-category-page").click(function () {
    // Удаляем класс активности со всех кнопок категорий и добавляем его к текущей кнопке
    $(".button-category-page").removeClass("active-button-category-page");
    $(this).addClass("active-button-category-page");
  });
});

/// Послуги (популярные)на странице главная
jQuery(document).ready(function ($) {
  // Получаем ссылку на контейнер постов
  const postContainer = jQuery(".post-container");
  // Получаем ссылку на индикатор загрузки
  const loading = jQuery(".loading-indicator");
  // Создаем пустой массив для хранения постов
  let posts = [];

  // Создаем новый XMLHttpRequest объект
  const xhr = new XMLHttpRequest();

  // Настраиваем запрос к WordPress admin-ajax.php
  xhr.open("POST", `http://dr-reva/wp-admin/admin-ajax.php`, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  // Функция, которая будет вызвана после завершения запроса
  xhr.onload = function () {
    // Проверяем успешность запроса
    if (xhr.status === 200) {
      // Получаем ответ и парсим его из JSON формата
      const response = JSON.parse(xhr.responseText);
      // Присваиваем массиву постов данные из ответа
      posts = response.data.data; // Обращаемся к свойству data только один раз

      // Скрываем индикатор загрузки и вызываем функцию загрузки постов с категорией "Обличчя"
      loading.style = "display: none;";
      loadPostsPageJs("Обличчя");
    }
  };

  // Отправляем запрос на сервер WordPress
  xhr.send("action=my_custom_action2");

  // Функция загрузки постов
  function loadPostsPageJs(category = "Обличчя") {
    // Очищаем контейнер перед добавлением нового контента
    postContainer.empty();
    // Фильтруем посты по выбранной категории
    let filterPosts = posts.filter(function (item) {
      return item.categories[0].name == category;
    });
    // Добавляем отфильтрованные посты в контейнер
    postContainer.append(
      filterPosts.map((item) => {
        return `<div class="post-item">
        <a href="${item.permalink}">
        ${item.images}
        </a>
        <h3><a href="${item.permalink}">
        ${item.title}
          </a></h3>
        <span class='categories'>
          ${item.price != null ? item.price : " "}
          ${item.services != null ? item.services : " "}
          </span>
        </div>`;
      })
    );
  }

  // Обработчик клика для кнопок категорий на странице послуги
  jQuery(".button-category").click(function () {
    // Получаем выбранную категорию из атрибута data-category кнопки и вызываем функцию загрузки постов с этой категорией
    var category = jQuery(this).data("category");
    loadPostsPageJs(category);
  });
  // Обработчик клика для кнопок категорий на странице главная
  $(".button-category").click(function () {
    // Удаляем класс активности со всех кнопок категорий и добавляем его к текущей кнопке
    $(".button-category").removeClass("active-button-category");
    $(this).addClass("active-button-category");
  });
});

jQuery(document).ready(function ($) {
  $(".button-post-services").click(function () {
    // Убираем класс у всех кнопок и добавляем его только нажатой кнопке
    $(".button-post-services").removeClass("active-button-post-services");
    $(this).addClass("active-button-post-services");

    // Получаем атрибут "post" нажатой кнопки
    var buttonAtr = $(this).attr("post");

    // Убираем класс у всех элементов с классом ".post-info-services"
    $(".post-info-services").removeClass("active-info-post-services");

    // Добавляем класс "active-info-post-services" только элементам, у которых атрибут "post" совпадает с атрибутом нажатой кнопки
    $(".post-info-services[post='" + buttonAtr + "']").addClass(
      "active-info-post-services"
    );
  });
});
