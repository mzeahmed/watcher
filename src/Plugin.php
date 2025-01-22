<?php

declare(strict_types=1);

namespace Watcher;

use Watcher\Traits\Singleton;

final class Plugin
{
    use Singleton;

    /**
     * Retrieves the plugin metadata and returns it as an instance of PluginData.
     *
     * This method gathers plugin information from filters and the plugin's main file.
     * It merges the data from the plugin file (name, version, description, etc.)
     * and the applied filters to create a PluginData instance.
     *
     * @return PluginData Instance containing all the plugin metadata and information.
     */
    public function getMetadata(): PluginData
    {
        $pluginData = [
            'plugin_path' => untrailingslashit(plugin_dir_path(WATCHER_PLUGIN_FILE)),
        ];

        $pluginFileHeaders = [
            'name' => 'Plugin Name',
            'uri' => 'Plugin URI',
            'description' => 'Description',
            'version' => 'Version',
            'author' => 'Author',
            'author-uri' => 'Author URI',
            'text-domain' => 'Text Domain',
            'domain-path' => 'Domain Path',
            'required-php' => 'Requires PHP',
            'required-wp' => 'Requires WP',
            'namespace' => 'Namespace',
        ];

        $fileData = get_file_data(WATCHER_PLUGIN_FILE, $pluginFileHeaders, 'plugin') ?? [];

        $datas = array_merge($fileData, $pluginData);

        $datas = apply_filters('watcher_plugin_data', $datas);

        return new PluginData(
            $datas['plugin_path'] ?? '',
            $datas['name'] ?? '',
            $datas['uri'] ?? '',
            $datas['description'] ?? '',
            $datas['version'] ?? '',
            $datas['author'] ?? '',
            $datas['author-uri'] ?? '',
            $datas['text-domain'] ?? '',
            $datas['domain-path'] ?? '',
            $datas['required-php'] ?? '',
            $datas['required-wp'] ?? '',
            $datas['namespace'] ?? ''
        );
    }

    public function getPluginPath(): string
    {
        return $this->getMetadata()->pluginPath;
    }

    public function getVersion(): string
    {
        return $this->getMetadata()->version;
    }

    public function getRequiredPhp(): string
    {
        return $this->getMetadata()->requiredPhp;
    }

    public function getRequiredWp(): string
    {
        return $this->getMetadata()->requiredWp;
    }

    public function getName(): string
    {
        return $this->getMetadata()->name;
    }

    public function getUri(): string
    {
        return $this->getMetadata()->uri;
    }

    public function getDescription(): string
    {
        return $this->getMetadata()->description;
    }

    public function getAuthor(): string
    {
        return $this->getMetadata()->author;
    }

    public function getAuthorUri(): string
    {
        return $this->getMetadata()->authorUri;
    }

    public function getTextDomain(): string
    {
        return $this->getMetadata()->textDomain;
    }

    public function getDomainPath(): string
    {
        return $this->getMetadata()->domainPath;
    }

    public function getNamespace(): string
    {
        return $this->getMetadata()->namespace;
    }
}
