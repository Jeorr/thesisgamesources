<?php

declare(strict_types=1);

namespace Server\Game\Battle;

use Server\Game\Models\Unit;

/**
 * Class BattleQueueTeamMember
 *
 * @package Server\Game\Battle
 */
class BattleQueueTeamMember
{
    protected bool $isReady = false;
    protected ?Unit $unit = null;

    public function __construct(Unit $unit){
        $this->unit = $unit;
    }

    /**
     * @return bool
     */
    public function getIsReady(): bool
    {
        return $this->isReady;
    }

    /**
     * @param bool $isReady
     */
    public function setIsReady(bool $isReady): void
    {
        $this->isReady = $isReady;
    }

    /**
     * @return Unit|null
     */
    public function getUnit(): ?Unit
    {
        return $this->unit;
    }
}