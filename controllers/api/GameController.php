<?php

declare(strict_types=1);

namespace app\controllers\api;


use app\components\repositories\StreamRepository;
use yii\db\DataReader;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;


class GameController extends Controller
{

    public function __construct(
        $id,
        $module,
        protected readonly StreamRepository $streamRepository,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

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
        return $this->streamRepository->getNumberOfStreamsByGames();
    }


    /**
     * @return DataReader|array
     */
    public function actionTopGames(): DataReader|array
    {
        return $this->streamRepository->getTopGamesByViewers();
    }

}
