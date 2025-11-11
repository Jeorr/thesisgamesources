<?php

declare(strict_types=1);

namespace Server\Game\Models;

/**
 * Class UserChat
 *
 * @package Server\Game\Models
 */
class UserChat extends BaseModel
{
    const DB_TABLE = 'g_chat';

    const CHAT_TYPE_SYSTEM = 'system';
    const CHAT_TYPE_GUILD = 'guild';

    /**
     * @var int|null
     */
    protected ?int $userId;

    /**
     * @var string
     */
    protected string $type = '';

    /**
     * @var UserChatMessage[]
     */
    protected array $messages = [];

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return UserChatMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param UserChatMessage[] $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @param UserChatMessage $message
     * @return void
     */
    public function addMessage(UserChatMessage $message): void {
        $this->messages[] = $message;
    }
}