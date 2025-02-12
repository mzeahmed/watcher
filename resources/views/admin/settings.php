<?php

/**
 * Settings page
 *
 * @var array $directories
 */

?>

<div class="wrap">
    <h1><?php _e('Watcher - Directories to Monitor', 'watcher'); ?></h1>

    <p><strong><?php _e('Base Path:', 'watcher'); ?></strong> <code><?php echo esc_html(ABSPATH); ?></code></p>
    <p><?php _e('Use this as a reference when setting the source and destination paths.', 'watcher'); ?></p>

    <form method="post" action="options.php" id="watcher-settings-form">
        <?php settings_fields('watcher_options_group'); ?>
        <?php do_settings_sections('watcher_options_group'); ?>

        <h3><?php _e('Plugins', 'watcher'); ?></h3>
        <div id="watcher-plugins">
            <?php foreach ($directories['plugins'] as $index => $plugin) : ?>
                <div>
                    <input
                            type="text"
                            name="watcher_directories[plugins][<?= $index ?>][source]"
                            value="<?= esc_attr($plugin['source']) ?>"
                            placeholder="<?php esc_attr_e('Source path', 'watcher'); ?>"
                            aria-label="Source path"
                    >

                    <input
                            type="text"
                            name="watcher_directories[plugins][<?= $index ?>][destination]"
                            value="<?= esc_attr($plugin['destination']) ?>"
                            placeholder="<?php esc_attr_e('Destination path', 'watcher'); ?>"
                            aria-label="Destination path"
                    >

                    <input
                            type="text"
                            name="watcher_directories[plugins][<?= $index ?>][exclude]"
                            value="<?= esc_attr(implode(',', $plugin['exclude'])) ?>"
                            placeholder="<?php esc_attr_e('Exclusions (comma-separated)', 'watcher'); ?>"
                            aria-label="Exclusions (comma-separated)"
                    >

                    <button type="button" class="remove-item">❌</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-plugin">➕ <?php _e('Add a plugin', 'watcher'); ?></button>

        <h3><?php _e('Themes', 'watcher'); ?></h3>
        <div id="watcher-themes">
            <?php foreach ($directories['themes'] as $index => $theme) : ?>
                <div>
                    <input
                            type="text"
                            name="watcher_directories[themes][<?= $index ?>][source]"
                            value="<?= esc_attr($theme['source']) ?>"
                            placeholder="<?php esc_attr_e('Source path', 'watcher'); ?>"
                            aria-label="Source path"
                    >

                    <input
                            type="text"
                            name="watcher_directories[themes][<?= $index ?>][destination]"
                            value="<?= esc_attr($theme['destination']) ?>"
                            placeholder="<?php esc_attr_e('Destination path', 'watcher'); ?>"
                            aria-label="Destination path"
                    >

                    <input
                            type="text"
                            name="watcher_directories[themes][<?= $index ?>][exclude]"
                            value="<?= esc_attr(implode(',', $theme['exclude'])) ?>"
                            placeholder="<?php esc_attr_e('Exclusions (comma-separated)', 'watcher'); ?>"
                            aria-label="Exclusions (comma-separated)"
                    >

                    <button type="button" class="remove-item">❌</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-theme">➕ <?php _e('Add a theme', 'watcher'); ?></button>

        <?php submit_button(); ?>
    </form>
</div>
