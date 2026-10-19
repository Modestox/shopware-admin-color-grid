# Shopware 6 Admin Color Grid

[English](README.md) | [Deutsch](README.de.md)

Лёгкий плагин для Shopware 6, который раскрашивает строки списка заказов в административной панели в зависимости от статуса заказа.

## Возможности

- **Визуальное разделение заказов**: выделяйте важные заказы (например, «В обработке», «Отменён», «Завершён») индивидуальными цветами.
- **Интерфейс управления**: отдельная страница в админ-панели для добавления, редактирования, включения и удаления правил раскраски.
- **Выбор цвета**: любой HEX-цвет через встроенную палитру.
- **Серверная валидация**: защита от дубликатов правил для одного статуса и проверка формата HEX.
- **Переводы**: английский, немецкий и русский.

<!--
## Скриншоты

![Список заказов с цветными строками](docs/screenshots/order-list.png)
![Страница управления правилами](docs/screenshots/rules-page.png)
-->

## Требования

- Shopware `~6.7.0`
- Проверено на Shopware 6.7.15.1 и PHP 8.4

## Установка

Собранные файлы админки **не входят** в репозиторий, поэтому после установки админку нужно собрать.

1. Перейдите в каталог плагинов вашего проекта Shopware:

   ```bash
   cd custom/plugins
   ```

2. Склонируйте репозиторий в папку `ModestoxAdminColorGrid`:

   ```bash
   git clone https://github.com/Modestox/shopware-admin-color-grid.git ModestoxAdminColorGrid
   ```

3. Установите и активируйте плагин:

   ```bash
   bin/console plugin:refresh
   bin/console plugin:install --activate ModestoxAdminColorGrid
   ```

4. Соберите админку и очистите кэш:

   ```bash
   bin/build-administration.sh
   bin/console cache:clear
   ```

   Подсказка: с переменной `SHOPWARE_ADMIN_BUILD_ONLY_EXTENSIONS=1` сборка пропускает ядро Shopware и собирает только установленные расширения, это заметно быстрее:

   ```bash
   SHOPWARE_ADMIN_BUILD_ONLY_EXTENSIONS=1 bin/build-administration.sh
   ```

## Использование

1. Откройте админ-панель Shopware.
2. Перейдите в **Settings → Modestox → Admin Color Grid**.
3. Нажмите **Add rule** (добавить правило).
4. Выберите статус заказа и цвет.
5. Сохраните правило и убедитесь, что оно **активно**.
6. Откройте **Orders**: строки с подходящими статусами окрасятся в выбранные цвета.

## Ограничения и планы

- Сейчас поддерживается только **список заказов**.
- В планах: список товаров, список клиентов и другие таблицы.

## Разработка

Структура проекта:

```text
ModestoxAdminColorGrid/
├── composer.json
├── README.md
├── README.de.md
├── README.ru.md
├── LICENSE
├── CHANGELOG.md
└── src/
    ├── Core/                    # Сущность DAL (Definition, Entity, Collection)
    ├── Migration/               # Миграция БД для таблицы правил
    ├── Subscriber/              # Серверная валидация (HEX-цвет, дубликаты правил)
    ├── Resources/
    │   ├── app/administration/  # Vue-компоненты и расширение sw-order-list
    │   └── config/
    │       └── services.xml     # Описание сервисов
    └── ModestoxAdminColorGrid.php
```

Во время разработки запускайте watcher админки с hot reload, вместо пересборки после каждой правки:

```bash
bin/watch-administration.sh
```

После изменений в PHP или XML нужен `bin/console cache:clear`.

## Лицензия

Проект распространяется под лицензией MIT. Подробности в файле [LICENSE](LICENSE).

## Автор

Sergey Kuzmitsky — [@Modestox](https://github.com/Modestox)