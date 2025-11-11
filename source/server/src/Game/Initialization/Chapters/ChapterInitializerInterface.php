<?php

declare(strict_types=1);

namespace Server\Game\Initialization\Chapters;

use React\Promise\PromiseInterface;
use Server\Game\Models\Chapter;

/**
 *
 */
interface ChapterInitializerInterface
{
    public function initChapter(Chapter $chapter): PromiseInterface;
}