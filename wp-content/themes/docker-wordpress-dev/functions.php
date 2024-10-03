<?php

namespace IdeasOnPurpose;

$autoloader = require __DIR__ . '/vendor/autoload.php';

if (!defined('VERSION')) {
    define('VERSION', defined('WP_DEBUG') ? time() : wp_get_theme()->get('Version'));
}

new ThemeInit();

/**
 * Load Scripts from the webpack generated dist/dependency-manifest.json file
 */
new ThemeInit\Manifest();

/**
 * Initialize IdeasOnPurpose\WP\GoogleAnalytics
 */
$client_ga_id = 'UA-2565788-3';
$iop_dev_ga = 'UA-2565788-3';
new WP\GoogleAnalytics($client_ga_id, $iop_dev_ga);

/**
 * Initialize our SVG Library for all SVGs in ./dist/images/svg
 */
new WP\SVG(__DIR__ . '/dist/images/svg');

/**
 * Register Custom Widgets
 */
// new Widgets\NAME();

/**
 * Register Custom Shortcodes
 */
// new Shortcodes\NAME();

/**
 * Enable TaxonomyCountColumn so post-counts in Taxonomy listings are accurate to the displayed  post_type
 */
new WP\TaxonomyCountColumn();

/**
 * Add Search redirect and length limiter
 */
new WP\Search();

/**
 * Enable assorted WordPress features
 */
add_action('after_setup_theme', function () {
    // Add excerpts to pages
    add_post_type_support('page', 'excerpt');

    // Theme features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'gallery', 'caption']);

    // Gutenberg settings
    add_theme_support('editor-styles'); // https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles
    add_theme_support('align-wide'); // https://wordpress.org/gutenberg/handbook/extensibility/theme-support/#wide-alignment
    add_theme_support('disable-custom-colors'); // https: //wordpress.org/gutenberg/handbook/extensibility/theme-support/#disabling-custom-colors-in-block-color-palettes
});

/**
 * Register Sidebars and disable default widgets
 */
add_action('widgets_init', function () {
    // register_sidebars();

    unregister_widget('WP_Nav_Menu_Widget');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Custom_HTML');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Media_Audio');
    unregister_widget('WP_Widget_Media_Gallery');
    unregister_widget('WP_Widget_Media_Video');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Tag_Cloud');
});

/**
 * Register Custom Menus
 */
add_action('after_setup_theme', function () {
    register_nav_menus([
        'menu-main' => 'Main Menu',
        'menu-footer' => 'Footer Menu',
    ]);
});

/**
 * Define additional image sizes
 * Image sizes are generated from an array of size objects
 * Each size maps like this:
 *   name:     (string)   Internal image size name (slug)
 *   dims:     (array)    Array of two integers: [w, h]
 *   display:  (string)   Show in WP Menus using this name
 *   crop:     (array|boolean)  if not false, hard-crop the resulting image
 *
 * If display_name is specified, the image size will appear in authoring menus
 */
$image_sizes = [
    ['name' => '1k', 'dims' => [1024, 1024], 'display' => '1k - 1024px'],
    ['name' => '2k', 'dims' => [2048, 2048], 'display' => '2k - 2048px'],
    ['name' => '4k', 'dims' => [3840, 3840], 'display' => '4k - 3840px'],
];
new ImageSize($image_sizes);

/**
 * ACF Options Pages
 */
