<?php

declare(strict_types=1);

namespace Server\Game\Models;

/**
 * Class UserItem
 *
 * @package Server\Game\Models
 */
class UserItem extends BaseModel
{
    const DB_TABLE = 'g_items';

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $id;

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $userId;

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $amount;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $itemCode;

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
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param  int|null  $userId
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int|null $amount
     */
    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string|null
     */
    public function getItemCode(): ?string
    {
        return $this->itemCode;
    }

    /**
     * @param string|null $itemCode
     */
    public function setItemCode(?string $itemCode): void
    {
        $this->itemCode = $itemCode;
    }
}