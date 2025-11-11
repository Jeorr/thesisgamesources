<?php

namespace Server\Events\Subscribers;

use Server\Core\Utility\CliUtility;
use Server\Events\BaseEvent;
use Server\Events\ClientEvent;

class UpdateCliDataEventSubscriber extends BaseSubscriber
{

    public static function getSubscribedEvents()
    {
        return [
            'UpdateCliDataEvent' => 'handle',
        ];
    }

    public function handle(BaseEvent $event)
    {
        parent::handle($event);

        $app = $event->getApp();
        $data = $event->getData();
        $user = $this->event->getClient()->getUser();
        $cliIsAllowedForCurrentUser = true;

        //@todo check if is admin

        if ($cliIsAllowedForCurrentUser) {
            $this->triggerSuccessResponse();
        } else {
            $this->triggerError(new \Exception('Cli is not allowed for the given user'), ClientEvent::ERROR_GENERAL);
        }
    }

    /**
     * @param \Throwable $error
     * @param string|null $eventCode
     *
     * @return void
     */
    protected function triggerError(\Throwable $error, ?string $eventCode = null)
    {
        $client = $this->event->getClient();
        $client->triggerEvent(
            $eventCode ?: ClientEvent::ERROR_GENERAL,
            [
                'title' => '',
                'message' => 'Operation is not permitted!',
            ]
        );
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    protected function triggerSuccessResponse()
    {
        $client = $this->event->getClient();

        $client->triggerEvent(
            ClientEvent::UPDATE_CLI,
            [
                'serverInfo' => [
                    'PHP_VERSION' => phpversion(),
                    'USED_MEMORY' => CliUtility::bytesToHumanReadableSize(memory_get_usage()),
                    'PEAK_USED_MEMORY' => CliUtility::bytesToHumanReadableSize(memory_get_peak_usage())
                ]
            ]
        );
    }

}