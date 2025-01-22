<?php

declare(strict_types=1);

namespace Watcher;

/**
 *  Represents the metadata and configuration details of the plugin.
 */
class PluginData
{
    /**
     * The absolute path to the plugin's main directory.
     *
     * @var string
     */
    public string $pluginPath;

    /**
     * The name of the plugin.
     *
     * @var string
     */
    public string $name;

    /**
     * The URI of the plugin's homepage or documentation.
     *
     * @var string
     */
    public string $uri;

    /**
     * A brief description of the plugin's functionality.
     *
     * @var string
     */
    public string $description;

    /**
     * The version of the plugin.
     *
     * @var string
     */
    public string $version;

    /**
     * The author of the plugin.
     *
     * @var string
     */
    public string $author;

    /**
     * The URI of the plugin author's homepage or profile.
     *
     * @var string
     */
    public string $authorUri;

    /**
     * The text domain for internationalization and localization.
     *
     * @var string
     */
    public string $textDomain;

    /**
     * The path to the domain files for translations.
     *
     * @var string
     */
    public string $domainPath;

    /**
     * The minimum required PHP version for the plugin.
     *
     * @var string
     */
    public string $requiredPhp;

    /**
     * The minimum required WordPress version for the plugin.
     *
     * @var string
     */
    public string $requiredWp;

    /**
     * The PHP namespace used for the plugin's classes and functionality.
     *
     * @var string
     */
    public string $namespace;

    /**
     * @param string $pluginPath The absolute path to the plugin's main directory.
     * @param string $name The name of the plugin.
     * @param string $uri The URI of the plugin's homepage or documentation.
     * @param string $description A brief description of the plugin's functionality.
     * @param string $version The version of the plugin.
     * @param string $author The author of the plugin.
     * @param string $authorUri The URI of the plugin author's homepage or profile.
     * @param string $textDomain The text domain for internationalization and localization.
     * @param string $domainPath The path to the domain files for translations.
     * @param string $requiredPhp The minimum required PHP version for the plugin.
     * @param string $requiredWp The minimum required WordPress version for the plugin.
     * @param string $namespace The PHP namespace used for the plugin's classes and functionality.
     */
    public function __construct(
        string $pluginPath,
        string $name,
        string $uri,
        string $description,
        string $version,
        string $author,
        string $authorUri,
        string $textDomain,
        string $domainPath,
        string $requiredPhp,
        string $requiredWp,
        string $namespace
    ) {
        $this->pluginPath = $pluginPath;
        $this->name = $name;
        $this->uri = $uri;
        $this->description = $description;
        $this->version = $version;
        $this->author = $author;
        $this->authorUri = $authorUri;
        $this->textDomain = $textDomain;
        $this->domainPath = $domainPath;
        $this->requiredPhp = $requiredPhp;
        $this->requiredWp = $requiredWp;
        $this->namespace = $namespace;
    }
}
