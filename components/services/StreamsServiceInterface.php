<?php

namespace app\components\services;

use Generator;

interface StreamsServiceInterface
{
    public function getStreams(): Generator;
}