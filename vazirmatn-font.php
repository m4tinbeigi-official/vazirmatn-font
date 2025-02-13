<?PHP
/**
 * Plugin Name: Vazirmatn Font
 * Description: ساخته شده توسط ریک سانچز برای زنده نگهداشتن یاد صابر راستی کردار
 * Version: 1.0.1
 * Author: Rick Sanchez
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: vazirmatn-font
 * Domain Path: /languages
 * Requires PHP: 7.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants.
define('VAZIRMATN_FONT_PLUGIN_VERSION', '1.0.1');
define('VAZIRMATN_FONT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VAZIRMATN_FONT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load text domain for translations.
function vazirmatn_font_plugin_load_textdomain() {
    load_plugin_textdomain('vazirmatn-font', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'vazirmatn_font_plugin_load_textdomain');

// Enqueue styles for frontend, admin, and login pages.
function vazirmatn_font_plugin_enqueue_styles() {
    $options = get_option('vazirmatn_font_plugin_options', array('enabled' => 1));

    if (isset($options['enabled']) && $options['enabled']) {
        $css_file = is_admin() ? 'vazirmatn-font-dashboard.css' : 'vazirmatn-font.css';
        wp_enqueue_style('vazirmatn-font-plugin-style', VAZIRMATN_FONT_PLUGIN_URL . 'assets/css/' . $css_file, array(), VAZIRMATN_FONT_PLUGIN_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'vazirmatn_font_plugin_enqueue_styles');
add_action('wp_enqueue_scripts', 'vazirmatn_font_plugin_enqueue_styles');
add_action('login_enqueue_scripts', 'vazirmatn_font_plugin_enqueue_styles');

// Add settings page for the plugin.
function vazirmatn_font_plugin_add_settings_page() {
    add_options_page(
    __('تنظیمات فونت Vazirmatn', 'vazirmatn-font'),
    __('فونت Vazirmatn', 'vazirmatn-font'),
    'manage_options',
    'vazirmatn-font-plugin-settings',
    'vazirmatn_font_plugin_render_settings_page'
);
}
add_action('admin_menu', 'vazirmatn_font_plugin_add_settings_page');

// Render the settings page.
function vazirmatn_font_plugin_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('تنظیمات فونت Vazirmatn', 'vazirmatn-font-plugin'); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('vazirmatn_font_plugin_options');
            do_settings_sections('vazirmatn-font-plugin-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register plugin settings.
function vazirmatn_font_plugin_register_settings() {
    register_setting('vazirmatn_font_plugin_options', 'vazirmatn_font_plugin_options', 'vazirmatn_font_plugin_options_validate');
    add_settings_section('vazirmatn_font_plugin_main', __('تنظیمات اصلی', 'vazirmatn-font-plugin'), 'vazirmatn_font_plugin_section_text', 'vazirmatn-font-plugin-settings');
    add_settings_field('vazirmatn_font_plugin_field', __('فعال کردن فونت Vazirmatn', 'vazirmatn-font-plugin'), 'vazirmatn_font_plugin_field_input', 'vazirmatn-font-plugin-settings', 'vazirmatn_font_plugin_main');
}
add_action('admin_init', 'vazirmatn_font_plugin_register_settings');

// Section text for settings.
function vazirmatn_font_plugin_section_text() {
    echo '<p>' . __('تنظیمات مربوط به فونت Vazirmatn را در این بخش انجام دهید.', 'vazirmatn-font-plugin') . '</p>';
}

// Input field for settings.
function vazirmatn_font_plugin_field_input() {
    $options = get_option('vazirmatn_font_plugin_options', array('enabled' => 1));
    echo "<input id='vazirmatn_font_plugin_field' name='vazirmatn_font_plugin_options[enabled]' type='checkbox' value='1' " . checked(1, $options['enabled'], false) . " />";
}

// Validate settings input.
function vazirmatn_font_plugin_options_validate($input) {
    $newinput['enabled'] = isset($input['enabled']) ? 1 : 0;
    return $newinput;
}