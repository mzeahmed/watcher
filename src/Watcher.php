<?php

declare(strict_types=1);

namespace Watcher;

use WPYoostart\File;
use Watcher\Traits\Singleton;

class Watcher
{
    use Singleton;

    private ?File $file = null;

    public function __construct()
    {
        $this->file = new File();

        if (!defined('DIRECTORIES_TO_WATCH')) {
            throw new \RuntimeException('DIRECTORIES_TO_WATCH constant is not defined.');
        }

        $this->boot();
    }

    private function boot(): void
    {
        $directoriesToWatch = DIRECTORIES_TO_WATCH['paths'];

        foreach ($directoriesToWatch['plugins'] as $pluginConfig) {
            $pluginFiles = $this->file->listFilesWithRecursiveIteratorIterator(
                $pluginConfig['source'],
                ['php', 'js', 'ts', 'css', 'scss', 'png', 'jpg', 'jpeg', 'gif', 'svg'],
                $pluginConfig['exclude']
            );

            $this->syncFiles($pluginFiles, $pluginConfig['destination'], $pluginConfig['source']);
        }

        foreach ($directoriesToWatch['themes'] as $themeConfig) {
            $themeFiles = $this->file->listFilesWithRecursiveIteratorIterator(
                $themeConfig['source'],
                ['php', 'js', 'ts', 'css', 'scss', 'png', 'jpg', 'jpeg', 'gif', 'svg'],
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

            // Crée le répertoire si nécessaire
            if (!file_exists(dirname($destinationFile))) {
                mkdir(dirname($destinationFile), 0777, true);
            }

            // Copie uniquement si la source est plus récente
            if (!file_exists($destinationFile) || filemtime($file) > filemtime($destinationFile)) {
                copy($file, $destinationFile);
            }
        }
    }
}
