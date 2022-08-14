<?php

declare(strict_types=1);

namespace app\components\repositories;

use app\models\Stream;
use yii\db\ActiveRecord;
use yii\db\Query;

class StreamRepository
{
    /**
     * @return array
     */
    public function getNumberOfStreamsByGames(): array
    {
        return (new Query())
            ->select(
                [
                    'streams' => 'count(id)',
                    'game' => 'game_name',
                ],
            )
            ->from('stream')
            ->where(['!=', 'game_name', ''])
            ->groupBy('game_name')
            ->orderBy('game_name')
            ->all();
    }

    /**
     * @return array
     */
    public function getTopGamesByViewers(): array
    {
        return (new Query())
            ->select(
                [
                    'viewers' => 'SUM(viewer_count)',
                    'game' => 'game_name',
                ],
            )
            ->from('stream')
            ->where(['!=', 'game_name', ''])
            ->groupBy('game_name')
            ->orderBy(['viewers' => SORT_DESC])
            ->all();
    }

    /**
     * @return array
     */
    public function getViewersColumn(): array
    {
        return (new Query())
            ->select(['viewer_count'])
            ->from('stream')
            ->column();
    }

    /**
     * @param int $limit
     * @return array|ActiveRecord[]
     */
    public function getTopStreams(int $limit = 100): array
    {
        return Stream::find()
            ->orderBy(['viewer_count' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * @return array
     */
    public function getStreamsByHour(): array
    {
        return (new Query())
            ->select(
                [
                    'day' => 'DAY(started_at)',
                    'hour' => 'HOUR(started_at)',
                    'streams' => 'count(id)',
                ],
            )
            ->from('stream')
            ->groupBy(['DAY(started_at)', 'HOUR(started_at)'])
            ->all();
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getTopStreamsByBroadcasterIds(array $ids): array
    {
        return Stream::find()
            ->select([
                'title',
                'game_name',
                'viewer_count',
                'started_at'
            ])->where(['in', 'twitch_user_id', $ids])->all();
    }

}
