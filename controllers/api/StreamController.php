<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\components\MathHelpers;
use app\models\Stream;
use Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;


class StreamController extends Controller
{
    const TOP_LIMIT = 100;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionMedianViewers(): array
    {
        $rows = (new Query())
            ->select(['viewer_count'])
            ->from('stream')
            ->column();

        return ['median_viewers' => MathHelpers::calculateMedian($rows)];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionTop(): array
    {
        return Stream::find()
            ->orderBy(['viewer_count' => SORT_DESC])
            ->limit(self::TOP_LIMIT)
            ->all();
    }

    /**
     * @return array
     */
    public function actionStreamsByHour(): array
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
}
