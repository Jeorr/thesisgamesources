<?php

declare(strict_types=1);

namespace Server\Game\Initialization;

use Psr\Log\LogLevel;
use React\Promise\Promise;
use Server\App;
use Server\Core\GameData\DataType;
use Server\Game\Consts\Skill;
use Server\Game\Managers\ItemManager;
use Server\Game\Managers\StructureManager;
use Server\Game\Models\Buff;
use Server\Game\Models\Chapter;
use Server\Game\Models\Factories\BuffFactory;
use Server\Game\Models\Factories\ElixirFactory;
use Server\Game\Models\Factories\ItemFactory;
use Server\Game\Models\Factories\SkillFactory;
use Server\Game\Models\Factories\StructureFactory;
use Server\Game\Models\Factories\TalentFactory;
use Server\Game\Models\Factories\UnitFactory;
use Server\Game\Models\Location;
use Server\Game\Models\ShopItem;
use Server\Game\Models\Structure;
use Server\Game\Models\Talent;
use Server\Game\Models\Territory;
use Server\Game\Models\World;

/**
 * Class WorldInitializer
 *
 * @package Server\Core\Initialization
 */
class WorldInitializer
{

    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function initWorld($world): World
    {
        $worldPromises = [];
        $worldPromises[] = $this->initTerritories($world)
            ->then(function () use ($world) {
                return $this->initLocations($world);
            })
            ->then(function() use ($world) {
                return $this->initItems($world);
            })
            ->then(function() use ($world) {
                return $this->initBuffs($world);
            })
            ->then(function() use ($world) {
                return $this->initElixirs($world);
            })
            ->then(function() use ($world) {
                return $this->initSkills($world);
            })
            ->then(function() use ($world) {
                return $this->initTalents($world);
            })
            ->then(function() use ($world) {
                return $this->initNpcs($world);
            })
            ->then(function() use ($world) {
                return $this->initStructures($world);
            })
            ->then(function() use ($world) {
                return $this->initChapter($world);
            })
        ;

        \React\Promise\all($worldPromises)
            ->then(
                function () {
                    $this->app->getLogger()->simpleLog('World initialized!');
                }
            )
            ->otherwise(function (\Throwable $error) {
                App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
            })
            ->done();

        return $world;
    }

    protected function initTerritories(World $world)
    {
        $deferred = new \React\Promise\Deferred();
        $territoriesCodes = $this->app->getGameDataProvider()->getKeys(DataType::Territories);
        $promises = [];

        foreach ($territoriesCodes as $territoryCode) {
            $promises[$territoryCode] =
                $this->app->getGameDataProvider()->getData(DataType::Territories, $territoryCode);
        }

        \React\Promise\all($promises)
            ->then(
                function (array $territoriesData) use ($world, $deferred) {
                    foreach ($territoriesData as $territoryCode => $territoryData) {
                        $territory = new Territory($territoryData);
                        $world->addTerritory($territory);
                    }
                    $this->app->getLogger()->simpleLog('Territories initialized!');
                    $deferred->resolve();
                }
            )
            ->otherwise(function (\Throwable $error) {
                App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
            })
            ->done();

        return $deferred->promise();
    }

    /**
     * @param   \Server\Game\Models\World  $world
     *
     * @return \React\Promise\Promise
     * @throws \Exception
     */
    protected function initLocations(World $world)
    {
        $deferred = new \React\Promise\Deferred();

        $locationsCodes = $this->app->getGameDataProvider()->getKeys(DataType::Locations);

        $promises = [];

        foreach ($locationsCodes as $locationCode) {
            $promises[$locationCode] = $this->app->getGameDataProvider()->getData(DataType::Locations, $locationCode);
        }

        \React\Promise\all($promises)
            ->then(
                function (array $locationsData) use ($world, $deferred) {
                    foreach ($locationsData as $locationCode => $locationData) {
                        $location = new Location($locationData);
                        $territoryCode = $location->getTerritoryCode();
                        /** @var Territory $relatedTerritorry */
                        $relatedTerritory = $world->getTerritories()[$territoryCode] ?? null;

                        if (empty($relatedTerritory)) {
                            throw new \Exception(
                                'Parent territory [' . $territoryCode . '] is not found for the given location code: '
                                . $locationCode
                                . '. Please, check data configuration!'
                            );
                        }
                        $relatedTerritory->addLocation($location);
                        $location->setTerritory($relatedTerritory);
                        $location->setNpcs([]);
                        $location->setStructures([]);
                        $world->addLocation($location);
                    }
                    $this->app->getLogger()->simpleLog('Locations initialized!');
                    $deferred->resolve();
                }
            )
            ->otherwise(function (\Throwable $error) {
                App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
            })
            ->done();

        return $deferred->promise();
    }

