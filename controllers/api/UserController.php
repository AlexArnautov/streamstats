<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\components\StreamsAPIServiceInterface;
use app\models\Tag;
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
    public function actionTopFollowing(): array
    {
        return $this->streamsService->getLoggedUserStreams();
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     * @throws Throwable
     */
    public function actionSharedTags(): array
    {
        $tagIds = $this->streamsService->getLoggedUserTagIds();
        return Tag::find()
            ->select('name')
            ->where(['in', 'twitch_id', $tagIds])->column();
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     * @throws Throwable
     */
    public function actionStreamToTop(): array
    {
       $this->streamsService->getNeededViewersUserFollowedForTop();
    }
}
