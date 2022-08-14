<?php

declare(strict_types=1);

namespace app\commands;


use app\components\StreamsAPIServiceInterface;
use app\components\StreamsFactory;
use app\components\TagsFactory;
use app\models\Stream;
use app\models\Tag;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\console\Exception;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\Json;


class ParseStreamsController extends Controller
{
    /**
     * @param $id
     * @param $module
     * @param StreamsAPIServiceInterface $streamsService
     * @param StreamsFactory $streamsFactory
     * @param TagsFactory $tagsFactory
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        protected readonly StreamsAPIServiceInterface $streamsService,
        protected readonly StreamsFactory $streamsFactory,
        protected readonly TagsFactory $tagsFactory,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * @return int
     * @throws GuzzleException
     * @throws \yii\base\Exception
     */
    public function actionIndex(): int
    {
        $parseHash = Yii::$app->security->generateRandomString(10);
        echo 'Start parsing streams...' . PHP_EOL;
        try {
            foreach ($this->streamsService->getStreams() as $streamBatch) {
                echo PHP_EOL . 'Starting new batch...' . PHP_EOL;
                $this->saveBatch($streamBatch, $parseHash);
            }
            echo 'Deleting old streams...' . PHP_EOL;
            Stream::deleteAll(['!=', 'parse_hash', $parseHash]);
        } catch (\Exception $e) {
            echo $this->ansiFormat($e->getMessage(), Console::FG_RED) . PHP_EOL;
            echo $e->getFile() . ':' . $e->getLine() . PHP_EOL;
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    private function saveBatch(array $streamBatch, string $parseHash): void
    {
        shuffle($streamBatch);
        foreach ($streamBatch as $streamRaw) {
            $streamModel = Stream::findOne(['twitch_id' => $streamRaw['id']]);
            if ($streamModel === null) {
                $streamModel = $this->streamsFactory->createStream($streamRaw, $parseHash);
            }
            $streamModel->parse_hash = $parseHash;
            if ($streamModel->save()) {
                echo 'Stream saved: ' . $streamModel->title . PHP_EOL;
                $tags = $this->streamsService->getTagsByBroadcasterId((string)$streamModel->twitch_user_id);
                $this->saveStreamTags($streamModel, $tags);
            } else {
                echo Json::encode($streamRaw) . PHP_EOL;
                throw new Exception('Unable to save stream: ' . Json::encode($streamModel->getFirstErrors()));
            }
        }
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    private function saveStreamTags(Stream $streamModel, array $tags)
    {
        foreach ($tags['data'] as $tag) {
            $tagModel = Tag::findOne(['twitch_id' => $tag['tag_id']]);
            if ($tagModel === null) {
                $tagModel = $this->tagsFactory->createTag($tag);
                if ($tagModel->save()) {
                    echo 'New tag saved: ' . $tagModel->name . PHP_EOL;
                } else {
                    throw new Exception('Unable to save tag: ' . Json::encode($tagModel->getFirstErrors()));
                }
            }

            $existTag = $streamModel->getTags()->where(['id' => $tagModel->id])->one();
            if ($existTag === null) {
                $streamModel->link('tags', $tagModel);
            }
        }
    }
}
