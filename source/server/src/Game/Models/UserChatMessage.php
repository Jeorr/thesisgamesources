<?php

declare(strict_types=1);

namespace Server\Game\Models;

/**
 * Class UserChatMessage
 *
 * @package Server\Game\Models
 */
class UserChatMessage extends BaseModel
{

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
    protected ?int $creatorId;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $type;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $author;

    /**
     * @var string|null
     */
    #[DbField]
    protected ?string $message;

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $timestamp;

    /**
     * @var bool|null
     */
    #[DbField]
    protected ?bool $isViewed;

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $attachmentsCurrency1;

    /**
     * @var int|null
     */
    #[DbField]
    protected ?int $attachmentsCurrency2;

    /**
     * @var array|null
     */
    #[DbField]
    protected ?array $attachmentsItems;

    /**
     * @var bool|null
     */
    #[DbField]
    protected ?bool $deleted;

    /**
     * @var bool|null
     */
    #[DbField]
    protected ?bool $hidden;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
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
     * @param int|null $userId
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int|null
     */
    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    /**
     * @param int|null $creatorId
     */
    public function setCreatorId(?int $creatorId): void
    {
        $this->creatorId = $creatorId;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     */
    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * @param int|null $timestamp
     */
    public function setTimestamp(?int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return bool|null
     */
    public function getIsViewed(): ?bool
    {
        return $this->isViewed;
    }

    /**
     * @param bool|null $isViewed
     */
    public function setIsViewed(?bool $isViewed): void
    {
        $this->isViewed = $isViewed;
    }

    /**
     * @return int|null
     */
    public function getAttachmentsCurrency1(): ?int
    {
        return $this->attachmentsCurrency1;
    }

    /**
     * @param int|null $attachmentsCurrency1
     */
    public function setAttachmentsCurrency1(?int $attachmentsCurrency1): void
    {
        $this->attachmentsCurrency1 = $attachmentsCurrency1;
    }

    /**
     * @return int|null
     */
    public function getAttachmentsCurrency2(): ?int
    {
        return $this->attachmentsCurrency2;
    }

    /**
     * @param int|null $attachmentsCurrency2
     */
    public function setAttachmentsCurrency2(?int $attachmentsCurrency2): void
    {
        $this->attachmentsCurrency2 = $attachmentsCurrency2;
    }

    /**
     * @return array|null
     */
    public function getAttachmentsItems(): ?array
    {
        return $this->attachmentsItems;
    }

    /**
     * @param array|null $attachmentsItems
     */
    public function setAttachmentsItems(?array $attachmentsItems): void
    {
        $this->attachmentsItems = $attachmentsItems;
    }

    /**
     * @return bool|null
     */
    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    /**
     * @param bool|null $deleted
     */
    public function setDeleted(?bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @return bool|null
     */
    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    /**
     * @param bool|null $hidden
     */
    public function setHidden(?bool $hidden): void
    {
        $this->hidden = $hidden;
    }
}