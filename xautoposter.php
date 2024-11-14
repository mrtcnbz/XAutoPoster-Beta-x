<?php
/**
 * Plugin Name: XAutoPoster
 * Description: WordPress içeriklerinizi otomatik olarak X (Twitter) hesabınızda paylaşın
 * Version: 1.0.0
 * Author: Murat Canbaz
 * Text Domain: xautoposter
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('XAUTOPOSTER_VERSION', '1.0.0');
define('XAUTOPOSTER_FILE', __FILE__);
define('XAUTOPOSTER_PATH', plugin_dir_path(__FILE__));
define('XAUTOPOSTER_URL', plugin_dir_url(__FILE__));
define('XAUTOPOSTER_BASENAME', plugin_basename(__FILE__));

// Otomatik yükleyiciyi yükle
require_once XAUTOPOSTER_PATH . 'src/autoload.php';
XAutoPoster\Autoloader::register();

// Initialize the plugin
function xautoposter_init() {
    try {
        return \XAutoPoster\Plugin::getInstance();
    } catch (Exception $e) {
        add_action('admin_notices', function() use ($e) {
            printf(
                '<div class="error"><p>%s</p></div>',
                esc_html(sprintf('XAutoPoster Hata: %s', $e->getMessage()))
            );
        });
        return null;
    }
}

// Add admin menu link
function xautoposter_admin_menu() {
    add_menu_page(
        __('XAutoPoster', 'xautoposter'),
        __('XAutoPoster', 'xautoposter'),
        'manage_options',
        'xautoposter',
        'xautoposter_admin_page',
        'dashicons-twitter',
        30
    );
}

// Admin page callback
function xautoposter_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Bu sayfaya erişim yetkiniz bulunmuyor.', 'xautoposter'));
    }
    require_once XAUTOPOSTER_PATH . 'templates/admin-page.php';
}

// Register hooks
add_action('plugins_loaded', 'xautoposter_init');
add_action('admin_menu', 'xautoposter_admin_menu');