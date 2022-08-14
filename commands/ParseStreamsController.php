<?php

namespace app\commands;

use app\components\services\StreamsServiceInterface;
use yii\console\Controller;
use yii\console\ExitCode;


class ParseStreamsController extends Controller
{
    /**
     * @param $id
     * @param $module
     * @param StreamsServiceInterface $streamsService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        protected StreamsServiceInterface $streamsService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex($message = 'hello world')
    {
        $streams = $this->streamsService->getStreams(1000);

        return ExitCode::OK;
    }
}
