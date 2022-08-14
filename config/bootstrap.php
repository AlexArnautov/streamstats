<?php

Yii::$container->set(
    'app\components\StreamsAPIServiceInterface',
    'app\components\StreamsAPIService'
);

Yii::$container->set(
    'app\components\StreamsFactory'
);

Yii::$container->set(
    'app\components\TagsFactory'
);