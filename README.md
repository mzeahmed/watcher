# Watcher Plugin

## Overview

The Watcher plugin is designed to help developers monitor and synchronize files between development and the WordPress plugins and themes
directories in a Bedrock or WordPlate environment. Plugins and themes are installed via Composer, and if the local `composer.json` mirrors
the production configuration, this plugin allows developers to work outside plugins and themes directories, ensuring changes are
synchronized without directly modifying plugins or themes in the WordPress directory.

## Features

- Monitors specified directories for changes.
- Automatically synchronizes updated or new files to their target destinations.
- Supports excluding specific directories (e.g., `vendor`, `node_modules`) to optimize performance.
- Allows configuration via a constant (`DIRECTORIES_TO_WATCH`) or through the WordPress admin panel.

## Requirements

- PHP 8.3 or higher.
- WordPress installation.
- Bedrock (optional) for configuration management.

## Installation

1. Clone the repository or download the plugin zip file.
2. Place the plugin folder in the `/plugins` directory.
3. Activate the plugin via the WordPress admin dashboard.

## Configuration

### Option 1: Define Directories via a Constant (Recommended for Development Environments)

To use the Watcher plugin, you need to define the `DIRECTORIES_TO_WATCH` constant in your configuration file (e.g., `wp-config.php` or
`config/environments/development.php` if using Bedrock).

#### Example Configuration

```php
Config::define('DIRECTORIES_TO_WATCH', [
    'paths' => [
        'plugins' => [
            [
                'source' => dirname(__DIR__, 2) . '/dev/plugins/my-plugin',
                'destination' => dirname(__DIR__, 2) . '/web/app/plugins/my-plugin',
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

### Option 2: Configure via WordPress Admin Panel

Watcher also provides an admin settings page where you can dynamically add, modify, or remove directories to monitor.

#### Steps to Use the Admin Panel:

1. Navigate to **Settings > Watcher** in the WordPress admin dashboard.
2. Add new directories to watch under the **Plugins** or **Themes** sections.
3. Specify the source and destination paths.
4. Optionally, add directories to exclude from synchronization.
5. Click **Save Changes** to apply your configuration.

If the `DIRECTORIES_TO_WATCH` constant is defined, it will take priority over the settings saved in the admin panel.

## Usage

- Activate the plugin.
- The plugin will automatically monitor the specified directories and synchronize files as defined in either the `DIRECTORIES_TO_WATCH`
  constant or the WordPress admin settings.

## How It Works

1. **Initialization**: The Watcher class is instantiated when the plugin is activated.
2. **Directory Monitoring**: The plugin reads the paths defined either via the constant or the admin settings.
3. **File Synchronization**: Using the `Utils::listFilesWithRecursiveIteratorIterator` method, it scans the source directories for files
   matching the specified extensions and synchronizes them to the destination directories.
4. **Exclusions**: Any files or directories listed in the `exclude` parameter are ignored.

## Troubleshooting

If the plugin does not work as expected:

- Verify that the `DIRECTORIES_TO_WATCH` constant is correctly defined if using this method.
- Check that the directories have been properly set in the **Watcher** settings panel.
- Ensure proper file and directory permissions.
- Look for PHP errors in the WordPress debug log.

## License

This plugin is open-source and distributed under the MIT License.
