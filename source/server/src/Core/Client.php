<?php

namespace Server\Core;

use Server\App;
use Server\Core\Factory\DbFactory;
use Server\Core\Factory\EventDispatcherFactory;
use Server\Game\Models\User;

/**
 *
 */
class Client
{
    /**
     * @var int
     */
    protected int $id;

    /**
     * @var \Ratchet\ConnectionInterface
     */
    protected \Ratchet\ConnectionInterface $connection;

    /**
     * @var App
     */
    protected App $app;

    /**
     * @var User|null
     */
    protected ?User $user = null;

    public function __construct(int $id, \Ratchet\ConnectionInterface $conn, App $app)
    {
        $this->id = $id;
        $this->connection = $conn;
        $this->app = $app;
    }

    /**
     * @return \Ratchet\ConnectionInterface
     */
    public function getConnection(): \Ratchet\ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @param  array  $data
     *
     * @return \Ratchet\ConnectionInterface
     */
    public function triggerEvent(string $event, array $data)
    {
        $conn = $this->getConnection();

        // connection is closed already (e.g. user reload the app)
        // try to find another active connection for the given user
        if (!empty($conn->WebSocket->closing)) {
            $currentClient = $this->app->getClientByUserId($this->user->getId());

            $currentClient?->triggerEvent($event, $data);
            return;
        }

        $response = [
            'eventType' => $event,
            'data' => $data
        ];

        $newMessage = new \Ratchet\RFC6455\Messaging\Message();
        $newMessage->addFrame(new \Ratchet\RFC6455\Messaging\Frame(json_encode($response)));
        $this->connection->send($newMessage);
    }
}