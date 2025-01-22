# Watcher Plugin

## Overview

The Watcher plugin is designed to help developers monitor and synchronize files between development and the wordpress plugins and themes
directories in a
Bedrock or wordplate environment. Plugins and themes are installed via Composer, and if the local composer.json mirrors the production
configuration, this plugin allows developers to work outside plugins and themes directories, ensuring changes are synchronized without
directly
modifying plugins or theme in the wordpress directory.

## Features

- Monitors specified directories for changes.
- Automatically synchronizes updated or new files to their target destinations.
- Supports excluding specific directories (e.g., `vendor`, `node_modules`) to optimize performance.

## Requirements

- PHP 8.3 or higher.
- WordPress installation.
- Bedrock (optional) for configuration management.

## Installation

1. Clone the repository or download the plugin zip file.
2. Place the plugin folder in the `wp-content/plugins` directory.
3. Activate the plugin via the WordPress admin dashboard.

## Configuration

To use the Watcher plugin, you need to define the `DIRECTORIES_TO_WATCH` constant in your configuration file (e.g., `wp-config.php` or
`config/environments/development.php` if using Bedrock).

### Example Configuration

```php
Config::define('DIRECTORIES_TO_WATCH', [
    'paths' => [
        'plugins' => [
            [
                'source' => dirname(__DIR__, 2) . '/dev/plugins/my-plugin',
                'destination' => dirname(__DIR__, 2) . '/web/app/plugins/my-plugin',
                'exclude' => ['vendor', 'node_modules', 'resources/i18n'],
            ],
            [
                'source' => dirname(__DIR__, 2) . '/dev/plugins/my-other-plugin',
                'destination' => dirname(__DIR__, 2) . '/web/app/plugins/my-other-plugin',
                'exclude' => ['vendor', 'node_modules', 'resources/i18n'],
            ],
        ],
        'themes' => [
            [
                'source' => dirname(__DIR__, 2) . '/dev/themes/my-theme',
                'destination' => dirname(__DIR__, 2) . '/web/app/themes/my-theme',
                'exclude' => ['vendor', 'node_modules', 'bin'],
            ],
        ],
    ],
]);
```

## Explanation

- source: The directory where the development files are located.
- destination: The directory where the files should be synchronized.
- exclude: An array of directories to exclude from synchronization.

## Usage

1. Activate the plugin.
2. The plugin will automatically monitor the specified directories and synchronize files as defined in the DIRECTORIES_TO_WATCH constant.

## How It Works

1. **Initialization**: The Watcher class is instantiated when the plugin is activated.
2. **Directory Monitoring**: The plugin reads the paths defined in the `DIRECTORIES_TO_WATCH` constant.
3. **File Synchronization**: Using the `Utils::listFilesWithRecursiveIteratorIterator` method, it scans the source directories for files
   matching the specified extensions and synchronizes them to the destination directories.
4. **Exclusions**: Any files or directories listed in the `exclude` parameter are ignored.

## Troubleshooting

If the plugin does not work as expected:

- Verify that the `DIRECTORIES_TO_WATCH` constant is correctly defined.
- Check for file or directory permissions.
- Look for PHP errors in the WordPress debug log.

## License

This plugin is open-source and distributed under the MIT License.