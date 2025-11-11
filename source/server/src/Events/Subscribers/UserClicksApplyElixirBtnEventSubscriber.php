<?php

namespace Server\Events\Subscribers;

use Psr\Log\LogLevel;
use Server\Events\BaseEvent;
use Server\Events\ClientEvent;
use Server\Game\Consts;
use Server\Game\Managers\PetManager;
use Server\Game\Managers\UserManager;
use Server\Game\Models\Elixir;
use Server\Game\Models\LootItem;
use Server\Game\Models\Pet;
use Server\Game\Models\Skill;
use Server\Game\Models\Structure;
use Server\Game\Utility\ElixirUtility;
use Server\Game\Utility\StructureUtility;
use Server\Game\Utility\TranslationsUtility;
use Server\Game\Utility\UnitUtility;
use Server\Game\Utility\UserUtility;

class UserClicksApplyElixirBtnEventSubscriber extends BaseSubscriber
{

    public static function getSubscribedEvents()
    {
        return [
            'UserClicksApplyElixirBtnEvent' => 'handle',
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
            $itemCode = $data['itemCode'] ?? null;
            $petCode = $data['petCode'] ?? null;
            $slot = $data['slot'] ?? null;

            $userItem = $user->getItem($itemCode);

            if (!$userItem) {
                throw new \Exception('Given item is missing in user backpack!');
            }

            $item = $app->getWorld()->getItems()[$itemCode] ?? null;
            $relatedElixirCode = $item->getElixirCode();
            /** @var Elixir $relatedElixir */
            $relatedElixir = App()->getWorld()->getElixirs()[$relatedElixirCode] ?? null;

            if (empty($relatedElixir)) {
                throw new \Exception('Invalid related elixir code for item ' . $item->getCode());
            }

            $structure = $app->getWorld()->getStructures()[$structureCode] ?? null;

            if ($structure->getLocationCode() !== $user->getLocationCode()) {
                throw new \Exception('Requested structure ' . $structureCode . ' is located in another location!');
            }

            $pet = $user->getSelectedPetByCode($petCode);

            if (empty($pet)) {
                throw new \Exception('Wrong pet code is given!', 556376650);
            }

            $slotMeta = ElixirUtility::getElixirSlotMeta($slot);
            if ($relatedElixir->getSlotType() !== $slotMeta['type']) {
                throw new \Exception('Wrong elixir slot type is given!', 556376650);
            }

            // @todo do not allow apply if pet already has this elixir in another slot

            // Apply elixir, remove elixir item from backpack
            $lootItem = new LootItem();
            $lootItem->setItemCode($itemCode);
            $lootItem->setAmount(1);
            \React\Promise\all([
                PetManager::applyElixirForSlot($pet, $relatedElixir, $slot),
                UserManager::removeUserItem($user, $lootItem),
            ])
                ->then(
                    function () use ($structure, $pet, $relatedElixir) {
                        $this->triggerSuccessResponse($structure, $pet, $relatedElixir);
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
    protected function triggerSuccessResponse(Structure $structure, Pet $pet, Elixir $elixir)
    {
        $client = $this->event->getClient();
        $user = $client->getUser();

        $client->triggerEvent(
            ClientEvent::PLAYER_APPLIES_ELIXIR,
            [
                'message' => TranslationsUtility::getTranslation(
                    'BARRACKS.APPLY_ELIXIR.SUCCESS_MESSAGE',
                    TranslationsUtility::TYPE_GENERAL,
                    (string)$user->getLang(),
                    [
                        TranslationsUtility::getTranslation($elixir->getName(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang())
                    ]
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
                'message' => 'Error: can not apply elixir!',
            ]
        );
    }
}