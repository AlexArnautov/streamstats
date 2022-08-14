<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\components\StreamsAPIServiceInterface;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;


class UserController extends Controller
{
    /**
     * @param $id
     * @param $module
     * @param StreamsAPIServiceInterface $streamsService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        protected readonly StreamsAPIServiceInterface $streamsService,
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
     * @throws Exception
     * @throws GuzzleException
     * @throws Throwable
     */
    public function actionTopFollowing()
    {
        return $this->streamsService->getLoggedUserStreams();
    }
}
