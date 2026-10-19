<?php
/**
 * Modestox - Shopware Admin Color Grid
 *
 * @copyright Copyright (c) 2026 Sergey Kuzmitsky
 * @license   MIT
 * @link      https://github.com/Modestox/modestox
 */

declare(strict_types=1);

namespace Modestox\AdminColorGrid;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

/**
 * Main plugin class for Admin Grid Color Customizer.
 */
class ModestoxAdminColorGrid extends Plugin
{
    /**
     * Uninstalls plugin resources and database tables if user data retention is disabled.
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);$connection->executeStatement('DROP TABLE IF EXISTS `modestox_admin_color_grid`');
    }
}