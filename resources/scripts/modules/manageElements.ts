import {__} from '@wordpress/i18n';

export const manageElements = {
  init: function () {
    this._addPlugin();
    this._addTheme();
    this._removeElement();
  },

  _addPlugin(): void {
    const addPlugin = document.getElementById('add-plugin') as HTMLElement;

    if (addPlugin) addPlugin.addEventListener('click', function () {
      const container = document.getElementById('watcher-plugins') as HTMLElement;
      const index = container.children.length;
      const div = document.createElement('div');

      div.innerHTML = `
        <input type="text" name="watcher_directories[plugins][${index}][source]" placeholder="${__('Source path', 'watcher')}">
        <input type="text" name="watcher_directories[plugins][${index}][destination]" placeholder="${__('Destination path', 'watcher')}">
        <input type="text" name="watcher_directories[plugins][${index}][exclude]" placeholder="${__('Exclusions (comma-separated)', 'watcher')}">
        <button type="button" class="remove-item">❌</button>
    `;
      container.appendChild(div);
    });
  },

  _addTheme(): void {
    const addTheme = document.getElementById('add-theme') as HTMLElement;

    if (addTheme) addTheme.addEventListener('click', function () {
      const container = document.getElementById('watcher-themes') as HTMLElement;
      const index = container.children.length;
      const div = document.createElement('div');

      div.innerHTML = `
        <input type="text" name="watcher_directories[themes][${index}][source]" placeholder="${__('Source path', 'watcher')}">
        <input type="text" name="watcher_directories[themes][${index}][destination]" placeholder="${__('Destination path', 'watcher')}">
        <input type="text" name="watcher_directories[themes][${index}][exclude]" placeholder="${__('Exclusions (comma-separated)', 'watcher')}">
        <button type="button" class="remove-item">❌</button>
    `;
      container.appendChild(div);
    });
  },

  _removeElement(): void {
    const form = document.getElementById('watcher-settings-form') as HTMLFormElement;

    if (form) form.addEventListener('click', function (e) {
      const target = e.target as HTMLElement;

      if (!target.classList.contains('remove-item')) return;

      target.parentElement?.remove();
    });
  },
};