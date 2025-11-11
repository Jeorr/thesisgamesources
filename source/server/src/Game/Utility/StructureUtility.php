<?php

declare(strict_types=1);

namespace Server\Game\Utility;

use Server\Game\Models\ShopItem;
use Server\Game\Models\Structure;
use Server\Game\Models\User;

/**
 * Class StructureUtility
 *
 * @package Server\Game\Utility
 */
class StructureUtility
{
    /**
     * @param Structure $structure
     * @return array
     * @throws \Exception
     */
    public static function prepareShopStructureDataForResponse(Structure $structure, User $user): array
    {
        $shopData = [
            'name' => TranslationsUtility::getTranslation($structure->getName(), TranslationsUtility::TYPE_STRUCTURES, (string)$user->getLang()),
            'code' => $structure->getCode(),
        ];

        /** @var ShopItem $shopItem */
        foreach ($structure->getShopItems() as $shopItem) {
            if (!$shopItem->getIsVisible()) {
                continue;
            }
            /** @var \Server\Game\Models\Item $relatedItem */
            $relatedItem = App()->getWorld()->getItems()[$shopItem->getItemCode()] ?? null;
            $shopData['items'][] = [
                'code' => $shopItem->getItemCode(),
                'icon' => $relatedItem->getIcon(),
                'type' => $relatedItem->getType(),
                'cost' => $shopItem->getCost(),
                'rarity' => $relatedItem->getRarity(),
                'amount' => $shopItem->getAmount() > 100 ? 100 : $shopItem->getAmount(),
                'description' => TranslationsUtility::getTranslation($relatedItem->getDescription(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang()),
                'name' => TranslationsUtility::getTranslation($relatedItem->getName(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang()),
            ];
        }

        return $shopData;
    }

    /**
     * @param Structure $structure
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public static function prepareElixirLabStructureDataForResponse(Structure $structure, User $user): array
    {
        $elixirLabData = [
            'name' => TranslationsUtility::getTranslation($structure->getName(), TranslationsUtility::TYPE_STRUCTURES, (string)$user->getLang()),
            'code' => $structure->getCode(),
        ];

        $recipes = [];
        foreach ($user->getItems() as $userItem) {
            /** @var \Server\Game\Models\Item $itemData */
            $itemData = App()->getWorld()->getItems()[$userItem->getItemCode()] ?? null;
            if ($itemData && $itemData->getType() === \Server\Game\Consts\Item::ITEM_TYPE_RECIPE) {
                $recipeIngredients = $itemData->getIngredients();
                $ingredients = [];

                foreach ($recipeIngredients as $itemCode => $amount) {
                    /** @var \Server\Game\Models\Item $ingredientData */
                    $ingredientData = App()->getWorld()->getItems()[$itemCode] ?? null;
                    $existingIngredient = $user->getItem($itemCode);

                    $ingredients[] = [
                        'code' => $ingredientData->getCode(),
                        'icon' => $ingredientData->getIcon(),
                        'name' => TranslationsUtility::getTranslation($ingredientData->getName(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang()),
                        'description' => TranslationsUtility::getTranslation($ingredientData->getDescription(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang()),
                        'rarity' => $ingredientData->getRarity(),
                        'needed' => $amount,
                        'exists' => $existingIngredient ? $existingIngredient->getAmount() : 0,
                    ];
                }

                $recipes[] = [
                    'code' => $userItem->getItemCode(),
                    'icon' => $itemData->getIcon(),
                    'cost' => $itemData->getCraftingCost(),
                    'name' => TranslationsUtility::getTranslation($itemData->getName(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang()),
                    'description' => TranslationsUtility::getTranslation($itemData->getDescription(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang()),
                    'type' => 'resource',
                    'rarity' => $itemData->getRarity(),
                    'ingredients' => $ingredients,
                ];
            }
        }
        $elixirLabData['items'] = $recipes;
        
        return $elixirLabData;
    }

    public static function prepareBarracksStructureDataForResponse(Structure $structure, User $user): array
    {
        $barracksData = [
            'name' => TranslationsUtility::getTranslation($structure->getName(), TranslationsUtility::TYPE_STRUCTURES, (string)$user->getLang()),
            'code' => $structure->getCode(),
        ];

        $selectedPets = $user->getSelectedPets();
        $selectedPetsData = [];


        foreach ($selectedPets as $selectedPet) {
            $selectedPetsData[] = UnitUtility::preparePetBasicDataForResponse($selectedPet, $user);
        }

        $barracksData['petsData'] = $selectedPetsData;

        return $barracksData;
    }
}