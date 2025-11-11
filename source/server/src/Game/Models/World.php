<?php

declare(strict_types=1);

namespace Server\Game\Models;

use Server\Game\Battle\BaseBattle;
use Server\Game\Battle\BattleQueue;

/**
 *
 */
class World extends BaseModel
{

    /**
     * @var bool
     */
    protected bool $initialized = false;

    /**
     * @var \Server\Game\Models\Chapter|null
     */
    protected ?Chapter $chapter = null;

    /**
     * @var \Server\Game\Models\Territory[]|null
     */
    protected ?array $territories = null;

    /**
     * @var \Server\Game\Models\Location[]|null
     */
    protected ?array $locations = null;

    /**
     * @var \Server\Game\Models\Npc[]|null
     */
    protected ?array $npcs = null;

    /**
     * @var \Server\Game\Models\Item[]|null
     */
    protected ?array $items = null;

    /**
     * @var \Server\Game\Models\Elixir[]|null
     */
    protected ?array $elixirs = null;
    
    /**
     * @var \Server\Game\Models\Structure[]|null
     */
    protected ?array $structures = null;

    /**
     * @var \Server\Game\Models\Buff[]|null
     */
    protected ?array $buffs = null;

    /**
     * @var \Server\Game\Models\Skill[]|null
     */
    protected ?array $skills = null;

    /**
     * @var \Server\Game\Models\Talent[]|null
     */
    protected ?array $talents = null;

    /**
     * @var BaseBattle[]
     */
    protected array $battles = [];

    /**
     * @var BattleQueue[]
     */
    protected array $battleQueues = [];

