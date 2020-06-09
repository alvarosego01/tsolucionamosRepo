


<?php
add_filter('use_block_editor_for_post_type', '__return_false', 100);

/**
 * @package     WordPress
 * @subpackage     Finance Business
 * @version        1.1.0
 *
 * Main Theme Functions File
 * Created by CMSMasters
 *
 */

// Current Theme Constants
if (!defined('CMSMS_SHORTNAME')) {
    define('CMSMS_SHORTNAME', 'finance-business');
}

if (!defined('CMSMS_FULLNAME')) {
    define('CMSMS_FULLNAME', 'Finance Business');
}

/*** START EDIT THEME PARAMETERS HERE ***/



// Theme Settings System Fonts List
if (!function_exists('cmsms_system_fonts_list')) {
    function cmsms_system_fonts_list()
    {
        $fonts = array(
            "Arial, Helvetica, 'Nimbus Sans L', sans-serif" => 'Arial',
            "Calibri, 'AppleGothic', 'MgOpen Modata', sans-serif" => 'Calibri',
            "'Trebuchet MS', Helvetica, Garuda, sans-serif" => 'Trebuchet MS',
            "'Comic Sans MS', Monaco, 'TSCu_Comic', cursive" => 'Comic Sans MS',
            "Georgia, Times, 'Century Schoolbook L', serif" => 'Georgia',
            "Verdana, Geneva, 'DejaVu Sans', sans-serif" => 'Verdana',
            "Tahoma, Geneva, Kalimati, sans-serif" => 'Tahoma',
            "'Lucida Sans Unicode', 'Lucida Grande', Garuda, sans-serif" => 'Lucida Sans',
            "'Times New Roman', Times, 'Nimbus Roman No9 L', serif" => 'Times New Roman',
            "'Courier New', Courier, 'Nimbus Mono L', monospace" => 'Courier New',
        );

        return $fonts;
    }
}

// Theme Settings Google Fonts List
if (!function_exists('cmsms_google_fonts_list')) {
    function cmsms_google_fonts_list()
    {
        $fonts = array(
            '' => __('None', 'cmsmasters'),
            'Roboto:300,300italic,400,400italic,500,500italic,700,700italic' => 'Roboto',
            'Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic' => 'Source Sans Pro',
            'Roboto+Condensed:400,400italic,700,700italic' => 'Roboto Condensed',
            'Roboto+Slab:400,300,700' => 'Roboto Slab',
            'Open+Sans:300,300italic,400,400italic,700,700italic' => 'Open Sans',
            'Open+Sans+Condensed:300,300italic,700' => 'Open Sans Condensed',
            'Droid+Sans:400,700' => 'Droid Sans',
            'Droid+Serif:400,400italic,700,700italic' => 'Droid Serif',
            'PT+Sans:400,400italic,700,700italic' => 'PT Sans',
            'PT+Sans+Caption:400,700' => 'PT Sans Caption',
            'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
            'PT+Serif:400,400italic,700,700italic' => 'PT Serif',
            'Ubuntu:400,400italic,700,700italic' => 'Ubuntu',
            'Ubuntu+Condensed' => 'Ubuntu Condensed',
            'Headland+One' => 'Headland One',
            'Lato:400,400italic,700,700italic' => 'Lato',
            'Cuprum:400,400italic,700,700italic' => 'Cuprum',
            'Oswald:300,400,700' => 'Oswald',
            'Yanone+Kaffeesatz:300,400,700' => 'Yanone Kaffeesatz',
            'Lobster' => 'Lobster',
            'Lobster+Two:400,400italic,700,700italic' => 'Lobster Two',
            'Questrial' => 'Questrial',
            'Raleway:300,400,500,600,700' => 'Raleway',
            'Dosis:300,400,500,700' => 'Dosis',
            'Cutive+Mono' => 'Cutive Mono',
            'Quicksand:300,400,700' => 'Quicksand',
            'Titillium+Web:300,300italic,400,400italic,600,600italic,700,700italic' => 'Titillium Web',
            'Montserrat:400,700' => 'Montserrat',
            'Cookie' => 'Cookie',
        );

        return $fonts;
    }
}

