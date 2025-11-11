<?php

declare(strict_types=1);

namespace Server\Game\Battle;

use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use React\Promise\Deferred;
use React\Promise\Promise;
use Server\Events\ClientEvent;

/**
 * Class BattleQueue
 *
 * @package Server\Game\Battle
 */
class BattleQueue
{
    /**
     * @var int
     */
    protected int $id;

    /**
     * @var int
     */
    protected int $teamSize = 0;

    /**
     * @var BattleQueueTeam|null
     */
    protected ?BattleQueueTeam $team1;

    /**
     * @var BattleQueueTeam|null
     */
    protected ?BattleQueueTeam $team2;

    protected Deferred $readyDeferred;

    protected Deferred $cancelDeferred;
    /**
     * @var \React\EventLoop\TimerInterface|null
     */
    protected ?TimerInterface $timer;

    /**
     * Number of seconds * 10 battle queue will be automatically canceled if this number is expired
     *
     * @var int
     */
    protected int $timeout = 600;

    /**
     * @var bool
     */
    protected bool $canceled = false;

    /**
     * @param int $id
     * @param int $teamSize
     * @throws \Exception
     */
    public function __construct(int $id, int $teamSize)
    {
        $this->id = $id;

        if ($teamSize <= 0 || $teamSize > 3) {
            throw new \Exception('Unsupported team size given!');
        }

        $this->team1 = new BattleQueueTeam($teamSize);
        $this->team2 = new BattleQueueTeam($teamSize);

        $this->readyDeferred = new Deferred();
        $this->cancelDeferred = new Deferred();

        $this->timer = Loop::addPeriodicTimer(0.1, function () {
            if ($this->team1->getIsReady() && $this->team2->getIsReady()) {
                $this->readyDeferred->resolve();
                Loop::cancelTimer($this->timer);
            } elseif ($this->timeout <= 0) {
                $this->cancelDeferred->resolve();
                Loop::cancelTimer($this->timer);
                $this->team1->clear();
                $this->team2->clear();
                $this->team1 = null;
                $this->team2 = null;
                $this->canceled = true;
            }
            $this->timeout = $this->timeout - 1;
        });
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return BattleQueueTeam
     */
    public function getTeam1(): BattleQueueTeam
    {
        return $this->team1;
    }

    /**
     * @return BattleQueueTeam
     */
    public function getTeam2(): BattleQueueTeam
    {
        return $this->team2;
    }

    /**
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->canceled;
    }

    /**
     * @return Promise
     */
    public function onAllMembersReady(): Promise
    {
        return $this->readyDeferred->promise();
    }

    /**
     * @return Promise
     */
    public function onCanceled(): Promise
    {
        return $this->cancelDeferred->promise();
    }
}