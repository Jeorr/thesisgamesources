<?php

declare(strict_types=1);

namespace Server\Game\UseFunctions;

use React\Promise\PromiseInterface;
use Server\Game\Managers\PetManager;
use Server\Game\Managers\UserManager;
use Server\Game\Models\LootItem;
use Server\Game\Models\User;
use Server\Game\Models\UserItem;
use Server\Game\Utility\TranslationsUtility;

/**
 * Class ResetTalentsBookUseFunction
 *
 * @package Server\Game\UseFunctions
 */
class ResetTalentsBookUseFunction implements UseFunctionInterface
{
    public function use(...$args): PromiseInterface
    {
        $deferred = new \React\Promise\Deferred();

        try {
            /** @var User $user */
            $user = $args['user'];
            /** @var UserItem $userItem */
            $userItem = $args['userItem'];
            /** @var int $quantity */
            $quantity = $args['quantity'];

            $userPet = $user->getSelectedPetByOrder(0);

            $lootItem = new LootItem();
            $lootItem->setItemCode($userItem->getItemCode());
            $lootItem->setAmount($quantity);
            \React\Promise\all([
                PetManager::resetTalents($userPet),
                UserManager::removeUserItem($user, $lootItem),
            ])
                ->then(
                    function () use ($user, $deferred) {
                        $useResults = [
                            'message' => TranslationsUtility::getTranslation(
                                'BOOK_OF_TALENTS_RESET_USE_RESULTS',
                                TranslationsUtility::TYPE_ITEMS,
                                (string)$user->getLang(),
                            ),
                        ];
                        $deferred->resolve($useResults);
                    },
                    function (\Throwable $error) use ($deferred) {
                        $deferred->reject($error);
                    }
                )
                ->otherwise(function (\Throwable $error) use ($deferred) {
                    $deferred->reject($error);
                })
                ->done();
        } catch (\Throwable $error) {
            $deferred->reject($error);
        }

        return $deferred->promise();
    }
}