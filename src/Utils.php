<?php

declare(strict_types=1);

namespace Watcher;

/**
 * Helper class
 *
 * @since 1.0.4
 */
class Utils
{
    /**
     * Returns a list of files in the specified directory using the RecursiveIteratorIterator object.
     *
     * @param string $directory Path of the directory to list.
     * @param array $extensions File extensions to include.
     * @param array $excludeDirectories Directories to exclude from the listing.
     *
     * @return array|null List of files found in the directory.
     * @since 1.0.4
     */
    public static function listFilesWithRecursiveIteratorIterator(
        string $directory,
        array $extensions = ['php'],
        array $excludeDirectories = []
    ): ?array {
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
                function ($current) use ($excludeDirectories) {
                    foreach ($excludeDirectories as $excluded) {
                        if (strpos($current->getPathname(), DIRECTORY_SEPARATOR . $excluded . DIRECTORY_SEPARATOR) !== false) {
                            return false; // Exclude this directory or file
                        }
                    }

                    return true; // Keep the file or directory
                }
            )
        );

        foreach ($iterator as $file) {
            if (in_array($file->getExtension(), $extensions, true)) {
                $files[] = $file->getRealPath();
            }
        }

        return $files;
    }

    /**
     * Writes a log if WP_DEBUG is enabled.
     *
     * @param mixed $log The log to write.
     */
    public static function writeLog(mixed $log): void
    {
        if (\is_array($log) || \is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }

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

            self::writeLog([
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

            self::writeLog([
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
            $notice = "<strong><h3>{$title}</h3>{$subtitle}</strong><p>{$message}</p><hr><p>{$footer}</p>";

            add_action('admin_notices', static function () use ($notice) {
                echo wp_kses_post(
                    \sprintf('<div class="notice notice-error"><p>%s</p></div>', $notice)
                );
            });
        }

        add_action('admin_init', static function () {
            deactivate_plugins(
                plugin_basename(WATCHER_PLUGIN_FILE)
            );
        });
    }

    /**
     * Retrieves the plugin data.
     *
     * @return PluginData
     */
    private static function getPluginData(): PluginData
    {
        return Plugin::getInstance()->getMetadata();
    }
}
