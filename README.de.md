# Shopware 6 Admin Color Grid

[English](README.md) | [Русский](README.ru.md)

Ein schlankes Shopware-6-Plugin, das die Zeilen der Bestellliste in der Administration abhängig vom Bestellstatus farblich hervorhebt.

## Funktionen

- **Visuelle Unterscheidung von Bestellungen**: Wichtige Bestellungen (zum Beispiel „In Bearbeitung“, „Storniert“, „Abgeschlossen“) lassen sich mit individuellen Farben markieren.
- **Verwaltungsoberfläche**: Eine eigene Seite in der Administration zum Anlegen, Bearbeiten, Aktivieren und Löschen von Farbregeln.
- **Farbwähler**: Beliebige HEX-Farben über einen integrierten Farbwähler.
- **Validierung auf dem Server**: Schutz vor doppelten Regeln für denselben Status und Prüfung des HEX-Formats.
- **Übersetzungen**: Englisch, Deutsch und Russisch.

<!--
## Screenshots

![Bestellliste mit farbigen Zeilen](docs/screenshots/order-list.png)
![Seite zur Verwaltung der Regeln](docs/screenshots/rules-page.png)
-->

## Voraussetzungen

- Shopware `~6.7.0`
- Getestet mit Shopware 6.7.15.1 und PHP 8.4

## Installation

Die kompilierten Administrations-Assets sind **nicht** Teil dieses Repositories. Die Administration muss daher nach der Installation gebaut werden.

1. Wechseln Sie in das Plugin-Verzeichnis Ihres Shopware-Projekts:

   ```bash
   cd custom/plugins
   ```

2. Klonen Sie das Repository in einen Ordner namens `ModestoxAdminColorGrid`:

   ```bash
   git clone https://github.com/Modestox/shopware-admin-color-grid.git ModestoxAdminColorGrid
   ```

3. Installieren und aktivieren Sie das Plugin:

   ```bash
   bin/console plugin:refresh
   bin/console plugin:install --activate ModestoxAdminColorGrid
   ```

4. Bauen Sie die Administration und leeren Sie den Cache:

   ```bash
   bin/build-administration.sh
   bin/console cache:clear
   ```

   Tipp: Mit `SHOPWARE_ADMIN_BUILD_ONLY_EXTENSIONS=1` überspringt der Build den Shopware-Kern und baut nur die installierten Erweiterungen. Das geht deutlich schneller:

   ```bash
   SHOPWARE_ADMIN_BUILD_ONLY_EXTENSIONS=1 bin/build-administration.sh
   ```

## Verwendung

1. Öffnen Sie die Shopware-Administration.
2. Gehen Sie zu **Einstellungen → Modestox → Admin Grid Farben**.
3. Klicken Sie auf **Regel hinzufügen**.
4. Wählen Sie einen Bestellstatus und eine Farbe aus.
5. Speichern Sie die Regel und stellen Sie sicher, dass sie **aktiv** ist.
6. Öffnen Sie **Bestellungen**: Zeilen mit passenden Status werden in den gewählten Farben hervorgehoben.

## Einschränkungen und Ausblick

- Derzeit wird nur die **Bestellliste** unterstützt.
- Geplant: Produktliste, Kundenliste und weitere Tabellen.

## Entwicklung

Projektstruktur:

```text
ModestoxAdminColorGrid/
├── composer.json
├── README.md
├── README.de.md
├── README.ru.md
├── LICENSE
├── CHANGELOG.md
└── src/
    ├── Core/                    # DAL-Entität (Definition, Entity, Collection)
    ├── Migration/               # Datenbankmigration für die Regeltabelle
    ├── Subscriber/              # Validierung auf dem Server (HEX-Farbe, doppelte Regeln)
    ├── Resources/
    │   ├── app/administration/  # Vue-Komponenten und Erweiterung von sw-order-list
    │   └── config/
    │       └── services.xml     # Service-Definitionen
    └── ModestoxAdminColorGrid.php
```

Verwenden Sie während der Entwicklung den Administrations-Watcher mit Hot Reload, statt nach jeder Änderung neu zu bauen:

```bash
bin/watch-administration.sh
```

Änderungen an PHP- oder XML-Dateien erfordern `bin/console cache:clear`.

## Lizenz

Veröffentlicht unter der MIT-Lizenz. Details in der Datei [LICENSE](LICENSE).

## Autor

Sergey Kuzmitsky — [@Modestox](https://github.com/Modestox)