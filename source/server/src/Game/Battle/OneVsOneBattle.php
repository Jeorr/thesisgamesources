<?php

declare(strict_types=1);

namespace Server\Game\Battle;

use Server\Events\ClientEvent;

/**
 *
 */
class OneVsOneBattle extends BaseBattle
{

    public function __construct(int $id, BattleUnit $unit1, BattleUnit $unit2)
    {
        parent::__construct($id);

        $team1 = new BattleTeam();
        $team1->addUnit($unit1);
        $unit1->setTeam($team1);
        $team2 = new BattleTeam();
        $team2->addUnit($unit2);
        $unit2->setTeam($team2);

        $this->setTeam1($team1);
        $this->setTeam2($team2);

        $unit2->setEnableAI(true);
    }
}