<?php

declare(strict_types=1);

namespace app\components;

use Exception;
use Generator;
use GuzzleHttp\Exception\GuzzleException;
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
     * @throws GuzzleException
     */
    public function getStreams(): Generator
    {
        $afterHash = null;
        for ($i = 1; $i <= self::STREAMS_PAGES; $i++) {
            $streams = $this->twitchApi
                ->getStreamsApi()
                ->getStreams($this->twitchAccessToken, [], [], [], [], self::STREAMS_PER_PAGE, null, $afterHash)
                ->getBody()
                ->getContents();
            $streamsData = Json::decode($streams);
            $afterHash = $streamsData['pagination']['cursor'];
            yield $streamsData['data'];
        }
    }


    /**
     * @throws GuzzleException
     * @throws \yii\base\Exception|\Throwable
     */
    public function getLoggedUserStreams(): array
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
     * @throws GuzzleException
     */
    public function getTagByBroadcasterId(string $streamId): string
    {
        return Json::decode(
            $this->twitchApi
                ->getTagsApi()
                ->getStreamTags($this->twitchAccessToken, $streamId)
                ->getBody()
                ->getContents()
        );
    }


}