<?php

namespace app\controllers\api;

use Yii;
use yii\db\DataReader;
use yii\db\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;


class GameController extends Controller
{
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
     * @return DataReader|array
     */
    public function actionNumberOfStreams(): DataReader|array
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
     * @throws Exception
     */
    public function actionTopGames(): DataReader|array
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

}