if (function_exists('acf_add_options_page')) {
    $acf_options = acf_add_options_page([
        'page_title' => 'Global Theme Options',
        'menu_title' => 'Site Options',
        'position' => 35,
        'icon_url' =>
            'data:image/svg+xml;base64,' .
            base64_encode(
                '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M20 10C20 11.9778 19.4135 13.9112 18.3147 15.5557C17.2159 17.2002 15.6541 18.4819 13.8268 19.2388C11.9996 19.9957 9.98891 20.1937 8.0491 19.8078C6.10929 19.422 4.32746 18.4696 2.92894 17.0711C1.53041 15.6725 0.578004 13.8907 0.192152 11.9509C-0.1937 10.0111 0.00433281 8.00043 0.761209 6.17317C1.51809 4.34591 2.79981 2.78412 4.4443 1.6853C6.08879 0.58649 8.02219 0 10 0C12.6522 0 15.1957 1.05357 17.0711 2.92893C18.9464 4.8043 20 7.34783 20 10ZM14.6719 6.32969C14.4225 6.32998 14.174 6.35881 13.9313 6.41562C13.4722 6.52472 13.0429 6.7338 12.6739 7.02793C12.305 7.32205 12.0055 7.69399 11.7969 8.11719C11.5576 7.62573 11.1951 7.20468 10.7446 6.89509C10.2942 6.58551 9.77114 6.39796 9.22659 6.35074C8.68204 6.30352 8.13455 6.39826 7.63752 6.62569C7.14049 6.85312 6.71089 7.2055 6.39063 7.64844C6.2983 7.2433 6.05947 6.88659 5.72006 6.64687C5.38065 6.40716 4.96461 6.30136 4.55192 6.34982C4.13923 6.39829 3.75902 6.59758 3.48437 6.9094C3.20973 7.22122 3.06003 7.62356 3.06406 8.03906C3.06406 8.96094 3.06406 9.31094 3.06406 9.8V11.0234C3.0599 11.4523 3.21463 11.8675 3.49844 12.1891C3.78402 12.4978 4.17348 12.6903 4.59219 12.7297C4.65469 12.7297 4.71876 12.7391 4.78126 12.7391C5.1907 12.7448 5.58711 12.5952 5.89063 12.3203C6.14814 12.0853 6.32432 11.7745 6.39376 11.4328C6.4888 11.5651 6.59324 11.6905 6.70626 11.8078C7.02404 12.1299 7.40742 12.3798 7.83036 12.5406C8.2533 12.7014 8.70588 12.7693 9.15738 12.7396C9.60888 12.71 10.0487 12.5835 10.447 12.3688C10.8453 12.1541 11.1927 11.8562 11.4656 11.4953V13.4984C11.4656 13.9456 11.6433 14.3744 11.9594 14.6906C12.2756 15.0067 12.7044 15.1844 13.1516 15.1844C13.5987 15.1844 14.0275 15.0067 14.3437 14.6906C14.6599 14.3744 14.8375 13.9456 14.8375 13.4984V12.7375C15.6668 12.6861 16.4437 12.3148 17.0046 11.7018C17.5655 11.0888 17.8665 10.282 17.8443 9.4514C17.822 8.62082 17.4781 7.83132 16.8852 7.24928C16.2922 6.66724 15.4965 6.33811 14.6656 6.33125L14.6719 6.32969ZM12.4844 11.8687L12.5406 11.9234C12.3968 11.9811 12.2617 12.0584 12.1391 12.1531V11.4859C12.2225 11.5961 12.3134 11.7005 12.4109 11.7984L12.4469 11.8344L12.4719 11.8562L12.4844 11.8687ZM12.2828 10.325V10.3109C12.275 10.2891 12.2672 10.2672 12.2609 10.2437C12.1668 9.91831 12.1387 9.57735 12.1782 9.24089C12.2177 8.90443 12.3241 8.57925 12.491 8.28448C12.658 7.98971 12.8822 7.73128 13.1505 7.52438C13.4187 7.31749 13.7256 7.16629 14.0531 7.07969C14.2466 7.02663 14.4463 6.99982 14.6469 7H14.6578C15.0122 6.99917 15.3628 7.07259 15.6871 7.21551C16.0114 7.35844 16.3021 7.5677 16.5406 7.82983C16.7791 8.09195 16.96 8.40112 17.0718 8.73743C17.1835 9.07373 17.2236 9.42972 17.1894 9.78244C17.1552 10.1352 17.0474 10.4768 16.8732 10.7854C16.6989 11.0939 16.4619 11.3626 16.1775 11.574C15.8931 11.7854 15.5675 11.9349 15.2218 12.0129C14.8761 12.0908 14.5179 12.0955 14.1703 12.0266C13.6903 11.9254 13.2507 11.6852 12.9063 11.3359C12.6682 11.0968 12.4792 10.8133 12.35 10.5016C12.3156 10.4437 12.2953 10.3844 12.2766 10.325H12.2828ZM13.1609 12.4641C13.2644 12.4643 13.3672 12.4806 13.4656 12.5125L13.5797 12.5562C13.7593 12.6387 13.9116 12.7707 14.0188 12.9368C14.1259 13.1029 14.1833 13.2961 14.1844 13.4937C14.1856 13.6297 14.1598 13.7646 14.1082 13.8904C14.0567 14.0162 13.9806 14.1305 13.8844 14.2266C13.7899 14.3224 13.6773 14.3985 13.5531 14.4503C13.4288 14.5021 13.2955 14.5285 13.1609 14.5281C12.8866 14.5281 12.6235 14.4191 12.4295 14.2252C12.2355 14.0312 12.1266 13.7681 12.1266 13.4937C12.1266 13.2194 12.2355 12.9563 12.4295 12.7623C12.6235 12.5684 12.8866 12.4594 13.1609 12.4594V12.4641ZM11.4688 9.53594C11.4708 10.206 11.2069 10.8496 10.735 11.3253C10.2631 11.8011 9.62167 12.0701 8.95157 12.0734C8.62098 12.0731 8.29374 12.0072 7.98879 11.8795C7.68384 11.7518 7.40725 11.565 7.17501 11.3297C6.70929 10.8665 6.44296 10.2396 6.43282 9.58281V9.49219C6.45389 8.83469 6.73135 8.21153 7.20588 7.75594C7.68041 7.30034 8.31435 7.04848 8.97216 7.05419C9.62996 7.05991 10.2594 7.32275 10.726 7.78652C11.1925 8.25028 11.4591 8.87817 11.4688 9.53594ZM4.75 9.06719C4.5451 9.06873 4.34435 9.00939 4.17322 8.89667C4.00209 8.78396 3.8683 8.62296 3.78881 8.43409C3.70933 8.24523 3.68772 8.03701 3.72675 7.83584C3.76577 7.63468 3.86367 7.44965 4.00801 7.3042C4.15236 7.15876 4.33665 7.05947 4.53751 7.01892C4.73837 6.97837 4.94674 6.99839 5.1362 7.07645C5.32567 7.1545 5.48768 7.28707 5.60169 7.45734C5.71569 7.62761 5.77656 7.8279 5.77657 8.03281C5.77677 8.16824 5.7503 8.30237 5.69867 8.42757C5.64703 8.55277 5.57124 8.66657 5.47563 8.76247C5.38001 8.85838 5.26644 8.93451 5.1414 8.98653C5.01636 9.03854 4.88231 9.06542 4.74688 9.06562L4.75 9.06719ZM5.77969 9.37969V11.0094C5.78482 11.1605 5.75796 11.311 5.70087 11.451C5.64379 11.591 5.55777 11.7174 5.44844 11.8219C5.34273 11.9151 5.2184 11.9847 5.0837 12.0262C4.94901 12.0677 4.80703 12.0801 4.66719 12.0625C4.40654 12.0414 4.16394 11.921 3.98949 11.7262C3.81503 11.5314 3.72203 11.277 3.72969 11.0156V9.37812C4.02338 9.60728 4.3853 9.73159 4.75782 9.73125C5.12819 9.72889 5.48762 9.60543 5.78125 9.37969H5.77969Z" fill="black"/>
                 </svg>',
            ),
    ]);

    acf_add_options_sub_page([
        'page_title' => 'Header Options',
        'menu_title' => 'Header',
        'parent_slug' => $acf_options['menu_slug'],
    ]);


    acf_add_options_sub_page([
        'page_title' => 'Footer Options',
        'menu_title' => 'Footer',
        'parent_slug' => $acf_options['menu_slug'],
    ]);
}

