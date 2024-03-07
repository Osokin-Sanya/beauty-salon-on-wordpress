<?php

// Helpers.
include_once(get_template_directory() . '/functions/helpers/helpers.php');

// Theme setup.
include_once(get_template_directory() . '/functions/theme/theme-setup.php');
include_once(get_template_directory() . '/functions/theme/theme-styles.php');
include_once(get_template_directory() . '/functions/theme/theme-scripts.php');

// Admin setup.
include_once(get_template_directory() . '/functions/admin/admin-setup.php');
include_once(get_template_directory() . '/functions/admin/admin-styles.php');
include_once(get_template_directory() . '/functions/admin/admin-scripts.php');

// Customizer.
include_once(get_template_directory() . '/inc/customizer/read-options.php');
include_once(get_template_directory() . '/inc/customizer/backend/class/class-fonts.php');
include_once(get_template_directory() . '/inc/customizer/frontend.php');
include_once(get_template_directory() . '/inc/customizer/backend.php');

// WP.
include_once(get_template_directory() . '/functions/wp/header-functions.php');
include_once(get_template_directory() . '/functions/wp/footer-functions.php');
include_once(get_template_directory() . '/functions/wp/actions.php');
include_once(get_template_directory() . '/functions/wp/filters.php');

// WC.
if (SHOPKEEPER_WOOCOMMERCE_IS_ACTIVE) {
	include_once(get_template_directory() . '/functions/plugins/wc/actions.php');
	include_once(get_template_directory() . '/functions/plugins/wc/filters.php');
	include_once(get_template_directory() . '/functions/plugins/wc/custom.php');
}

// Germanized & German Market.
if (SHOPKEEPER_GERMAN_MARKET_IS_ACTIVE || SHOPKEEPER_WOOCOMMERCE_GERMANIZED_IS_ACTIVE) {
	include_once(get_template_directory() . '/functions/plugins/germanized/functions.php');
}

// WPBakery.
if (SHOPKEEPER_WPBAKERY_IS_ACTIVE) {
	include_once(get_template_directory() . '/functions/plugins/wb/functions.php');
}

// YITH Wishlist
if (SHOPKEEPER_WISHLIST_IS_ACTIVE) {
	include_once(get_template_directory() . '/functions/plugins/wishlist/actions.php');
}

// WPML.
include_once(get_template_directory() . '/functions/plugins/wpml/functions.php');

// Load Custom Styles.
include_once(get_template_directory() . '/inc/custom-styles/init.php');

// Load Post meta template.
include_once(get_template_directory() . '/inc/templates/post-meta.php');

// Load Template Tags.
include_once(get_template_directory() . '/inc/templates/template-tags.php');

//Include Metaboxes.
include_once(get_template_directory() . '/inc/metaboxes/page.php');
include_once(get_template_directory() . '/inc/metaboxes/post.php');
include_once(get_template_directory() . '/inc/metaboxes/product.php');

//Quick View.
include_once(get_template_directory() . '/inc/woocommerce/quick_view.php');

//Product Layout.
include_once(get_template_directory() . '/inc/woocommerce/product-layout.php');


function shopkeeper_register_elementor_locations($elementor_theme_manager)
{
	$elementor_theme_manager->register_all_core_location();
}
add_action('elementor/theme/register_locations', 'shopkeeper_register_elementor_locations');



add_action("wp_enqueue_scripts", function () {
	wp_enqueue_script('my-js', get_template_directory_uri() . '/js/my-js.js', array(), null, true);
	wp_enqueue_style('my-css', get_template_directory_uri() . '/css/my-style.css');
});



///Обработчик вызова постов 
add_action('wp_ajax_my_custom_action', 'my_custom_ajax_handler');


