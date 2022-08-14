<?php

declare(strict_types=1);

namespace app\controllers\api;


use app\components\helpers\MathHelpers;
use app\components\repositories\StreamRepository;
use Exception;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;


class StreamController extends Controller
{
    const TOP_LIMIT = 100;

    /**
     * @param $id
     * @param $module
     * @param StreamRepository $streamRepository
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly StreamRepository $streamRepository,
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
     * @return array
     * @throws Exception
     */
    public function actionMedianViewers(): array
    {
        $rows = $this->streamRepository->getViewersColumn();
        return ['median_viewers' => MathHelpers::calculateMedian($rows)];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionTop(): array
    {
        return $this->streamRepository->getTopStreams(self::TOP_LIMIT);
    }

    /**
     * @return array
     */
    public function actionStreamsByHour(): array
    {
        return $this->streamRepository->getStreamsByHour();
    }
}
