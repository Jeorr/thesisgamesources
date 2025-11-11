<?php

namespace Server\Events\Subscribers;

use Psr\Log\LogLevel;
use Server\Events\BaseEvent;
use Server\Events\ClientEvent;
use Server\Game\Consts;
use Server\Game\Managers\PetManager;
use Server\Game\Models\Pet;
use Server\Game\Models\Skill;
use Server\Game\Models\Structure;
use Server\Game\Utility\TranslationsUtility;
use Server\Game\Utility\UnitUtility;

class UserChangesPetSkillPositionEventSubscriber extends BaseSubscriber
{

    public static function getSubscribedEvents()
    {
        return [
            'UserChangesPetSkillPositionEvent' => 'handle',
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
            $petCode = $data['petCode'] ?? null;
            $skillCode = $data['skillCode'] ?? null;
            $direction = $data['direction'] ?? 'up';

            $structure = $app->getWorld()->getStructures()[$structureCode] ?? null;

            if ($structure->getLocationCode() !== $user->getLocationCode()) {
                throw new \Exception('Requested structure ' . $structureCode . ' is located in another location!');
            }

            $pet = $user->getSelectedPetByCode($petCode);

            if (empty($pet)) {
                throw new \Exception('Wrong pet code is given!', 556376650);
            }

            $currentSkill = $pet->getSkills()[$skillCode] ?? null;

            if (empty($currentSkill)) {
                throw new \Exception('Wrong skill code is given!', 556376650);
            }
            
            // add missing skills to sorted list
            $sortedSkillCodes = $pet->getSortedSkillCodes();
            /** @var Skill $skill */
            foreach ($pet->getSkills() as $skill) {
                if (!in_array($skill->getCode(), $sortedSkillCodes)) {
                    $sortedSkillCodes[] = $skill->getCode();
                }
            }

            // Find current index of skill code
            $currentIndex = array_search($skillCode, $sortedSkillCodes);

            if ($currentIndex !== false) {
                if ($direction === 'up' && $currentIndex > 0) {
                    // Move skill up by swapping with previous element
                    $temp = $sortedSkillCodes[$currentIndex - 1];
                    $sortedSkillCodes[$currentIndex - 1] = $sortedSkillCodes[$currentIndex];
                    $sortedSkillCodes[$currentIndex] = $temp;
                } elseif ($direction === 'down' && $currentIndex < count($sortedSkillCodes) - 1) {
                    // Move skill down by swapping with next element
                    $temp = $sortedSkillCodes[$currentIndex + 1];
                    $sortedSkillCodes[$currentIndex + 1] = $sortedSkillCodes[$currentIndex];
                    $sortedSkillCodes[$currentIndex] = $temp;
                }
            }

            // Update pet's sorted skill codes
            PetManager::updatePetSortedSkillCodes($pet, $sortedSkillCodes)
                ->then(
                    function ($pet) use($structure) {
                        $this->triggerSuccessResponse($structure, $pet);
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
     * @param Structure $structure
     * @param Pet $pet
     * @return void
     * @throws \Exception
     */
    protected function triggerSuccessResponse(Structure $structure, Pet $pet)
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