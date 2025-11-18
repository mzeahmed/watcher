<?php

declare(strict_types=1);

namespace Watcher;

use Watcher\Traits\Singleton;
use Watcher\Admin\WatcherAdmin;

class Watcher
{
    use Singleton;

    private const array ALLOWED_EXTENSIONS = [
        'php',
        'js',
        'ts',
        'css',
        'scss',
        'json',
        'png',
        'jpg',
        'jpeg',
        'gif',
        'svg',
        'mo',
        'po',
        'pot',
        'ttf',
        'woff',
        'woff2',
        'eot',
        'yml',
        'yaml'
    ];

    public function __construct()
    {
        $directoriesToWatch = $this->getDirectoriesToWatch();

        if (empty($directoriesToWatch)) {
            throw new \RuntimeException('No directories to watch found.');
        }

        $this->boot($directoriesToWatch);

        if (is_admin()) {
            new WatcherAdmin();
        }
    }

    private function getDirectoriesToWatch(): array
    {
        // Vérify if the constant DIRECTORIES_TO_WATCH is defined and not empty
        if (\defined('DIRECTORIES_TO_WATCH') && !empty(DIRECTORIES_TO_WATCH)) {
            return DIRECTORIES_TO_WATCH['paths'];
        }

        // If the constant DIRECTORIES_TO_WATCH is not defined, we use the option watcher_directories
        return get_option('watcher_directories', []);
    }

    private function boot(array $directoriesToWatch): void
    {
        foreach ($directoriesToWatch['plugins'] ?? [] as $pluginConfig) {
            $this->processSync($pluginConfig);
        }

        foreach ($directoriesToWatch['themes'] ?? [] as $themeConfig) {
            $this->processSync($themeConfig);
        }
    }

    private function syncFiles(array $sourceFiles, string $destinationPath, string $sourcePath): void
    {
        foreach ($sourceFiles as $file) {
            $relativePath = str_replace($sourcePath, '', $file);
            $destinationFile = $destinationPath . $relativePath;

            // Create the destination directory if it does not exist
            if (
                !file_exists(\dirname($destinationFile))
                && !mkdir($concurrentDirectory = dirname($destinationFile), 0777, true)
                && !is_dir($concurrentDirectory)
            ) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }

            // Copy the file if it does not exist or if it is newer than the destination file
            if (!file_exists($destinationFile) || filemtime($file) > filemtime($destinationFile)) {
                copy($file, $destinationFile);
            }
        }
    }

    private function syncDeletions(array $sourceFiles, string $destinationPath, string $sourcePath, array $exclude): void
    {
        $sourceSet = [];
        foreach ($sourceFiles as $file) {
            $sourceSet[str_replace($sourcePath, '', $file)] = true;
        }

        $destFiles = Utils::listFilesWithRecursiveIteratorIterator(
            $destinationPath,
            self::ALLOWED_EXTENSIONS,
            $exclude
        );

        foreach ($destFiles as $destFile) {
            $relative = str_replace($destinationPath, '', $destFile);
            if (!isset($sourceSet[$relative])) {
                @unlink($destFile);
            }
        }

        $this->removeEmptyDirectories($destinationPath);
    }

    private function removeEmptyDirectories(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $dir = $item->getPathname();
                if (!(new \FilesystemIterator($dir))->valid()) {
                    @rmdir($dir);
                }
            }
        }
    }

    private function processSync(array $config): void
    {
        $source = $config['source'] ?? '';
        $destination = $config['destination'] ?? '';
        $exclude = !empty($config['exclude']) ? (is_array($config['exclude']) ? $config['exclude'] : explode(',', $config['exclude'])) : [];

        if (!$source || !$destination) {
            return;
        }

        $files = Utils::listFilesWithRecursiveIteratorIterator(
            $source,
            self::ALLOWED_EXTENSIONS,
            $exclude
        );
        $this->syncFiles($files, $destination, $source);
        $this->syncDeletions($files, $destination, $source, $exclude);
    }
}
