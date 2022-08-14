<?php

namespace app\components\services;

use Exception;
use Generator;
use GuzzleHttp\Exception\GuzzleException;
use TwitchApi\HelixGuzzleClient;
use TwitchApi\TwitchApi;
use Yii;
use yii\helpers\Json;

class StreamsService implements StreamsServiceInterface
{
    public const STREAMS_PER_PAGE = 100;
    public const STREAMS_PAGES = 10;
    private string $twitchClientId;
    private string $twitchClientSecret;
    private ?string $twitchAccessToken;
    private TwitchApi $twitchApi;

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
            $token = $this->twitchApi->getOauthApi()->getAppAccessToken('');
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
            yield $streams;
        }
    }
}