<?php

declare(strict_types=1);

namespace Server\Events;

use Server\Events\Subscribers\UserInitGameEventSubscriber;
use Server\Events\Subscribers\UserRequestsOwnPetFullInfoEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 *
 */
class EventDispatcherFactory
{

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function createEventsDispatcher(): EventDispatcher
    {
        $eventsDispatcher = new EventDispatcher();

        $subscribersList = [];
        $iterator = new \FilesystemIterator(__DIR__ . '/Subscribers');

        /** @var \SplFileInfo $entry */
        foreach ($iterator as $entry) {
            $className = $entry->getBasename('.php');

            if ($className == 'BaseSubscriber') {
                continue;
            }

            $subscribersList[] = '\\Server\\Events\\Subscribers\\' . $className;
        }

        foreach ($subscribersList as $subscriberClass) {
            $subscriber = new $subscriberClass();
            $eventsDispatcher->addSubscriber($subscriber);
        }

        return $eventsDispatcher;
    }

}