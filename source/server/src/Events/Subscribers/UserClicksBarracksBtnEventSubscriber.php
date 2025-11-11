<?php

namespace Server\Events\Subscribers;

use Psr\Log\LogLevel;
use Server\Events\BaseEvent;
use Server\Events\ClientEvent;
use Server\Game\Managers\UserManager;
use Server\Game\Models\LootItem;
use Server\Game\Models\Pet;
use Server\Game\Models\ShopItem;
use Server\Game\Models\Structure;
use Server\Game\Models\User;
use Server\Game\Utility\StructureUtility;
use Server\Game\Utility\TranslationsUtility;
use Server\Game\Utility\UnitUtility;
use Server\Game\Utility\UserUtility;

class UserClicksBarracksBtnEventSubscriber extends BaseSubscriber
{

    public static function getSubscribedEvents()
    {
        return [
            'UserClicksBarracksBtnEvent' => 'handle',
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
            $structureCode = $data['structureCode'] ?? null;
            $btnType = $data['type'] ?? null;
            $petCode = $data['petCode'] ?? null;

            $structure = $app->getWorld()->getStructures()[$structureCode] ?? null;

            if ($structure->getLocationCode() !== $user->getLocationCode()) {
                throw new \Exception('Requested structure ' . $structureCode . ' is located in another location!');
            }

            $pet = $user->getSelectedPetByCode($petCode);

            if (empty($pet)) {
                throw new \Exception('Wrong pet code is given!', 556376650);
            }

            if ($btnType === 'stats') {
                $this->triggerPetStatsResponse($structure, $pet);
            } elseif ($btnType === 'skills') {
                $this->triggerPetSkillsResponse($structure, $pet);
            } elseif ($btnType === 'elixirs') {
                $this->triggerPetElixirsResponse($structure, $pet);
            } elseif ($btnType === 'talents') {
                $this->triggerPetTalentsResponse($structure, $pet);
            }
        } catch (\Throwable $error) {
            $this->triggerError($error, ClientEvent::ERROR_GENERAL);
        }
    }

    /**
     * @param Pet $pet
     * @return void
     * @throws \Exception
     */
    protected function triggerPetStatsResponse(Structure $structure, Pet $pet)
    {
        $client = $this->event->getClient();
        $user = $client->getUser();
        $response = UnitUtility::preparePetCalculatedDataForResponse($pet, $user);
        $response['structureCode'] = $structure->getCode();

        $client->triggerEvent(
            ClientEvent::PLAYER_OPENS_BARRACKS_INFO_WINDOW_STATS,
            $response
        );
    }

    protected function triggerPetSkillsResponse(Structure $structure, Pet $pet)
    {
        $client = $this->event->getClient();
        $user = $client->getUser();
        $response = UnitUtility::preparePetSkillsDataForResponse($pet, $user);
        $response['structureCode'] = $structure->getCode();

        $client->triggerEvent(
            ClientEvent::PLAYER_OPENS_BARRACKS_INFO_WINDOW_SKILLS,
            $response
        );
    }

    protected function triggerPetElixirsResponse(Structure $structure, Pet $pet)
    {
        $client = $this->event->getClient();
        $user = $client->getUser();
        $response = UnitUtility::preparePetElixirsDataForResponse($pet, $user);
        $response['structureCode'] = $structure->getCode();

        $client->triggerEvent(
            ClientEvent::PLAYER_OPENS_BARRACKS_INFO_WINDOW_ELIXIRS,
            $response
        );
    }

    protected function triggerPetTalentsResponse(Structure $structure, Pet $pet)
    {
        $client = $this->event->getClient();
        $user = $client->getUser();
        $response = UnitUtility::preparePetTalentsDataForResponse($pet, $user);
        $response['structureCode'] = $structure->getCode();

        $client->triggerEvent(
            ClientEvent::PLAYER_OPENS_BARRACKS_INFO_WINDOW_TALENTS,
            $response
        );
    }

    /**
     * @param \Throwable $error
     * @return void
     * @throws \Exception
     */
    protected function triggerFlashMessage(\Throwable $error)
    {
        $client = $this->event->getClient();
        $user = $client->getUser();

        $client->triggerEvent(
            ClientEvent::DISPLAY_FLASH_MESSAGE,
            [
                'title' => '',
                'message' => TranslationsUtility::getTranslation(
                    $error->getMessage(),
                    TranslationsUtility::TYPE_GENERAL,
                    (string)$user->getLang()
                ),
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
                'message' => 'Error: can not buy the item!',
            ]
        );
    }
}