    /**
     * @param World $world
     * @return Promise
     * @throws \Exception
     */
    protected function initItems(World $world): Promise
    {
        $deferred = new \React\Promise\Deferred();
        $itemCodes = $this->app->getGameDataProvider()->getKeys(DataType::Items);
        $promises = [];

        try {
            foreach ($itemCodes as $itemCode) {
                $promises[$itemCode] = ItemFactory::createItemWithAllRelations($itemCode);
            }

            \React\Promise\all($promises)
                ->then(
                    function (array $itemsData) use ($world, $deferred) {
                        foreach ($itemsData as $itemCode => $item) {
                            $world->addItem($item);
                        }
                        $this->app->getLogger()->simpleLog('Items initialized!');
                        $deferred->resolve();
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                })
                ->done();
        } catch (\Throwable $error) {
            App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
        }

        return $deferred->promise();
    }

    /**
     * @param World $world
     * @return Promise
     * @throws \Exception
     */
    protected function initElixirs(World $world): Promise
    {
        $deferred = new \React\Promise\Deferred();
        $elixirCodes = $this->app->getGameDataProvider()->getKeys(DataType::Elixirs);
        $promises = [];

        try {
            foreach ($elixirCodes as $elixirCode) {
                $promises[$elixirCode] = ElixirFactory::createElixirWithAllRelations($elixirCode);
            }

            \React\Promise\all($promises)
                ->then(
                    function (array $elixirsData) use ($world, $deferred) {
                        foreach ($elixirsData as $elixirCode => $elixir) {
                            $world->addElixir($elixir);
                        }
                        $this->app->getLogger()->simpleLog('Elixirs initialized!');
                        $deferred->resolve();
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                })
                ->done();
        } catch (\Throwable $error) {
            App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
        }

        return $deferred->promise();
    }

    /**
     * @param   \Server\Game\Models\World  $world
     *
     * @return \React\Promise\Promise
     * @throws \Exception
     */
    protected function initNpcs(World $world)
    {
        $deferred = new \React\Promise\Deferred();

        $npcsCodes = $this->app->getGameDataProvider()->getKeys(DataType::NPCs);
        $promises = [];

        foreach ($npcsCodes as $npcCode) {
            $promises[$npcCode] = UnitFactory::createNpcUnitWithAllRelations($npcCode);
        }

        \React\Promise\all($promises)
            ->then(
                function (array $npcsData) use ($world, $deferred) {
                    try {
                        foreach ($npcsData as $npcCode => $npc) {
                            $locationCode = $npc->getLocationCode();
                            /** @var Location $relatedLocation */
                            $relatedLocation = $world->getLocations()[$locationCode] ?? null;

                            if (empty($relatedLocation)) {
                                throw new \Exception(
                                    'Parent location [' . $locationCode . '] is not found for the given npc code: '
                                    . $npcCode
                                    . '. Please, check data configuration!'
                                );
                            }
                            $relatedLocation->addNpc($npc);
                            $world->addNpc($npc);
                        }

                        $this->app->getLogger()->simpleLog('NPCs initialized!');
                        $deferred->resolve();
                    } catch (\Throwable $error) {
                        $this->app->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                    }
                }
            )
            ->otherwise(function (\Throwable $error) {
                App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
            })
            ->done();

        return $deferred->promise();
    }

    /**
     * @param World $world
     * @return \React\Promise\Promise
     * @throws \Exception
     */
    protected function initStructures(World $world): Promise
    {
        $deferred = new \React\Promise\Deferred();
        $promises = [];

        try {
            $structuresCodes = $this->app->getGameDataProvider()->getKeys(DataType::Structures);

            foreach ($structuresCodes as $code) {
                $promises[$code] = StructureFactory::createStructureWithAllRelations($code);
            }

            \React\Promise\all($promises)
                ->then(
                    function (array $structures) use ($world, $deferred) {
                        try {
                            /** @var Structure $structure */
                            foreach ($structures as $code => $structure) {
                                $locationCode = $structure->getLocationCode();
                                /** @var Location $relatedLocation */
                                $relatedLocation = $world->getLocations()[$locationCode] ?? null;

                                if (empty($relatedLocation)) {
                                    throw new \Exception(
                                        'Parent location [' . $locationCode . '] is not found for the given structure code: '
                                        . $code
                                        . '. Please, check data configuration!'
                                    );
                                }
                                $relatedLocation->addStructure($structure);
                                $world->addStructure($structure);
                            }

                            $this->app->getLogger()->simpleLog('Structures initialized!');
                            $deferred->resolve();
                        } catch (\Throwable $error) {
                            $this->app->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                        }
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                })
                ->done();
        } catch (\Throwable $error) {
            App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
        }

        return $deferred->promise();
    }

