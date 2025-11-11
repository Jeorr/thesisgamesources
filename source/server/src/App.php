<?php

namespace Server;

use Server\Core\Client;
use Server\Core\Db\Connection;
use Server\Core\Db\Migration;
use Server\Core\Exception\CommonException;
use Server\Core\GameData;
use Server\Core\Logger\Logger;
use Server\Core\Modifier;
use Server\Events\BaseEvent;
use Server\Events\EventDispatcherFactory;
use Server\Game\Initialization\WorldInitializer;
use Server\Game\Models\World;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Send any incoming messages to all connected clients (except sender)
 */
class App implements \Ratchet\WebSocket\MessageComponentInterface
{
    protected array $clients;
    protected Connection $connection;
    protected GameData\Provider $gameDataProvider;
    protected Modifier\Provider $modifiersProvider;
    protected EventDispatcher $eventsDispatcher;
    protected Logger $logger;
    protected ?World $world = null;

    public function __construct()
    {
        $this->clients = [];
        $this->connection = (new \Server\Core\Db\DbFactory())->createMysqlConnection();
        $this->eventsDispatcher = (new EventDispatcherFactory())->createEventsDispatcher();
        $this->gameDataProvider = new GameData\Provider();
        $this->modifiersProvider = new Modifier\Provider();
        // pass correct data or use factory that will do it by himself
        $this->logger = new Logger([]);
    }

    /**
     * @return \Server\Core\Db\Connection
     */
    public function getConnection(): Core\Db\Connection
    {
        return $this->connection;
    }

    /**
     * @return \Server\Core\GameData\Provider
     */
    public function getGameDataProvider(): GameData\Provider
    {
        return $this->gameDataProvider;
    }

    /**
     * @return \Server\Core\Modifier\Provider
     */
    public function getModifiersProvider(): Modifier\Provider
    {
        return $this->modifiersProvider;
    }

    /**
     * @return \Server\Core\Logger\Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return \Server\Game\Models\World
     */
    public function getWorld(): World
    {
        return $this->world;
    }

    /**
     * @param int $userId
     *
     * @return Client|null
     */
    public function getClientByUserId(int $userId): ?Client
    {
        /** @var Client $client */
        foreach ($this->clients as $client) {
            if ($client->getUser() && $client->getUser()->getId() === $userId) {
                return $client;
            }
        }

        return null;
    }

    /**
     * @return void
     */
    public function initApp(): void
    {
        $this->getConnection()
            ->onConnected()
            ->then(
                function () {
                    $this->getLogger()->simpleLog('DB connection is established!');
                    $migration = new Migration();

                    $migration->runMigrations()
                        ->then(
                            function () {
                                $this->getLogger()->simpleLog('Migrations are done!');

                                $initializer = new WorldInitializer($this);
                                $this->world = new World();
                                $initializer->initWorld($this->world);
                            },
                            function (\Throwable $error) {
                                $this->getLogger()->simpleLog('Could not run all migrations: ' . $error->getMessage());
                            }
                        )
                        ->otherwise(function (\Throwable $error) {
                            $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                            App()->getLogger()->logException($error);
                        })
                        ->done();
                }
            )
            ->done();

    }

    public function onOpen(\Ratchet\ConnectionInterface $conn)
    {
        $connectionKey = spl_object_id($conn);
        $client = new Client($connectionKey, $conn, $this);
        $this->clients[$connectionKey] = $client;
    }

    public function onMessage(\Ratchet\ConnectionInterface $conn, $msg)
    {
        $connectionKey = spl_object_id($conn);
        $client = $this->clients[$connectionKey] ?? null;

        try {
            $requestData = json_decode($msg->getPayload(), true);
        } catch (\Exception $e) {
            $requestData = [];
        }

        $event = new BaseEvent($this, $client, ($requestData['data'] ?? null) ?: []);
        $this->eventsDispatcher->dispatch($event, $requestData['event'] ?? '');
    }

    public function onClose(\Ratchet\ConnectionInterface $conn)
    {
        $connectionKey = spl_object_id($conn);
        unset($this->clients[$connectionKey]);
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param \Exception $e
     *
     * @return void
     */
    public function onError(\Ratchet\ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * @param Client $client
     *
     * @return void
     *
     * @throws \Exception
     */
    public function closeAllRelatedClients(Client $client){
        $user = $client->getUser();

        if (empty($user)) {
            throw new \Exception('closeAllRelatedClients method must be called only for Client with initialized user!');
        }

        /** @var Client $cl */
        foreach ($this->clients as $id => $cl) {
            if ($cl === $client) {
                continue;
            }
            if ($user->getId() !== $cl->getUser()?->getId()) {
                continue;
            }

            unset($this->clients[$id]);
        }
    }
}