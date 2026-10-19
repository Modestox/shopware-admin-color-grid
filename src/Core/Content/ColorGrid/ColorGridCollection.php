<?php
/**
 * Modestox - Shopware Admin Color Grid
 *
 * @copyright Copyright (c) 2026 Sergey Kuzmitsky
 * @license   MIT
 * @link      https://github.com/Modestox/modestox
 */

declare(strict_types=1);

namespace Modestox\AdminColorGrid\Core\Content\ColorGrid;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Collection for ColorGridEntity elements.
 *
 * @extends EntityCollection<ColorGridEntity>
 */
class ColorGridCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ColorGridEntity::class;
    }
}