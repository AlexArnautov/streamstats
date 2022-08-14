<?php

namespace app\assets;

use yii\web\AssetBundle;


/**
 * Main frontend application asset bundle.
 */
class VueAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        '/js/streamstats/app.js',
    ];
}