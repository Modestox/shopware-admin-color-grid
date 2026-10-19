/**
 * Modestox - Shopware Admin Color Grid
 *
 * @copyright Copyright (c) 2026 Sergey Kuzmitsky
 * @license   MIT
 * @link      https://github.com/Modestox/modestox
 */

import './page/modestox-color-grid-index';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';
import ruRU from './snippet/ru-RU.json';

const { Module } = Shopware;

Module.register('modestox-color-grid', {
    type: 'plugin',
    name: 'modestox-color-grid',
    title: 'modestox-color-grid.general.title',
    description: 'modestox-color-grid.general.description',
    color: '#9AA8EC',
    icon: 'regular-cog',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB,
        'ru-RU': ruRU,
    },

    routes: {
        index: {
            component: 'modestox-color-grid-index',
            path: 'index',
            meta: {
                parentPath: 'sw.settings.index',
            },
        },
    },

    routeMiddleware(next, currentRoute) {
        next(currentRoute);
    },

    settingsItem: [
        {
            group: 'modestox',
            to: 'modestox.color.grid.index',
            icon: 'regular-cog',
            label: 'modestox-color-grid.general.title',
        },
    ],
});