// Theme Settings Font Weights List
if (!function_exists('cmsms_font_weight_list')) {
    function cmsms_font_weight_list()
    {
        $list = array(
            'normal' => 'normal',
            '100' => '100',
            '200' => '200',
            '300' => '300',
            '400' => '400',
            '500' => '500',
            '600' => '600',
            '700' => '700',
            '800' => '800',
            '900' => '900',
            'bold' => 'bold',
            'bolder' => 'bolder',
            'lighter' => 'lighter',
        );

        return $list;
    }
}

// Theme Settings Font Styles List
if (!function_exists('cmsms_font_style_list')) {
    function cmsms_font_style_list()
    {
        $list = array(
            'normal' => 'normal',
            'italic' => 'italic',
            'oblique' => 'oblique',
            'inherit' => 'inherit',
        );

        return $list;
    }
}

// Theme Settings Text Transforms List
if (!function_exists('cmsms_text_transform_list')) {
    function cmsms_text_transform_list()
    {
        $list = array(
            'none' => 'none',
            'uppercase' => 'uppercase',
            'lowercase' => 'lowercase',
            'capitalize' => 'capitalize',
        );

        return $list;
    }
}

// Theme Settings Text Decorations List
if (!function_exists('cmsms_text_decoration_list')) {
    function cmsms_text_decoration_list()
    {
        $list = array(
            'none' => 'none',
            'underline' => 'underline',
            'overline' => 'overline',
            'line-through' => 'line-through',
        );

        return $list;
    }
}

// Theme Settings Custom Color Schemes
if (!function_exists('cmsms_custom_color_schemes_list')) {
    function cmsms_custom_color_schemes_list()
    {
        $list = array(
            'first' => __('Custom 1', 'cmsmasters'),
            'second' => __('Custom 2', 'cmsmasters'),
            'third' => __('Custom 3', 'cmsmasters'),
        );

        return $list;
    }
}

// WP Color Picker Palettes
if (!function_exists('cmsms_color_picker_palettes')) {
    function cmsms_color_picker_palettes()
    {
        $palettes = array(
            '#000000',
            '#ffffff',
            '#d43c18',
            '#5173a6',
            '#959595',
            '#c0c0c0',
            '#f4f4f4',
            '#e1e1e1',
        );

        return $palettes;
    }
}

/*** STOP EDIT THEME PARAMETERS HERE ***/

// Theme Plugin Support Constants
if (!defined('CMSMS_WOOCOMMERCE') && class_exists('woocommerce')) {
    define('CMSMS_WOOCOMMERCE', true);
} elseif (!defined('CMSMS_WOOCOMMERCE')) {
    define('CMSMS_WOOCOMMERCE', false);
}

if (!defined('CMSMS_EVENTS_CALENDAR') && class_exists('Tribe__Events__Main')) {
    define('CMSMS_EVENTS_CALENDAR', true);
} elseif (!defined('CMSMS_EVENTS_CALENDAR')) {
    define('CMSMS_EVENTS_CALENDAR', false);
}

if (!defined('CMSMS_PAYPALDONATIONS') && class_exists('PayPalDonations')) {
    define('CMSMS_PAYPALDONATIONS', true);
} elseif (!defined('CMSMS_PAYPALDONATIONS')) {
    define('CMSMS_PAYPALDONATIONS', false);
}

if (!defined('CMSMS_DONATIONS') && class_exists('Cmsms_Donations')) {
    define('CMSMS_DONATIONS', false);
} elseif (!defined('CMSMS_DONATIONS')) {
    define('CMSMS_DONATIONS', false);
}

if (!defined('CMSMS_TIMETABLE') && function_exists('timetable_events_init')) {
    define('CMSMS_TIMETABLE', true);
} elseif (!defined('CMSMS_TIMETABLE')) {
    define('CMSMS_TIMETABLE', false);
}

