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

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateEntity;

/**
 * Represents a single color grid configuration rule entity.
 */
class ColorGridEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $stateMachineStateId = null;

    protected string $entityType = 'order';

    protected bool $active = true;

    protected string $color = '#ffffff';

    protected ?StateMachineStateEntity $stateMachineState = null;

    public function getStateMachineStateId(): ?string
    {
        return $this->stateMachineStateId;
    }

    public function setStateMachineStateId(?string $stateMachineStateId): void
    {
        $this->stateMachineStateId = $stateMachineStateId;
    }

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): void
    {
        $this->entityType = $entityType;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getStateMachineState(): ?StateMachineStateEntity
    {
        return $this->stateMachineState;
    }

    public function setStateMachineState(?StateMachineStateEntity $stateMachineState): void
    {
        $this->stateMachineState = $stateMachineState;
    }
}