    /**
     * @var \Server\Game\Models\User[]
     */
    protected array $users = [];

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }

    /**
     * @param bool $initialized
     */
    public function setInitialized(bool $initialized): void
    {
        $this->initialized = $initialized;
    }

    /**
     * @return Chapter|null
     */
    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    /**
     * @param Chapter|null $chapter
     */
    public function setChapter(?Chapter $chapter): void
    {
        $this->chapter = $chapter;
    }

    /**
     * @return array|null
     */
    public function getTerritories(): ?array
    {
        return $this->territories;
    }

    /**
     * @param  array|null  $territories
     */
    public function setTerritories(?array $territories): void
    {
        $this->territories = $territories;
    }

    /**
     * @param  \Server\Game\Models\Territory  $territory
     *
     * @return void
     */
    public function addTerritory(Territory $territory): void
    {
        $this->territories[$territory->getCode()] ??= $territory;
    }

    /**
     * @return array|null
     */
    public function getLocations(): ?array
    {
        return $this->locations;
    }

    /**
     * @param  array|null  $locations
     */
    public function setLocations(?array $locations): void
    {
        $this->locations = $locations;
    }

    /**
     * @param  \Server\Game\Models\Location  $location
     *
     * @return void
     */
    public function addLocation(Location $location): void
    {
        $this->locations[$location->getCode()] ??= $location;
    }

    /**
     * @return array|null
     */
    public function getNpcs(): ?array
    {
        return $this->npcs;
    }

    /**
     * @param  array|null  $npcs
     */
    public function setNpcs(?array $npcs): void
    {
        $this->npcs = $npcs;
    }

    /**
     * @param  \Server\Game\Models\Npc  $npc
     *
     * @return void
     */
    public function addNpc(Npc $npc): void
    {
        $this->npcs[$npc->getCode()] ??= $npc;
    }

    /**
     * @return array|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param  array|null  $items
     */
    public function setItems(?array $items): void
    {
        $this->items = $items;
    }

    /**
     * @param  \Server\Game\Models\Item  $item
     *
     * @return void
     */
    public function addItem(Item $item): void
    {
        $this->items[$item->getCode()] ??= $item;
    }

    /**
     * @return array|null
     */
    public function getElixirs(): ?array
    {
        return $this->elixirs;
    }

    /**
     * @param array|null $elixirs
     */
    public function setElixirs(?array $elixirs): void
    {
        $this->elixirs = $elixirs;
    }

    /**
     * @param \Server\Game\Models\Elixir $elixir
     * @return void
     */
    public function addElixir(Elixir $elixir): void
    {
        $this->elixirs[$elixir->getCode()] ??= $elixir;
    }
    
    /**
     * @return array|null
     */
    public function getStructures(): ?array
    {
        return $this->structures;
    }

    /**
     * @param array|null $structures
     * @return void
     */
    public function setStructures(?array $structures): void
    {
        $this->structures = $structures;
    }

    /**
     * @param Structure $structure
     * @return void
     */
    public function addStructure(Structure $structure): void
    {
        $this->structures[$structure->getCode()] ??= $structure;
    }

    /**
     * @return array|null
     */
    public function getTalents(): ?array
    {
        return $this->talents;
    }

    /**
     * @param int $category
     * @return Talent[]|null
     */
    public function getTalentsByCategory(int $category): ?array
    {
        $talents = [];

        foreach ($this->getTalents() as $talent) {
            if ($talent->getCategory() === $category) {
                $talents[$talent->getCode()] = $talent;
            }
        }

        return $talents;
    }


    /**
     * @param array|null $talents
     */
    public function setTalents(?array $talents): void
    {
        $this->talents = $talents;
    }

    /**
     * @param Talent $talent
     * @return void
     */
    public function addTalent(Talent $talent): void
    {
        $this->talents[$talent->getCode()] ??= $talent;
    }

    /**
     * @return array|null
     */
    public function getBuffs(): ?array
    {
        return $this->buffs;
    }

    /**
     * @param array|null $buffs
     */
    public function setBuffs(?array $buffs): void
    {
        $this->buffs = $buffs;
    }

    /**
     * @param Buff $buff
     * @return void
     */
    public function addBuff(Buff $buff): void
    {
        $this->buffs[$buff->getCode()] ??= $buff;
    }

    /**
     * @return array|null
     */
    public function getSkills(): ?array
    {
        return $this->skills;
    }

    /**
     * @param array|null $skills
     */
    public function setSkills(?array $skills): void
    {
        $this->skills = $skills;
    }

    /**
     * @param Skill $skill
     * @return void
     */
    public function addSkill(Skill $skill): void
    {
        $this->skills[$skill->getCode()] ??= $skill;
    }

    /**
     * @return array
     */
    public function getBattles(): array
    {
        return $this->battles;
    }

    /**
     * @param array $battles
     */
    public function setBattles(array $battles): void
    {
        $this->battles = $battles;
    }

    /**
     * @param int $id
     *
     * @return BaseBattle|null
     */
    public function getBattleById(int $id): ?BaseBattle
    {
        return $this->battles[$id] ?? null;
    }

    /**
     * @param BaseBattle $battle
     *
     * @return void
     */
    public function addBattle(BaseBattle $battle): void
    {
        $this->battles[$battle->getId()] = $battle;
    }

    /**
     * @param BaseBattle $battle
     *
     * @return void
     */
    public function removeBattle(BaseBattle $battle): void
    {
        unset($this->battles[$battle->getId()]);
    }

    /**
     * @return array
     */
    public function getBattleQueues(): array
    {
        return $this->battleQueues;
    }

    /**
     * @param array $battleQueues
     */
    public function setBattleQueues(array $battleQueues): void
    {
        $this->battleQueues = $battleQueues;
    }

    /**
     * @param int $id
     * @return BattleQueue|null
     */
    public function getBattleQueueById(int $id): ?BattleQueue
    {
        return $this->battleQueues[$id] ?? null;
    }

    /**
     * @param BattleQueue $battleQueue
     * @return void
     */
    public function addBattleQueue(BattleQueue $battleQueue): void
    {
        $this->battleQueues[$battleQueue->getId()] = $battleQueue;
    }

    /**
     * @param BattleQueue $battleQueue
     * @return void
     */
    public function removeBattleQueue(BattleQueue $battleQueue): void
    {
        unset($this->battleQueues[$battleQueue->getId()]);
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array $users
     */
    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    /**
     * @param int $id
     *
     * @return \Server\Game\Models\User|null
     */
    public function getUsersById(int $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function addUser(User $user): void
    {
        $this->users[$user->getId()] = $user;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function removeUser(User $user): void
    {
        unset($this->users[$user->getId()]);
    }
}