// Theme Image Thumbnails Size
if (!function_exists('cmsms_image_thumbnail_list')) {
    function cmsms_image_thumbnail_list()
    {
        $list = array(
            'small-thumb' => array(
                'width' => 55,
                'height' => 55,
                'crop' => true,
            ),
            'square-thumb' => array(
                'width' => 250,
                'height' => 250,
                'crop' => true,
                'title' => __('Square', 'cmsmasters'),
            ),
            'blog-masonry-thumb' => array(
                'width' => 580,
                'height' => 390,
                'crop' => true,
                'title' => __('Masonry Blog', 'cmsmasters'),
            ),
            'project-thumb' => array(
                'width' => 580,
                'height' => 460,
                'crop' => true,
                'title' => __('Project', 'cmsmasters'),
            ),
            'project-masonry-thumb' => array(
                'width' => 580,
                'height' => 9999,
                'title' => __('Masonry Project', 'cmsmasters'),
            ),
            'post-thumbnail' => array(
                'width' => 820,
                'height' => 390,
                'crop' => true,
                'title' => __('Featured', 'cmsmasters'),
            ),
            'masonry-thumb' => array(
                'width' => 820,
                'height' => 9999,
                'title' => __('Masonry', 'cmsmasters'),
            ),
            'full-thumb' => array(
                'width' => 1160,
                'height' => 700,
                'crop' => true,
                'title' => __('Full', 'cmsmasters'),
            ),
            'project-full-thumb' => array(
                'width' => 1160,
                'height' => 920,
                'crop' => true,
                'title' => __('Project Full', 'cmsmasters'),
            ),
            'full-masonry-thumb' => array(
                'width' => 1160,
                'height' => 9999,
                'title' => __('Masonry Full', 'cmsmasters'),
            ),
        );

        if (CMSMS_EVENTS_CALENDAR) {
            $list['event-thumb'] = array(
                'width' => 300,
                'height' => 300,
                'crop' => true,
                'title' => __('Event', 'cmsmasters'),
            );
        }

        return $list;
    }
}

// Theme Settings All Color Schemes List
if (!function_exists('cmsms_all_color_schemes_list')) {
    function cmsms_all_color_schemes_list()
    {
        $list = array(
            'default' => __('Default', 'cmsmasters'),
            'header' => __('Header', 'cmsmasters'),
            'header_top' => __('Header Top', 'cmsmasters'),
            'header_bottom' => __('Header Bottom', 'cmsmasters'),
            'footer' => __('Footer', 'cmsmasters'),
        );

        $out = array_merge($list, cmsms_custom_color_schemes_list());

        return $out;
    }
}

// Theme Settings Color Schemes List
if (!function_exists('cmsms_color_schemes_list')) {
    function cmsms_color_schemes_list()
    {
        $list = cmsms_all_color_schemes_list();

        unset($list['header']);

        unset($list['header_top']);

        unset($list['header_bottom']);

        $out = array_merge($list, cmsms_custom_color_schemes_list());

        return $out;
    }
}

// Theme Settings Color Schemes Default Colors
if (!function_exists('cmsms_color_schemes_defaults')) {
    function cmsms_color_schemes_defaults()
    {
        $list = array(
            'default' => array( // content default color scheme
                'color' => '#616161',
                'link' => '#9c9c9c',
                'hover' => '#51c5eb',
                'heading' => '#36444e',
                'bg' => '#ffffff',
                'alternate' => '#fbfbfb',
                'border' => '#dddddd',
                'custom' => '#ffffff',
            ),
            'header' => array( // Header color scheme
                'color' => '#616161',
                'block_bg' => 'rgba(255,255,255,0)',
                'link' => '#36444e',
                'hover' => '#51c5eb',
                'subtitle' => '#aaaaaa',
                'bg' => '#f9f9f9',
                'hover_bg' => '#ffffff',
                'border' => '#dddddd',
                'rollover_border' => '#dddddd',
                'dropdown_link' => 'rgba(255,255,255,0.4)',
                'dropdown_hover' => '#ffffff',
                'dropdown_subtitle' => '#9e9e9e',
                'dropdown_bg' => '#36444e',
                'dropdown_border' => 'rgba(255,255,255,0.1)',
                'dropdown_hover_bd' => '#51c5eb',
                'navi_scrolled_bg' => '#ffffff',
            ),
            'header_top' => array( // Header Top color scheme
                'color' => 'rgba(255,255,255,0.4)',
                'bg' => '#36444e',
                'link' => 'rgba(255,255,255,0.7)',
                'hover' => '#ffffff',
                'border' => 'rgba(251,251,251,0)',
                'dropdown_link' => 'rgba(255,255,255,0.7)',
                'dropdown_hover' => '#ffffff',
                'dropdown_bg' => '#36444e',
                'dropdown_border' => 'rgba(255,255,255,0.1)',
            ),
            'header_bottom' => array( // Header Bottom color scheme
                'color' => '#616161',
                'block_bg' => 'rgba(255,255,255,0)',
                'link' => '#36444e',
                'hover' => '#51c5eb',
                'subtitle' => '#aaaaaa',
                'bg' => '#f9f9f9',
                'hover_bg' => '#ffffff',
                'border' => '#dddddd',
                'rollover_border' => '#dddddd',
                'dropdown_link' => 'rgba(255,255,255,0.4)',
                'dropdown_hover' => '#ffffff',
                'dropdown_subtitle' => '#9e9e9e',
                'dropdown_bg' => '#36444e',
                'dropdown_border' => 'rgba(255,255,255,0.1)',
                'dropdown_hover_bd' => '#51c5eb',
                'navi_scrolled_bg' => '#ffffff',
            ),
            'footer' => array( // Footer color scheme
                'color' => '#161515',
                'link' => '#9c9c9c',
                'hover' => '#2cced6',
                'heading' => '#3d3d3d',
                'bg' => '#ffffff',
                'alternate' => '#fbfbfb',
                'border' => '#bbbbbb',
                'custom' => '#ffffff',
            ),
            'first' => array( // custom color scheme 1
                'color' => 'rgba(255,255,255,0.6)',
                'link' => '#ffffff',
                'hover' => '#ff5f00',
                'heading' => '#ffffff',
                'bg' => '#36444e',
                'alternate' => '#414141',
                'border' => '#52616b',
                'custom' => '#ffffff',
            ),
            'second' => array( // custom color scheme 2
                'color' => '#616161',
                'link' => '#9c9c9c',
                'hover' => '#51c5eb',
                'heading' => '#36444e',
                'bg' => 'rgba(249,249,249,0)',
                'alternate' => 'rgba(255,255,255,0)',
                'border' => '#dddddd',
                'custom' => '#f2f2f2',
            ),
            'third' => array( // custom color scheme 3
                'color' => '#9c9c9c',
                'link' => '#838383',
                'hover' => '#5173a6',
                'heading' => '#36444e',
                'bg' => '#fbfbfb',
                'alternate' => '#ffffff',
                'border' => '#c4c4c4',
                'custom' => '#ffffff',
            ),
        );

        return $list;
    }
}

