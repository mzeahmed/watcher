<?php

declare(strict_types=1);

namespace Watcher;

use Watcher\Traits\Singleton;

class Watcher
{
    use Singleton;

    private const array EXTENSIONS = ['php', 'js', 'ts', 'css', 'scss', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'json'];

    public function __construct()
    {
        if (!defined('DIRECTORIES_TO_WATCH')) {
            throw new \RuntimeException('DIRECTORIES_TO_WATCH constant is not defined.');
        }

        $this->boot();
    }

    private function boot(): void
    {
        /**
         * The constant DIRECTORIES_TO_WATCH need to be defined in a config file, for example: wp-config.php
         * or config/environment/development.php if bedrock is used.
         */
        $directoriesToWatch = DIRECTORIES_TO_WATCH['paths'];

        foreach ($directoriesToWatch['plugins'] as $pluginConfig) {
            $pluginFiles = Utils::listFilesWithRecursiveIteratorIterator(
                $pluginConfig['source'],
                self::EXTENSIONS,
                $pluginConfig['exclude']
            );

            $this->syncFiles($pluginFiles, $pluginConfig['destination'], $pluginConfig['source']);
        }

        foreach ($directoriesToWatch['themes'] as $themeConfig) {
            $themeFiles = Utils::listFilesWithRecursiveIteratorIterator(
                $themeConfig['source'],
                self::EXTENSIONS,
                $themeConfig['exclude']
            );

            $this->syncFiles($themeFiles, $themeConfig['destination'], $themeConfig['source']);
        }
    }

    private function syncFiles(array $sourceFiles, string $destinationPath, string $sourcePath): void
    {
        foreach ($sourceFiles as $file) {
            $relativePath = str_replace($sourcePath, '', $file);
            $destinationFile = $destinationPath . $relativePath;

            // Create the destination directory if it does not exist
            if (!file_exists(dirname($destinationFile))) {
                mkdir(dirname($destinationFile), 0777, true);
            }

            // Copy the file if it does not exist or if it is newer than the destination file
            if (!file_exists($destinationFile) || filemtime($file) > filemtime($destinationFile)) {
                copy($file, $destinationFile);
            }
        }
    }
}
