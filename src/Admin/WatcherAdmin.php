<?php

declare(strict_types=1);

namespace Watcher\Admin;

class WatcherAdmin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'registerSettings']);

        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
    }

    public function addAdminMenu(): void
    {
        add_options_page(
            'Watcher Settings',
            'Watcher',
            'manage_options',
            'watcher-settings',
            [$this, 'settingsPage']
        );
    }

    public function registerSettings(): void
    {
        register_setting('watcher_options_group', 'watcher_directories');
    }

    public function settingsPage(): void
    {
        $directories = get_option('watcher_directories', ['plugins' => [], 'themes' => []]);

        require_once WATCHER_PLUGIN_PATH . 'resources/views/admin/settings.php';
    }

    public function enqueueAdmin(): void
    {
        $currentScreen = get_current_screen();

        $asset = WATCHER_PLUGIN_PATH . 'resources/build/app.asset.php';

        $dependencies = array_merge($asset['dependencies'] ?? [], ['jquery', 'wp-element']);

        if ('settings_page_watcher-settings' === $currentScreen?->base) {
            wp_enqueue_script(
                'watcher-admin',
                WATCHER_PLUGIN_URL . 'resources/build/app.js',
                $dependencies,
                $asset['version'] ?? false,
                true
            );

            wp_enqueue_style(
                'watcher-admin',
                WATCHER_PLUGIN_URL . 'resources/build/app.css',
                [],
                $asset['version'] ?? false
            );
        }
    }
}
