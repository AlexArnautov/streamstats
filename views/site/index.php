<?php

/** @var yii\web\View $this */

use app\assets\VueAsset;

$this->title = 'Dashboard';
VueAsset::register($this);
?>

<?php
if (Yii::$app->user->isGuest) { ?>
    <section class="jumbotron text-center">
        <div class="container">
            <h1>Stream Stats</h1>
            <p class="lead text-muted">
                This application is aimed at helping Twitch viewers get a quick look at how the channels they watch
                compare to the top 1000 live streams. </p>
            <p class="lead text-muted">
                The stats will show a comparison between the streams they are watching and the top 1000 current live
                streams. </p>
            <a href="/site/auth?authclient=twitch" class="btn btn-primary my-2"><i class="fa-solid fa-gauge-high"></i> Login via Twitch</a>
            </p>
        </div>
    </section>
    <?php
} else { ?>
    <div id="app">
    </div>
    <?php
} ?>
