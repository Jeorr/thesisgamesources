<?php

declare(strict_types=1);

namespace Server\Game\Models;

use Server\Core\Client;
use Server\Core\Utility\StringUtility;
use Server\Game\Battle\BaseBattle;
use Server\Game\Battle\BattleQueue;
use Server\Game\Battle\BattleUnit;
use Server\Game\Utility\UserUtility;

/**
 *
 */
class User extends BaseModel
{

    const DB_TABLE = 'g_users';

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $id;

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $wpId;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $username;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $email;

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $currency1;

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $currency2;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $selectedPetsCodes;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $locationCode;

    /**
     * @var array|null
     */
    #[DbField]
    protected ?array $settings;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $avatar;

    /**
     * @var \Server\Core\Client|null
     */
    protected ?Client $client = null;

    /**
     * @var array|null
     */
    protected ?array $selectedPets = null;

    /**
     * @var \Server\Game\Battle\BattleUnit|null
     */
    protected ?BattleUnit $currentBattlePet = null;

    /**
     * @var \Server\Game\Battle\BaseBattle|null
     */
    protected ?BaseBattle $currentBattle = null;

    /**
     * @var BattleQueue|null
     */
    protected ?BattleQueue $currentBattleQueue = null;

    /**
     * @var \Server\Game\Models\Location|null
     */
    protected ?Location $location;

    /**
     * @var array
     */
    protected array $searchedNpcCodes = [];

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @var UserItem[]
     */
    protected array $items = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param  int|null  $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getWpId(): ?int
    {
        return $this->wpId;
    }

    /**
     * @param  int|null  $wpId
     */
    public function setWpId(?int $wpId): void
    {
        $this->wpId = $wpId;
    }

    /**
     * @return \Server\Core\Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param  \Server\Core\Client|null  $client
     */
    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return string|null
     */
    public function getSelectedPetsCodes(): ?string
    {
        return $this->selectedPetsCodes;
    }

    /**
     * @param  string|null  $selectedPetsCodes
     */
    public function setSelectedPetsCodes(?string $selectedPetsCodes): void
    {
        $this->selectedPetsCodes = $selectedPetsCodes;
    }

    /**
     * @return \Server\Game\Models\Pet[]|null
     */
    public function getSelectedPets(): ?array
    {
        return $this->selectedPets;
    }

    /**
     * @param  \Server\Game\Models\Pet  $pet
     *
     * @return void
     */
    public function addSelectedPet(Pet $pet): void
    {
        $this->selectedPets[$pet->getUnitCode()] = $pet;
        $this->setSelectedPetsCodes(implode(',', array_keys($this->selectedPets)));
    }

    /**
     * @param  array|null  $selectedPets
     */
    public function setSelectedPets(?array $selectedPets): void
    {
        $this->selectedPets = $selectedPets;
        $this->setSelectedPetsCodes(implode(',', array_keys($this->selectedPets)));
    }

    /**
     * @param  string  $code
     *
     * @return \Server\Game\Models\Pet|null
     */
    public function getSelectedPetByCode(string $code): ?Pet
    {
        return $this->selectedPets[$code] ?? null;
    }

    /**
     * @param int $order
     * @return Pet|null
     */
    public function getSelectedPetByOrder(int $order): ?Pet
    {
        $selectedPetCodes = StringUtility::trimExplode((string)$this->getSelectedPetsCodes());
        if (empty($selectedPetCodes) || empty($requiredPetCode = $selectedPetCodes[$order])) {
            return null;
        }

        return $this->selectedPets[$requiredPetCode] ?? null;
    }

    /**
     * @return \Server\Game\Battle\BattleUnit|null
     */
    public function getCurrentBattlePet(): ?BattleUnit
    {
        return $this->currentBattlePet;
    }

    /**
     * @param  \Server\Game\Battle\BattleUnit|null  $currentBattlePet
     */
    public function setCurrentBattlePet(?BattleUnit $currentBattlePet): void
    {
        $this->currentBattlePet = $currentBattlePet;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username ?: 'ID: ' . $this->getId();
    }

    /**
     * @param  string  $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param  string|null  $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return int|null
     */
    public function getCurrency1(): ?int
    {
        return $this->currency1;
    }

