<?php

declare(strict_types=1);

namespace Watcher;

use WPYoostart\File;
use Watcher\Traits\Singleton;

class Watcher
{
    use Singleton;

    private ?File $file = null;
    private string $wpYsPLuginDevPath;
    private string $wpYsThemeDevPath;

    public function __construct()
    {
        // $start = microtime(true);

        $this->file = new File();
        $this->wpYsPLuginDevPath = APP_ABS_PATH . '/dev/plugins/wp-yoostart';
        $this->wpYsThemeDevPath = APP_ABS_PATH . '/dev/themes/wp-yoostart-theme';

        $this->boot();

        // dump('Execution time: ' . (microtime(true) - $start));
    }

    private function boot(): void
    {
        $defaultDirectories = [
            'vendor',
            'node_modules',
        ];

        $exludePluginDirectories = [...$defaultDirectories, 'resources/i18n'];

        $exludeThemeDirectories = [...$defaultDirectories, 'bin'];

        $pluginFiles = $this->file
            ->listFilesWithRecursiveIteratorIterator($this->wpYsPLuginDevPath, 'php', $exludePluginDirectories);

        $activePluginPath = WP_PLUGIN_DIR . '/wp-yoostart';
        $this->syncFiles($pluginFiles, $activePluginPath);

        $themeFiles = $this->file
            ->listFilesWithRecursiveIteratorIterator($this->wpYsThemeDevPath, 'php', $exludeThemeDirectories);

        $activeThemePath = get_theme_root() . '/wp-yoostart-theme';
        $this->syncFiles($themeFiles, $activeThemePath);
    }

    private function syncFiles(array $sourceFiles, string $destinationPath): void
    {
        foreach ($sourceFiles as $file) {
            $relativePath = str_replace($this->wpYsPLuginDevPath, '', $file);
            $destinationFile = $destinationPath . $relativePath;

            // Create directory if it doesn't exist
            if (!file_exists(dirname($destinationFile))) {
                mkdir(dirname($destinationFile), 0777, true);
            }

            // Copy only if source is newer
            if (!file_exists($destinationFile) || filemtime($file) > filemtime($destinationFile)) {
                copy($file, $destinationFile);
            }
        }
    }
}
