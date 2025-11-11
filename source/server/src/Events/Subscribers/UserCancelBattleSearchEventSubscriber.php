<?php

namespace Server\Events\Subscribers;

use Psr\Log\LogLevel;
use Server\Events\BaseEvent;
use Server\Events\ClientEvent;
use Server\Game\Models\Pet;
use Server\Game\Models\User;
use Server\Game\Utility\TranslationsUtility;
use Server\Game\Utility\UserUtility;

class UserCancelBattleSearchEventSubscriber extends BaseSubscriber
{

    public static function getSubscribedEvents()
    {
        return [
            'UserCancelBattleSearchEvent' => 'handle',
        ];
    }

    public function handle(BaseEvent $event)
    {
        parent::handle($event);

        $app = $event->getApp();
        $user = $this->event->getClient()->getUser();
        $data = $event->getData();
        $world = $app->getWorld();

        try {
            if (empty($currentBattleQueue = $user->getCurrentBattleQueue())) {
                $this->triggerSuccessResponse($user);
            }

            $team1 = $currentBattleQueue->getTeam1();
            $team2 = $currentBattleQueue->getTeam2();

            foreach ([$team1, $team2] as $team) {
                foreach ($team->getMembers() as $member) {
                    if ($member->getUnit() instanceof Pet && $member->getUnit()->getUserId() === $user->getId()) {
                        $team->removeMember($member);
                    }
                }
            }

            $user->setCurrentBattleQueue(null);
            $this->triggerSuccessResponse($user);
        } catch (\Throwable $error) {
            $errorType = ClientEvent::ERROR_GENERAL;

            $this->triggerError($error, $errorType);
        }
    }

    /**
     * @param User $user
     * @return void
     */
    protected function triggerSuccessResponse(User $user)
    {
        $client = $this->event->getClient();
        $client->triggerEvent(
            ClientEvent::PLAYER_CANCELS_BATTLE_SEARCH,
            [
                'userStatus' => UserUtility::prepareUserStatusForResponse($user),
                'message' => TranslationsUtility::getTranslation('ARENA.BATTLE_CANCELED_MESSAGE.NOT_ENOUGH_ENEMIES', TranslationsUtility::TYPE_GENERAL, (string)$user->getLang()),
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
                'message' => 'Error during battle creation!',
            ]
        );
    }
}