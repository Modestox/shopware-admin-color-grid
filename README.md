# Shopware 6 Admin Color Grid

[Deutsch](README.de.md) | [Русский](README.ru.md)

A lightweight Shopware 6 plugin that highlights rows of the order list in the Administration with colors, depending on the order status.

## Features

- **Visual separation of orders**: highlight important orders (for example "In progress", "Cancelled", "Completed") with individual colors.
- **Management UI**: a dedicated page in the Administration to add, edit, enable and delete coloring rules.
- **Color picker**: choose any HEX color with a built-in picker.
- **Server-side validation**: protection against duplicate rules for the same status and validation of the HEX format.
- **Translations**: English, German and Russian.

<!--
## Screenshots

![Order list with colored rows](docs/screenshots/order-list.png)
![Rule management page](docs/screenshots/rules-page.png)
-->

## Requirements

- Shopware `~6.7.0`
- Tested with Shopware 6.7.15.1 and PHP 8.4

## Installation

The compiled Administration assets are **not** part of this repository, so the Administration has to be built after installation.

1. Go to the plugins directory of your Shopware project:

   ```bash
   cd custom/plugins
   ```

2. Clone the repository into a folder named `ModestoxAdminColorGrid`:

   ```bash
   git clone https://github.com/Modestox/shopware-admin-color-grid.git ModestoxAdminColorGrid
   ```

3. Install and activate the plugin:

   ```bash
   bin/console plugin:refresh
   bin/console plugin:install --activate ModestoxAdminColorGrid
   ```

4. Build the Administration and clear the cache:

   ```bash
   bin/build-administration.sh
   bin/console cache:clear
   ```

   Tip: with `SHOPWARE_ADMIN_BUILD_ONLY_EXTENSIONS=1` the build skips the Shopware core and builds only the installed extensions, which is much faster:

   ```bash
   SHOPWARE_ADMIN_BUILD_ONLY_EXTENSIONS=1 bin/build-administration.sh
   ```

## Usage

1. Open the Shopware Administration.
2. Go to **Settings → Modestox → Admin Color Grid**.
3. Click **Add rule**.
4. Select an order status and pick a color.
5. Save the rule and make sure it is set to **Active**.
6. Open **Orders**: rows with the matching statuses are highlighted in the selected colors.

## Limitations and roadmap

- Currently only the **order list** is supported.
- Planned: product list, customer list and other grids.

## Development

Project structure:

```text
ModestoxAdminColorGrid/
├── composer.json
├── README.md
├── README.ru.md
├── LICENSE
├── CHANGELOG.md
└── src/
    ├── Core/                    # DAL entity (Definition, Entity, Collection)
    ├── Migration/               # Database migration for the rules table
    ├── Subscriber/              # Server-side validation (HEX color, duplicate rules)
    ├── Resources/
    │   ├── app/administration/  # Vue components and the sw-order-list extension
    │   └── config/
    │       └── services.xml     # Service definitions
    └── ModestoxAdminColorGrid.php
```

Run the Administration watcher with hot reload while developing, instead of rebuilding after every change:

```bash
bin/watch-administration.sh
```

Changes to PHP or XML files require `bin/console cache:clear`.

## License

Released under the MIT License. See [LICENSE](LICENSE) for details.

## Author

Sergey Kuzmitsky — [@Modestox](https://github.com/Modestox)