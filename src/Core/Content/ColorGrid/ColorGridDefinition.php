<?php
/**
 * Modestox - Shopware Admin Color Grid
 *
 * @copyright Copyright (c) 2026 Sergey Kuzmitsky
 * @license   MIT
 * @link      https://github.com/Modestox/shopware-admin-color-grid
 */

declare(strict_types=1);

namespace Modestox\AdminColorGrid\Core\Content\ColorGrid;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;

/**
 * Definition class for modestox_admin_color_grid entity.
 */
class ColorGridDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'modestox_admin_color_grid';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return ColorGridEntity::class;
    }

    public function getCollectionClass(): string
    {
        return ColorGridCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required(), new ApiAware()),
            (new FkField('state_machine_state_id', 'stateMachineStateId', StateMachineStateDefinition::class))->addFlags(new ApiAware()),
            (new StringField('entity_type', 'entityType'))->addFlags(new Required(), new ApiAware()),
            (new BoolField('active', 'active'))->addFlags(new Required(), new ApiAware()),
            (new StringField('color', 'color'))->addFlags(new Required(), new ApiAware()),
            (new ManyToOneAssociationField('stateMachineState', 'state_machine_state_id', StateMachineStateDefinition::class, 'id', false))->addFlags(new ApiAware()),
        ]);
    }
}