    /**
     * @param  int|null  $currency1
     */
    public function setCurrency1(?int $currency1): void
    {
        $this->currency1 = $currency1;
    }

    /**
     * @return int|null
     */
    public function getCurrency2(): ?int
    {
        return $this->currency2;
    }

    /**
     * @param  int|null  $currency2
     */
    public function setCurrency2(?int $currency2): void
    {
        $this->currency2 = $currency2;
    }

    /**
     * @return \Server\Game\Battle\BaseBattle|null
     */
    public function getCurrentBattle(): ?BaseBattle
    {
        return $this->currentBattle;
    }

    /**
     * @param  \Server\Game\Battle\BaseBattle|null  $currentBattle
     */
    public function setCurrentBattle(?BaseBattle $currentBattle): void
    {
        $this->currentBattle = $currentBattle;
    }

    /**
     * @return BattleQueue|null
     */
    public function getCurrentBattleQueue(): ?BattleQueue
    {
        return $this->currentBattleQueue;
    }

    /**
     * @param BattleQueue|null $currentBattleQueue
     */
    public function setCurrentBattleQueue(?BattleQueue $currentBattleQueue): void
    {
        $this->currentBattleQueue = $currentBattleQueue;
    }

    /**
     * @return string|null
     */
    public function getLocationCode(): ?string
    {
        return $this->locationCode;
    }

    /**
     * @param  string|null  $locationCode
     */
    public function setLocationCode(?string $locationCode): void
    {
        $this->locationCode = $locationCode;
    }

    /**
     * @return \Server\Game\Models\Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param  \Server\Game\Models\Location|null  $location
     */
    public function setLocation(?Location $location): void
    {
        $this->location = $location;
        $this->setLocationCode($location->getCode());
    }

    /**
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * @param array|null $settings
     */
    public function setSettings(?array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return bool
     */
    public function isBusy(): bool
    {
        return UserUtility::isUserBusy($this);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param  string  $key
     *
     * @return mixed|null
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * @param  array  $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @param  string  $key
     * @param $value
     *
     * @return void
     */
    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @param  string  $key
     *
     * @return void
     */
    public function unsetAttribute(string $key): void
    {
        unset($this->attributes[$key]);
    }

    /**
     * @return array
     */
    public function getSearchedNpcCodes(): array
    {
        return $this->searchedNpcCodes;
    }

    /**
     * @param array $searchedNpcCodes
     */
    public function setSearchedNpcCodes(array $searchedNpcCodes): void
    {
        $this->searchedNpcCodes = $searchedNpcCodes;
    }

    /**
     * @param string $npcCode
     *
     * @return int
     */
    public function getSearchedNpcCodeId(string $npcCode): int
    {
        return (int)($this->searchedNpcCodes[$npcCode] ?? 0);
    }

    /**
     * @param string $npcCode
     *
     * @return int
     */
    public function addSearchedNpcCode(string $npcCode): int
    {
        if (empty($this->searchedNpcCodes[$npcCode])) {
            $this->searchedNpcCodes[$npcCode] = 0;
        }
        $this->searchedNpcCodes[$npcCode]++;

        return $this->searchedNpcCodes[$npcCode];
    }

    /**
     * @param string $npcCode
     *
     * @return void
     */
    public function removeSearchedNpcCode(string $npcCode): void
    {
        unset($this->searchedNpcCodes[$npcCode]);
    }

    /**
     * @return UserItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @param string $key
     * @return UserItem|null
     */
    public function getItem(string $key): ?UserItem
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @param UserItem $item
     * @return void
     */
    public function addItem(UserItem $item): void
    {
        if ($curItem = $this->getItem($item->getItemCode())) {
            $curItem->setAmount($curItem->getAmount() + $item->getAmount());
        } else {
            $this->items[$item->getItemCode()] ??= $item;
        }
    }

    /**
     * @param string $key
     * @return void
     */
    public function removeItem(string $key): void
    {
        unset($this->items[$key]);
    }

    /**
     * @return string|null
     */
    public function getLang(): ?string
    {
        $settings = $this->getSettings();
        return $settings['language'] ?? 'en';
    }
}