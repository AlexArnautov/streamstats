<?php

Yii::$container->set(
    'app\components\services\StreamsAPIServiceInterface',
    'app\components\services\StreamsAPIService'
);

Yii::$container->set(
    'app\components\factories\StreamFactory'
);

Yii::$container->set(
    'app\components\factories\TagFactory'
);

Yii::$container->set(
    'app\components\repositories\StreamRepository'
);