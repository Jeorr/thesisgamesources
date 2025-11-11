<?php

declare(strict_types=1);

namespace Server\Game\Battle;

use React\Promise\Deferred;

/**
 *
 * Class BattleQueueTeam
 *
 * @package Server\Game\Battle
 */
class BattleQueueTeam
{
    /**
     * @var int
     */
    protected int $teamSize = 0;

    /**
     * @var BattleQueueTeamMember[]
     */
    protected array $members = [];

    /**
     * @param int $teamSize
     */
    public function __construct(int $teamSize)
    {
        $this->teamSize = $teamSize;
    }

    /**
     * @return int
     */
    public function getTeamSize(): int
    {
        return $this->teamSize;
    }

    /**
     * @param BattleQueueTeamMember $member
     * @return void
     * @throws \Exception
     */
    public function addMember(BattleQueueTeamMember $member): void
    {
        if (count($this->members) >= $this->teamSize) {
            throw new \Exception('Battle queue team is already full');
        }

        $objId = spl_object_id($member);
        if (isset($this->members[$objId])) {
            throw new \Exception('Member is already in battle queue team!');
        }
        $this->members[$objId] = $member;
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param BattleQueueTeamMember $member
     * @return bool
     */
    public function containsMember(BattleQueueTeamMember $member): bool
    {
        $objId = spl_object_id($member);

        return isset($this->members[$objId]);
    }

    /**
     * @param BattleQueueTeamMember $member
     * @return void
     */
    public function removeMember(BattleQueueTeamMember $member): void
    {
        $objId = spl_object_id($member);

        unset($this->members[$objId]);
    }

    /**
     * @return bool
     */
    public function getIsReady(): bool
    {
        if (count($this->members) < $this->teamSize) {
            return false;
        }

        foreach ($this->members as $member) {
            if (!$member->getIsReady()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->members = [];
    }
}