    /**
     * @param World $world
     * @return \React\Promise\Promise
     * @throws \Exception
     */
    protected function initBuffs(World $world): Promise
    {
        $deferred = new \React\Promise\Deferred();
        $promises = [];

        try {
            $buffCodes = $this->app->getGameDataProvider()->getKeys(DataType::Buffs);

            foreach ($buffCodes as $code) {
                $promises[$code] = BuffFactory::createBuffWithAllRelations($code);
            }

            \React\Promise\all($promises)
                ->then(
                    function (array $buffs) use ($world, $deferred) {
                        try {
                            /** @var Buff $buff */
                            foreach ($buffs as $code => $buff) {
                                $world->addBuff($buff);
                            }

                            $this->app->getLogger()->simpleLog('Buffs initialized!');
                            $deferred->resolve();
                        } catch (\Throwable $error) {
                            $this->app->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                        }
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                })
                ->done();
        } catch (\Throwable $error) {
            App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
        }

        return $deferred->promise();
    }

    /**
     * @param World $world
     * @return \React\Promise\Promise
     * @throws \Exception
     */
    protected function initSkills(World $world): Promise
    {
        $deferred = new \React\Promise\Deferred();
        $promises = [];

        try {
            $skillCodes = $this->app->getGameDataProvider()->getKeys(DataType::Skills);

            foreach ($skillCodes as $code) {
                $promises[$code] = SkillFactory::createSkillWithAllRelations($code);
            }

            \React\Promise\all($promises)
                ->then(
                    function (array $skills) use ($world, $deferred) {
                        try {
                            /** @var Skill $buff */
                            foreach ($skills as $code => $skill) {
                                $world->addSkill($skill);
                            }

                            $this->app->getLogger()->simpleLog('Skills initialized!');
                            $deferred->resolve();
                        } catch (\Throwable $error) {
                            $this->app->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                        }
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                })
                ->done();
        } catch (\Throwable $error) {
            App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
        }

        return $deferred->promise();
    }

    /**
     * @param World $world
     * @return \React\Promise\Promise
     * @throws \Exception
     */
    protected function initTalents(World $world): Promise
    {
        $deferred = new \React\Promise\Deferred();
        $promises = [];

        try {
            $talentsCodes = $this->app->getGameDataProvider()->getKeys(DataType::Talents);

            foreach ($talentsCodes as $code) {
                $promises[$code] = TalentFactory::createTalentWithAllRelations($code);
            }

            \React\Promise\all($promises)
                ->then(
                    function (array $talents) use ($world, $deferred) {
                        try {
                            /** @var Talent $structure */
                            foreach ($talents as $code => $talent) {
                                $world->addTalent($talent);
                            }

                            $this->app->getLogger()->simpleLog('Talents initialized!');
                            $deferred->resolve();
                        } catch (\Throwable $error) {
                            $this->app->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                        }
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                })
                ->done();
        } catch (\Throwable $error) {
            App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
        }

        return $deferred->promise();
    }

    /**
     * @param World $world
     * @return \React\Promise\Promise
     * @throws \Exception
     */
    protected function initChapter(World $world): Promise
    {
        $deferred = new \React\Promise\Deferred();

        try {
            $currentChapter = (int)CURRENT_CHAPTER;

            $chapterInitializerClass = 'ChapterInitializer' . $currentChapter;
            $chapterInitializerClassFullName = 'Server\\Game\\Initialization\\Chapters\\' . $chapterInitializerClass;

            if (!class_exists($chapterInitializerClassFullName)) {
                throw new \Exception('Initializer class for Chapter ' . $currentChapter . ' doesn\'t exists!');
            }

            $chapterInitializer = new $chapterInitializerClassFullName();
            $chapter = new Chapter();

            $chapterInitializer
                ->initChapter($chapter)
                ->then(
                    function () use ($world, $chapter, $deferred) {
                        try {
                            $world->setChapter($chapter);
                            $this->app->getLogger()->simpleLog('Chapter initialized!');
                            $deferred->resolve();
                        } catch (\Throwable $error) {
                            $this->app->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                        }
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
                })
                ->done();
        } catch (\Throwable $error) {
            App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
        }

        return $deferred->promise();
    }
}