// CMSMasters Framework Directories Constants
if (!defined('CMSMS_FRAMEWORK')) {
    define('CMSMS_FRAMEWORK', get_template_directory() . '/framework');
}

if (!defined('CMSMS_ADMIN')) {
    define('CMSMS_ADMIN', CMSMS_FRAMEWORK . '/admin');
}

if (!defined('CMSMS_SETTINGS')) {
    define('CMSMS_SETTINGS', CMSMS_ADMIN . '/settings');
}

if (!defined('CMSMS_OPTIONS')) {
    define('CMSMS_OPTIONS', CMSMS_ADMIN . '/options');
}

if (!defined('CMSMS_ADMIN_INC')) {
    define('CMSMS_ADMIN_INC', CMSMS_ADMIN . '/inc');
}

if (!defined('CMSMS_CLASS')) {
    define('CMSMS_CLASS', CMSMS_FRAMEWORK . '/class');
}

if (!defined('CMSMS_FUNCTION')) {
    define('CMSMS_FUNCTION', CMSMS_FRAMEWORK . '/function');
}

if (!defined('CMSMS_COMPOSER')) {
    define('CMSMS_COMPOSER', get_template_directory() . '/cmsms-c-c');
}

// Load Framework Parts
require_once CMSMS_SETTINGS . '/cmsms-theme-settings.php';

require_once CMSMS_OPTIONS . '/cmsms-theme-options.php';

require_once CMSMS_ADMIN_INC . '/admin-scripts.php';

require_once CMSMS_ADMIN_INC . '/plugin-activator.php';

require_once CMSMS_CLASS . '/likes-posttype.php';

require_once CMSMS_CLASS . '/twitteroauth.php';

require_once CMSMS_CLASS . '/widgets.php';

require_once CMSMS_FUNCTION . '/breadcrumbs.php';

require_once CMSMS_FUNCTION . '/likes.php';

require_once CMSMS_FUNCTION . '/pagination.php';

require_once CMSMS_FUNCTION . '/single-comment.php';

require_once CMSMS_FUNCTION . '/theme-functions.php';

require_once CMSMS_FUNCTION . '/theme-fonts.php';

require_once CMSMS_FUNCTION . '/theme-colors-primary.php';

require_once CMSMS_FUNCTION . '/theme-colors-secondary.php';

require_once CMSMS_FUNCTION . '/template-functions.php';

require_once CMSMS_FUNCTION . '/template-functions-post.php';

