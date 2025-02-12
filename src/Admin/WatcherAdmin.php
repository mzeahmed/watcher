<?php

declare(strict_types=1);

namespace Watcher\Admin;

class WatcherAdmin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addAdminMenu(): void
    {
        add_options_page(
            'Watcher Settings',
            'Watcher',
            'manage_options',
            'watcher-settings',
            [$this, 'settingsPage']
        );
    }

    public function registerSettings(): void
    {
        register_setting('watcher_options_group', 'watcher_directories');
    }

    public function settingsPage()
    {
        $directories = get_option('watcher_directories', ['plugins' => [], 'themes' => []]);

        ?>
        <div class="wrap">
            <h1>Watcher - Répertoires à surveiller</h1>
            <form method="post" action="options.php">
                <?php settings_fields('watcher_options_group'); ?>
                <?php do_settings_sections('watcher_options_group'); ?>

                <h2>Plugins</h2>
                <div id="watcher-plugins">
                    <?php foreach ($directories['plugins'] as $index => $plugin) : ?>
                        <div>
                            <input
                                    type="text"
                                    name="watcher_directories[plugins][<?= $index ?>][source]"
                                    value="<?= esc_attr($plugin['source']) ?>"
                                    placeholder="Chemin source"
                            >

                            <input
                                    type="text"
                                    name="watcher_directories[plugins][<?= $index ?>][destination]"
                                    value="<?= esc_attr($plugin['destination']) ?>"
                                    placeholder="Chemin destination"
                            >

                            <input
                                    type="text"
                                    name="watcher_directories[plugins][<?= $index ?>][exclude]"
                                    value="<?= esc_attr(implode(',', $plugin['exclude'])) ?>"
                                    placeholder="Exclusions (séparées par une virgule)"
                            >

                            <button type="button" class="remove-item">❌</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-plugin">➕ Ajouter un plugin</button>

                <h2>Thèmes</h2>
                <div id="watcher-themes">
                    <?php foreach ($directories['themes'] as $index => $theme) : ?>
                        <div>
                            <input
                                    type="text"
                                    name="watcher_directories[themes][<?= $index ?>][source]"
                                    value="<?= esc_attr($theme['source']) ?>"
                                    placeholder="Chemin source"
                            >

                            <input
                                    type="text"
                                    name="watcher_directories[themes][<?= $index ?>][destination]"
                                    value="<?= esc_attr($theme['destination']) ?>"
                                    placeholder="Chemin destination"
                            >

                            <input
                                    type="text"
                                    name="watcher_directories[themes][<?= $index ?>][exclude]"
                                    value="<?= esc_attr(implode(',', $theme['exclude'])) ?>"
                                    placeholder="Exclusions (séparées par une virgule)"
                            >

                            <button type="button" class="remove-item">❌</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-theme">➕ Ajouter un thème</button>

                <?php submit_button(); ?>
            </form>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
          document.getElementById("add-plugin").addEventListener("click", function () {
            let container = document.getElementById("watcher-plugins");
            let index = container.children.length;
            let div = document.createElement("div");
            div.innerHTML = `
                    <input type="text" name="watcher_directories[plugins][${index}][source]" placeholder="Chemin source">
                    <input type="text" name="watcher_directories[plugins][${index}][destination]" placeholder="Chemin destination">
                    <input type="text" name="watcher_directories[plugins][${index}][exclude]" placeholder="Exclusions (séparées par une virgule)">
                    <button type="button" class="remove-item">❌</button>
                `;
            container.appendChild(div);
          });

          document.getElementById("add-theme").addEventListener("click", function () {
            let container = document.getElementById("watcher-themes");
            let index = container.children.length;
            let div = document.createElement("div");
            div.innerHTML = `
                    <input type="text" name="watcher_directories[themes][${index}][source]" placeholder="Chemin source">
                    <input type="text" name="watcher_directories[themes][${index}][destination]" placeholder="Chemin destination">
                    <input type="text" name="watcher_directories[themes][${index}][exclude]" placeholder="Exclusions (séparées par une virgule)">
                    <button type="button" class="remove-item">❌</button>
                `;
            container.appendChild(div);
          });

          document.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-item")) {
              e.target.parentElement.remove();
            }
          });
        });
        </script>
        <?php
    }
}
