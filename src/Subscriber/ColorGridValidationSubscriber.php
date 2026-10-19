<?php

/**
 * Modestox - Shopware Admin Color Grid
 *
 * @copyright Copyright (c) 2026 Sergey Kuzmitsky
 * @license   MIT
 * @link      https://github.com/Modestox/modestox
 */

declare(strict_types=1);

namespace Modestox\AdminColorGrid\Subscriber;

use Doctrine\DBAL\Connection;
use Modestox\AdminColorGrid\Core\Content\ColorGrid\ColorGridDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWriteEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\InsertCommand;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\UpdateCommand;
use Shopware\Core\Framework\Validation\WriteConstraintViolationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ColorGridValidationSubscriber implements EventSubscriberInterface
{
    /**
     * HEX color format regex pattern (#RGB or #RRGGBB).
     */
    private const COLOR_HEX_PATTERN = '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';

    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EntityWriteEvent::class => 'onEntityWrite',
        ];
    }

    /**
     * Validates entity data before writing to database.
     */
    public function onEntityWrite(EntityWriteEvent $event): void
    {
        $violations = new ConstraintViolationList();

        foreach ($event->getCommands() as $command) {
            if (!($command instanceof InsertCommand || $command instanceof UpdateCommand)) {
                continue;
            }

            if ($command->getEntityName() !== ColorGridDefinition::ENTITY_NAME) {
                continue;
            }

            $payload =$command->getPayload();

            // 1. Проверяем валидность HEX-формата цвета
            if (isset($payload['color']) && is_string($payload['color'])) {
                if (preg_match(self::COLOR_HEX_PATTERN, $payload['color']) !== 1) {$violations->add(new ConstraintViolation(
                        'modestox-color-grid.validation.invalidHexColor',
                        'modestox-color-grid.validation.invalidHexColor',
                        [],
                        $payload['color'],
                        '/color',
                        $payload['color'],
                        null,
                        Regex::REGEX_FAILED_ERROR
                    ));
                }
            }

            // 2. Проверяем дубликат правила для одного и того же статуса
            if (isset($payload['state_machine_state_id']) && !empty($payload['state_machine_state_id'])) {$entityType = isset($payload['entity_type']) && is_string($payload['entity_type'])
                    ? $payload['entity_type']
                    : 'order';

                // В Shopware 6 бинарные UUID из команд могут приходить как string или binary-stream
                $stateId = is_resource($payload['state_machine_state_id'])
                    ? stream_get_contents($payload['state_machine_state_id'])
                    : (string) $payload['state_machine_state_id'];

                $sql = 'SELECT COUNT(*) FROM `modestox_admin_color_grid` 
                        WHERE `entity_type` = :entityType 
                          AND `state_machine_state_id` = :stateId';

                $params = [
                    'entityType' => $entityType,
                    'stateId' => $stateId,
                ];

                // Если это обновление существующего правила, исключаем его ID из проверки
                if ($command instanceof UpdateCommand && isset($payload['id'])) {
                    $currentId = is_resource($payload['id'])
                        ? stream_get_contents($payload['id'])
                        : (string) $payload['id'];

                    $sql .= ' AND `id` != :currentId';
                    $params['currentId'] =$currentId;
                }

                $exists = (int)$this->connection->fetchOne($sql,$params);

                if ($exists > 0) {$violations->add(new ConstraintViolation(
                        'modestox-color-grid.validation.duplicateStatus',
                        'modestox-color-grid.validation.duplicateStatus',
                        [],
                        $stateId,
                        '/stateMachineStateId',
                        $stateId
                    ));
                }
            }
        }

        if ($violations->count() > 0) {
            throw new WriteConstraintViolationException($violations, '/stateMachineStateId');
        }
    }
}