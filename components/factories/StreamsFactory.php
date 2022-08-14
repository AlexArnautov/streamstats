<?php

declare(strict_types=1);

namespace app\components;

use app\models\Stream;

class StreamsFactory
{
    /**
     * @param array $rawData
     * @param string $parseHash
     * @return Stream
     */
    public function createStream(array $rawData, string $parseHash): Stream
    {
        if (empty($rawData['title'])) {
            $rawData['title'] = 'No title';
        }
        $streamModel = new Stream();
        $streamModel->twitch_id = $rawData['id'];
        $streamModel->twitch_user_id = $rawData['user_id'];
        $streamModel->game_name = $rawData['game_name'];
        $streamModel->channel_name = $rawData['user_name'];
        $streamModel->title = $rawData['title'];
        $streamModel->viewer_count = $rawData['viewer_count'];
        $streamModel->started_at = date('Y-m-d h:i:s', strtotime($rawData['started_at']));
        $streamModel->parse_hash = $parseHash;
        return $streamModel;
    }
}