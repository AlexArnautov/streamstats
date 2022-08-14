<?php

declare(strict_types=1);

namespace app\components\services;

use Generator;

interface StreamsAPIServiceInterface
{
    public function getStreams(): Generator;

    public function getUserFollows(): array;

    public function getTagsByBroadcasterId(string $broadcasterId): array;

    public function getLoggedUserTagIds(): array;

    public function getActiveUserStreams(): array;
}