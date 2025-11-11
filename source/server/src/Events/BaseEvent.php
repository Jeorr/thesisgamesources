<?php

declare(strict_types=1);

namespace Server\Events;

use Server\App;
use Server\Core\Client;
use Symfony\Contracts\EventDispatcher\Event;

class BaseEvent extends Event
{

    /**
     * @param  \Server\App  $app
     * @param  \Server\Core\Client  $client
     * @param  array  $data
     */
    public function __construct(protected App $app, protected Client $client, protected array $data)
    {
    }

    /**
     * @return \Server\App
     */
    public function getApp(): App
    {
        return $this->app;
    }

    /**
     * @return \Server\Core\Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

}