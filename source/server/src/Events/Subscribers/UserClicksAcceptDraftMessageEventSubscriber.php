<?php

namespace Server\Events\Subscribers;

use Psr\Log\LogLevel;
use Server\Core\Utility\CliUtility;
use Server\Events\BaseEvent;
use Server\Events\ClientEvent;
use Server\Game\Managers\ChatManager;
use Server\Game\Managers\UserManager;
use Server\Game\Models\Pet;
use Server\Game\Models\Structure;
use Server\Game\Models\UserChat;
use Server\Game\Models\UserChatMessage;
use Server\Game\Utility\StructureUtility;
use Server\Game\Utility\TranslationsUtility;

class UserClicksAcceptDraftMessageEventSubscriber extends BaseSubscriber
{

    public static function getSubscribedEvents()
    {
        return [
            'UserClicksAcceptDraftMessageEvent' => 'handle',
        ];
    }

    public function handle(BaseEvent $event)
    {
        parent::handle($event);

        $app = $event->getApp();
        $data = $event->getData();
        $user = $event->getClient()->getUser();
        $client = $user->getClient();

        try {
            $messageId = (int)($data['id'] ?: null);

            ChatManager::getMessageByIdAndCreator($messageId ,$user)
                ->then(
                    function (UserChatMessage $message) use ($user) {
                        $message->setHidden(false);
                        ChatManager::updateMessage($message)
                            ->then(
                                function (UserChatMessage $message) use ($user) {
                                    $this->triggerSuccessResponse($message);
                                },
                                function (\Throwable $error) {
                                    $this->triggerError($error);
                                }
                            )
                            ->otherwise(function (\Throwable $error) {
                                $this->triggerError($error);
                            })
                            ->done();
                    },
                    function (\Throwable $error) {
                        $this->triggerError($error);
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    $this->triggerError($error);
                })
                ->done();
        } catch (\Throwable $error) {
            $this->triggerError($error, ClientEvent::ERROR_GENERAL);
        }
    }

    /**
     * @param UserChatMessage $message
     * @return void
     */
    protected function triggerSuccessResponse(UserChatMessage $message)
    {
        $client = $this->event->getClient();
        $user = $client->getUser();

        $client->triggerEvent(
            ClientEvent::PLAYER_ACCEPTS_DRAFT_MESSAGE,
            [
                'message' => TranslationsUtility::getTranslation(
                    'CHAT.DRAFT_MESSAGE_ACCEPTED',
                    TranslationsUtility::TYPE_GENERAL,
                    (string)$user->getLang(),
                ),
                'messageData' => [
                    'id' => $message->getId(),
                    'hidden' => $message->getHidden()
                ]

            ]
        );
    }

    /**
     * @param \Throwable $error
     * @param string|null $eventCode
     *
     * @return void
     */
    protected function triggerError(\Throwable $error, ?string $eventCode = null)
    {
        $this->event->getApp()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);

        $client = $this->event->getClient();
        $client->triggerEvent(
            $eventCode ?: ClientEvent::ERROR_GENERAL,
            [
                'title' => '',
                'message' => 'Error: can not enter the building!',
            ]
        );
    }
}