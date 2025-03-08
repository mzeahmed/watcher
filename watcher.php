<?php

/**
 * @since             1.0.0
 * @package           Watcher
 * @wordpress-plugin
 * Plugin Name:       Watcher
 * Description:       This plugin is designed to help developers monitor and synchronize files between development and the WP plugins and themes directories.
 * Version:           1.0.6.1
 * Author:            Ahmed Mze
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       watcher
 * Domain Path:       /resources/i18n
 * Requires PHP:      8.1
 * Requires WP:       6.0.0
 * Namespace:         Watcher
 */

declare(strict_types=1);

use Watcher\Watcher;

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

define('WATCHER_PLUGIN_FILE', __FILE__);
define('WATCHER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WATCHER_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once WATCHER_PLUGIN_PATH . 'vendor/autoload.php';

if (!class_exists(\Watcher\Watcher::class)) {
    \Watcher\Utils::pluginDie(__('Class Watcher does not exist', 'watcher'));
}

function watcher(): ?Watcher
{
    if (class_exists(\Watcher\Watcher::class)) {
        return \Watcher\Watcher::getInstance();
    }

    return null;
}

watcher();
