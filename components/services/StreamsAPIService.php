<?php

declare(strict_types=1);

namespace app\components\services;

use Exception;
use Generator;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;
use TwitchApi\HelixGuzzleClient;
use TwitchApi\TwitchApi;
use Yii;
use yii\helpers\Json;

class StreamsAPIService implements StreamsAPIServiceInterface
{
    public const STREAMS_PER_PAGE = 100;
    public const STREAMS_PAGES = 10;
    private string $twitchClientId;
    private string $twitchClientSecret;
    private ?string $twitchAccessToken;
    private TwitchApi $twitchApi;

    /**
     * @throws GuzzleException
     */
    public function __construct()
    {
        $this->twitchClientId = Yii::$app->params['twitch.clientId'];
        $this->twitchClientSecret = Yii::$app->params['twitch.clientSecret'];

        $this->twitchApi = new TwitchApi(
            new HelixGuzzleClient($this->twitchClientId),
            $this->twitchClientId,
            $this->twitchClientSecret
        );

        try {
            $token = $this->twitchApi->getOauthApi()->getAppAccessToken();
            $data = Json::decode($token->getBody()->getContents());
            $this->twitchAccessToken = $data['access_token'] ?? null;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * @param array $userIds
     * @return Generator
     * @throws GuzzleException
     */
    public function getStreams(array $userIds = []): Generator
    {
        $afterHash = null;
        for ($i = 1; $i <= self::STREAMS_PAGES; $i++) {
            $streams = $this->twitchApi
                ->getStreamsApi()
                ->getStreams($this->twitchAccessToken, $userIds, [], [], [], self::STREAMS_PER_PAGE, null, $afterHash)
                ->getBody()
                ->getContents();
            $streamsData = Json::decode($streams);
            if (isset($streamsData['pagination']['cursor'])) {
                $afterHash = $streamsData['pagination']['cursor'];
            } else {
                yield $streamsData['data'];
                break;
            }
            yield $streamsData['data'];
        }
    }


    /**
     * @throws GuzzleException
     * @throws \yii\base\Exception|Throwable
     */
    public function getUserFollows(): array
    {
        $twitchId = Yii::$app->user->getIdentity()->twitch_id;

        if (Yii::$app->user->isGuest) {
            throw new \yii\base\Exception('User is not logged in');
        }

        $allUserStreams = [];
        $afterHash = null;
        do {
            $streams = $this->twitchApi
                ->getUsersApi()
                ->getUsersFollows($this->twitchAccessToken, (string)$twitchId, null, self::STREAMS_PER_PAGE, $afterHash)
                ->getBody()
                ->getContents();

            $streamsData = Json::decode($streams);

            if (isset($streamsData['pagination']['cursor'])) {
                $afterHash = $streamsData['pagination']['cursor'];
            }

            $allUserStreams = array_merge($allUserStreams, $streamsData['data']);
        } while (isset($streamsData['pagination']['cursor']));

        return $allUserStreams;
    }

    /**
     * @param string $broadcasterId
     * @return array
     * @throws GuzzleException
     */
    public function getTagsByBroadcasterId(string $broadcasterId): array
    {
        return Json::decode(
            $this->twitchApi
                ->getTagsApi()
                ->getStreamTags($this->twitchAccessToken, $broadcasterId)
                ->getBody()
                ->getContents()
        );
    }

    /**
     * @throws \yii\base\Exception
     * @throws GuzzleException
     * @throws Throwable
     */
    public function getLoggedUserTagIds(): array
    {
        $tagIds = [];

        $userStreams = $this->getUserFollows();

        foreach ($userStreams as $userStream) {
            $tags = array_merge($this->getTagsByBroadcasterId($userStream['to_id']));
            $tagIds = array_merge($tagIds, array_column($tags['data'], 'tag_id'));
        }

        return array_unique($tagIds);
    }

    /**
     * @throws Throwable
     * @throws \yii\base\Exception
     * @throws GuzzleException
     */
    public function getActiveUserStreams(): array
    {
        $streams = [];
        $broadcasterIds = array_column($this->getUserFollows(), 'to_id');
        foreach ($this->getStreams($broadcasterIds) as $streamBatch) {
            foreach ($streamBatch as $stream) {
                $streams[] = $stream;
            }
        }
        return $streams;
    }


}