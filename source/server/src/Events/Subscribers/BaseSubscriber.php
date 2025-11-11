<?php

namespace Server\Events\Subscribers;

use Server\Events\BaseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class BaseSubscriber implements EventSubscriberInterface
{

    /**
     * @var BaseEvent
     */
    protected $event;

    public static function getSubscribedEvents()
    {
        return [];
    }

    public function handle(BaseEvent $event)
    {
        $this->event = $event;
    }

    abstract protected function triggerError(\Throwable $error, ?string $eventCode = null);
}