<?php

declare(strict_types=1);

namespace Server\Core\Curl;

use KHR\React\Curl\Exception;

/**
 * Class Curl
 *
 * @package Server\Core\Curl
 */
class Curl extends \KHR\React\Curl\Curl
{

    public function run()
    {
        $client = $this->client;
        $client->run();

        while ($client->has()) {
            /**
             * @var Result $result
             */
            $result = $client->next();
            $deferred = $result->shiftDeferred();

            if (!$result->hasError()) {
                $deferred->resolve($result);
            } else {
                $deferred->reject(new Exception($result));
            }
        }

        if (!isset($this->loop_timer)) {
            $this->loop_timer = $this->loop->addPeriodicTimer($this->timeout, function () {
                $this->run();
                if (!($this->client->run() || $this->client->has())) {
                    $this->loop->cancelTimer($this->loop_timer);
                    $this->loop_timer = null;
                }
            });
        }
    }

}