require_once CMSMS_FUNCTION . '/template-functions-project.php';

require_once CMSMS_FUNCTION . '/template-functions-profile.php';

require_once CMSMS_FUNCTION . '/template-functions-shortcodes.php';

require_once CMSMS_FUNCTION . '/template-functions-widgets.php';

if (class_exists('Cmsms_Content_Composer')) {
    require_once CMSMS_COMPOSER . '/filters/cmsms-c-c-atts-filters.php';
}

// Woocommerce functions
if (CMSMS_WOOCOMMERCE) {
    require_once get_template_directory() . '/woocommerce/cmsms-woo-functions.php';
}

//include the WPML installer in the theme
include get_template_directory() . '/installer/loader.php';

WP_Installer_Setup($wp_installer_instance,
    array(
        'plugins_install_tab' => 1, // optional, default value: 1
        'affiliate_id' => '46196', // optional, default value: empty
        'affiliate_key' => 'N9YfTTsunQtc', // optional, default value: empty
        'src_name' => 'Finance Business', // optional, default value: empty, needed for coupons
        'src_author' => 'Cmsmasters', // optional, default value: empty, needed for coupons
        'repositories_include' => array('wpml'), // optional, default to empty (show all)
    )
);

// Events functions
if (CMSMS_EVENTS_CALENDAR) {
    require_once get_template_directory() . '/tribe-events/cmsms-events-functions.php';
}

// Load Theme Local File
if (!function_exists('cmsms_load_theme_textdomain')) {
    function cmsms_load_theme_textdomain()
    {
        $locale = get_locale();

        load_theme_textdomain('cmsmasters', CMSMS_FRAMEWORK . '/languages');

        $locale_file = CMSMS_FRAMEWORK . '/languages/' . $locale . '.php';

        if (is_readable($locale_file)) {
            require_once $locale_file;
        }
    }
}

// Load Theme Local File Action
if (!has_action('after_setup_theme', 'cmsms_load_theme_textdomain')) {
    add_action('after_setup_theme', 'cmsms_load_theme_textdomain');
}

// Framework Activation & Data Import
if (!function_exists('cmsms_theme_activation')) {
    function cmsms_theme_activation()
    {
        if (get_option('cmsms_active_theme') != CMSMS_SHORTNAME) {
            add_option('cmsms_active_theme', CMSMS_SHORTNAME, '', 'yes');

            cmsms_add_global_options();

            cmsms_regenerate_styles();

            cmsms_add_global_icons();

            flush_rewrite_rules();

            wp_redirect(admin_url('admin.php?page=cmsms-settings&upgraded=true'));
        }
    }
}

add_action('after_switch_theme', 'cmsms_theme_activation');

// Framework Deactivation
if (!function_exists('cmsms_theme_deactivation')) {
    function cmsms_theme_deactivation()
    {
        delete_option('cmsms_active_theme');
    }
}

// Framework Deactivation Action
if (!has_action('switch_theme', 'cmsms_theme_deactivation')) {
    add_action('switch_theme', 'cmsms_theme_deactivation');
}

// TRIA CUSTOM CODE -- 20160520
// Filter wp_nav_menu() to add additional links and other output
// Show only other language in language switcher
// Use the new filter: https://wpml.org/wpml-hook/wpml_active_languages/

function new_nav_menu_items($items, $args)
{
    // uncomment this to find your theme's menu location
    //echo "args:<pre>"; print_r($args); echo "</pre>";

    // get languages
    $languages = apply_filters('wpml_active_languages', null, 'skip_missing=0');

    // add $args->theme_location == 'primary-menu' in the conditional if we want to specify the menu location.

    if ($languages) {

        if (!empty($languages)) {

            foreach ($languages as $l) {
                if (!$l['active']) {
                    $items = '<a href="' . $l['url'] . '" class="lang_sel_sel">' . $l['native_name'] . '</a>';
                }
            }
        }
    }

    return $items;
}

@ini_set( 'upload_max_size' , '256M' );
@ini_set( 'post_max_size', '256M');
@ini_set( 'max_execution_time', '400' );



function customAdminScripts() {
    wp_register_style( 'customCssAdmin', get_template_directory_uri() . '/css/customAdmin.css', array(), rand(), 'all');
    wp_enqueue_style( 'customCssAdmin' );
}
add_action( 'admin_enqueue_scripts', 'customAdminScripts' );

