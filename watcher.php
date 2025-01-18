<?php

/**
 * @since             1.0.0
 * @package           WPYoostart
 * @wordpress-plugin
 * Plugin Name:       Watcher
 * Description:       Surveille les modifications de fichiers
 * Version:           1.0.0
 * Author:            Ahmed Mze
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       watcher
 * Domain Path:       /resources/i18n
 * Requires PHP:      8.3.0
 * Requires WP:       5.5.0
 * Namespace:         Watcher
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

define('WATCHER_PLUGIN_FILE', __FILE__);
define('WATCHER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WATCHER_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once WATCHER_PLUGIN_PATH . 'vendor/autoload.php';
