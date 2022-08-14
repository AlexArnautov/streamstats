<?php

declare(strict_types=1);

namespace app\controllers\api;


use app\components\repositories\StreamRepository;
use app\components\services\StreamsAPIServiceInterface;
use app\models\Tag;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;


class UserController extends Controller
{
    /**
     * @param $id
     * @param $module
     * @param StreamsAPIServiceInterface $streamsApiService
     * @param StreamRepository $streamRepository
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly StreamsAPIServiceInterface $streamsApiService,
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
     */
    public function actionTopFollowing(): array
    {
        $broadcasterIds = array_column($this->streamsApiService->getUserFollows(), 'to_id');
        return $this->streamRepository->getTopStreamsByBroadcasterIds($broadcasterIds);
    }

    /**
     * @return array
     */
    public function actionSharedTags(): array
    {
        $tagIds = $this->streamsApiService->getLoggedUserTagIds();
        return Tag::find()
            ->select('name')
            ->where(['in', 'twitch_id', $tagIds])->column();
    }

    /**
     * @return array
     */
    public function actionLowerUserStream(): array
    {
        $result = [
            'title' => '',
            'need_viewers' => ''
        ];
        $userStreams = $this->streamsApiService->getActiveUserStreams();
        $counts = array_column($userStreams, 'viewer_count');
        $index = array_search(min($counts), $counts, true);
        $lowerStream = $userStreams[$index];
        if (empty($lowerStream)) {
            $result['title'] = 'No active streams';
            return $result;
        }

        $lowerViewersInTop = (new Query())
            ->select(
                [
                    'min(viewer_count)',
                ],
            )
            ->from('stream')
            ->scalar();

        if ($lowerStream['viewer_count'] > $lowerViewersInTop) {
            $needViewersForTop = 'Already in top';
        } else {
            $needViewersForTop = $lowerViewersInTop - $lowerStream['viewer_count'];
        }

        return ['title' => $lowerStream['title'], 'need_viewers_for_top' => $needViewersForTop];
    }

}
