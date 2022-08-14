<?php

declare(strict_types=1);

namespace app\components;

use Generator;

interface StreamsAPIServiceInterface
{
    public function getStreams(): Generator;
}