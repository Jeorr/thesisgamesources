<?php

declare(strict_types=1);

namespace Server\Game\Modifiers;

/**
 * Class ModifierInterface
 *
 * @package Server\Game\Modifiers
 */
interface ModifierInterface
{
    const MODIFY_BUFF_DAMAGE_SOURCE = 'MODIFY_BUFF_DAMAGE_SOURCE';
    const MODIFY_BUFF_FINAL_DAMAGE = 'MODIFY_BUFF_FINAL_DAMAGE';

    public function getSlots(): array;
}