function my_custom_ajax_handler()
{

	// Получение параметров запроса
	$post_type = $_POST['post_type']; // Тип поста (например, 'post', 'page')
	$offset = $_POST['offset']; // Смещение (для постраничной навигации)

	// Проверяем, есть ли данные в кэше
	$cache_key = 'my_custom_ajax_' . $post_type . '_' . $offset;
	$cached_posts = wp_cache_get($cache_key, 'my_custom_ajax');

	// Если данные есть в кэше, возвращаем их
	if ($cached_posts !== false) {
		wp_send_json_success(array('data' => $cached_posts));
		return;
	}

	// Запрос к базе данных
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => -1, // Получить все записи
		'offset' => $offset,
	);

	$query = new WP_Query($args);

	// Подготовка данных для ответа
	$posts = array();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();

			$categories = array();
			$post_categories = wp_get_post_categories(get_the_ID()); // Получить ID категорий

			foreach ($post_categories as $cat_id) {
				$category = get_category($cat_id); // Получить данные категории
				$categories[] = array(
					'id' => $category->term_id,
					'name' => $category->name,
					'slug' => $category->slug,
				);
			}

			$posts[] = array(
				'id' => get_the_ID(),
				'title' => get_the_title(),
				'content' => get_the_content(),
				'permalink' => get_the_permalink(),
				'pricePage' => get_field('price-page'),
				'servicesPage' => get_field('services-page'),
				'categories' => $categories, // Добавить массив категорий
			);
		}
	}

	// Сохраняем данные в кэше
	wp_cache_set($cache_key, $posts, 'my_custom_ajax', HOUR_IN_SECONDS);

	// Ответ
	wp_send_json_success(array('data' => $posts));
}




///Это штука для новых полей в посте
add_action('acf/init', 'my_acf_init');
function my_acf_init()
{
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(
			array(

				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'post', // Set the post type where you want the field to appear
						),
					),
				),
			)
		);
	}
}

/////
add_action('wp_ajax_my_custom_action2', 'my_custom_ajax_handler2');
function my_custom_ajax_handler2()
{
	// Получение параметров запроса
	$post_type = $_POST['post_type']; // Тип поста (например, 'post', 'page')
	$offset = $_POST['offset']; // Смещение (для постраничной навигации)

	// Проверяем, есть ли данные в кэше
	$cache_key = 'my_custom_posts_cache'; // Ключ кэша
	$posts = get_transient($cache_key); // Получаем данные из кэша

	if (false === $posts) { // Если данных в кэше нет, делаем запрос к базе данных
		// Запрос к базе данных
		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => -1, // Получить все записи
			'offset' => $offset,
			'tax_query' => array(
				array(
					'taxonomy' => 'post_tag',
					'field' => 'slug',
					'terms' => 'popular', // Условие для метки "popular"
				),
			),
		);

		$query = new WP_Query($args);

		// Подготовка данных для ответа
		$posts = array();

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();

				$categories = array();
				$post_categories = wp_get_post_categories(get_the_ID()); // Получить ID категорий

				foreach ($post_categories as $cat_id) {
					$category = get_category($cat_id); // Получить данные категории
					$categories[] = array(
						'id' => $category->term_id,
						'name' => $category->name,
						'slug' => $category->slug,
					);
				}

				$posts[] = array(
					'id' => get_the_ID(),
					'title' => get_the_title(),
					'content' => get_the_content(),
					'permalink' => get_the_permalink(),
					'price' => get_field('price'),
					'services' => get_field('services'),
					'images' => get_the_post_thumbnail(),
					'categories' => $categories, // Добавить массив категорий
				);
			}
		}

		// Сохраняем данные в кэше
		set_transient($cache_key, $posts, 1 * HOUR_IN_SECONDS); // 1 час (можете изменить время по вашему усмотрению)
	}

	// Ответ
	wp_send_json_success(array('data' => $posts));
}


 
 

/////








function shortcode_post_4843()
{
	$post = get_post(4843); // Получаем пост с ID 4843

	if ($post) {
		// Настраиваем параметры отображения
		$title = get_the_title($post); // Заголовок
		$permalink = get_permalink($post); // Ссылка на пост
		$excerpt = wp_trim_words($post->post_content, 20); // Обрезаем текст до 20 слов
		$thumbnail = get_the_post_thumbnail($post, 'thumbnail'); // Миниатюра

		// Выводим пост
		$output = '<div class="shortcode-post">';
		$output .= '<h2><a href="' . $permalink . '">' . esc_html($title) . '</a></h2>'; // Заголовок с ссылкой на пост
		$output .= $thumbnail;
		$output .= '<div class="test-class">' . $excerpt . '</div>';
		$output .= '<a class="shortcode-link" href="' . $permalink . '">Далее</a>'; // Ссылка "Далее"
		$output .= '</div>';

		return $output;
	} else {
		return 'Пост с ID 4843 не найден.';
	}
}
add_shortcode('post_4843', 'shortcode_post_4843');