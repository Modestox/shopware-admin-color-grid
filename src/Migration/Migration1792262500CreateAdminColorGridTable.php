<?php

/**
 * Modestox - Shopware Admin Color Grid
 *
 * @copyright Copyright (c) 2026 Sergey Kuzmitsky
 * @license   MIT
 * @link      https://github.com/Modestox/modestox
 */

declare(strict_types=1);

namespace Modestox\AdminColorGrid\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * Creates the initial table for modestox_admin_color_grid entity.
 */
class Migration1792262500CreateAdminColorGridTable extends MigrationStep
{
    /**
     * Return migration creation timestamp.
     */
    public function getCreationTimestamp(): int
    {
        return 1792262500;
    }

    /**
     * Safe idempotent table creation with unique constraint.
     */
    public function update(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `modestox_admin_color_grid` (
    `id` BINARY(16) NOT NULL,
    `state_machine_state_id` BINARY(16) NULL,
    `entity_type` VARCHAR(64) NOT NULL DEFAULT 'order',
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `color` VARCHAR(32) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk.modestox_admin_color_grid.state_machine_state_id`
        FOREIGN KEY (`state_machine_state_id`)
        REFERENCES `state_machine_state` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);

        // Safe idempotent check for unique index existence
        $indexExists =$connection->fetchOne(
            'SELECT COUNT(*) FROM information_schema.statistics 
             WHERE table_schema = DATABASE() 
               AND table_name = "modestox_admin_color_grid" 
               AND index_name = "uniq.modestox_admin_color_grid.entity_state"'
        );

        if ((int) $indexExists === 0) {$connection->executeStatement(
                'CREATE UNIQUE INDEX `uniq.modestox_admin_color_grid.entity_state` 
                 ON `modestox_admin_color_grid` (`entity_type`, `state_machine_state_id`)'
            );
        }
    }

    /**
     * Destructive operations are omitted.
     */
    public function updateDestructive(Connection $connection): void
    {
    }
}