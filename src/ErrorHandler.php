<?php

declare(strict_types=1);

namespace Watcher;

/**
 * This class provides cleaner error handling, logging, plugin deactivation, and more.
 *
 * @package WPYoostart\Helpers
 * @since   1.0.0
 */
class ErrorHandler
{
    /**
     * Enhanced wp_die error function.
     *
     * @param string $message Error message.
     * @param string $subtitle Specific subtitle for the error.
     * @param string $source Source file of the error.
     * @param string $exception Exception details.
     * @param string $title Error title.
     *
     * @since 1.0.0
     */
    public static function wpDie(
        string $message = '',
        string $subtitle = '',
        string $source = '',
        string $exception = '',
        string $title = ''
    ): void {
        if ($message) {
            $plugin = self::getPluginData();
            $title = $title ?: $plugin->name . ' ' . $plugin->version . ' ' . __('&rsaquo; Fatal Error', 'watcher');
            Debugger::writeLog([
                'title' => $title . ' - ' . $subtitle,
                'message' => $message,
                'source' => $source,
                'exception' => $exception,
            ]);
            $source = $source ? '<code>' .
                                \sprintf(
                                /* translators: %s: file path */
                                    __('Error source: %s', 'watcher'),
                                    $source
                                ) . '</code><BR><BR>' : '';
            $footer = $source . '<a href="' . $plugin->uri . '">' . $plugin->uri . '</a>';
            $message = '<p>' . $message . '</p>';
            $message .= $exception ? '<p><strong>Exception: </strong><BR>' . $exception . '</p>' : '';
            $message = "<h1>{$title}<br><small>{$subtitle}</small></h1>{$message}<hr><p>{$footer}</p>";
            wp_die($message, $title);
        } else {
            wp_die();
        }
    }

    /**
     * Deactivates the plugin and displays an error notification in the admin back-end.
     *
     * @param string $message Error message.
     * @param string $subtitle Specific subtitle for the error.
     * @param string $source Source file of the error.
     * @param string $exception Exception details.
     * @param string $title Error title.
     *
     * @since 1.0.0
     */
    public static function pluginDie(
        string $message = '',
        string $subtitle = '',
        string $source = '',
        string $exception = '',
        string $title = ''
    ): void {
        if ($message) {
            $plugin = self::getPluginData();
            $title = $title ?: $plugin->name . ' ' . $plugin->version . ' ' . __('&rsaquo; Plugin Deactivated', 'watcher');
            Debugger::writeLog([
                'title' => $title . ' - ' . $subtitle,
                'message' => $message,
                'source' => $source,
                'exception' => $exception,
            ]);
            $source = $source ? '<small>' .
                                \sprintf( /* translators: %s: file path */
                                    __('Error source: %s', 'watcher'),
                                    $source
                                ) . '</small> - ' : '';
            $footer = $source . '<a href="' . $plugin->uri . '"><small>' . $plugin->uri . '</small></a>';
            $error = "<strong><h3>{$title}</h3>{$subtitle}</strong><p>{$message}</p><hr><p>{$footer}</p>";
            global $the_plugin_name_die_notice;
            $the_plugin_name_die_notice = $error;

            add_action('admin_notices', static function () {
                global $the_plugin_name_die_notice;
                echo wp_kses_post(
                    \sprintf('<div class="notice notice-error"><p>%s</p></div>', $the_plugin_name_die_notice)
                );
            });
        }

        add_action('admin_init', static function () {
            deactivate_plugins(
                plugin_basename(YS_PLUGIN_FILE)
            );
        });
    }

    /**
     * Retrieves the plugin data.
     *
     * @return PluginData
     * @since 1.0.0
     */
    private static function getPluginData(): PluginData
    {
        return Plugin::getInstance()->getData();
    }
}
