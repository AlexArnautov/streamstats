<?php

declare(strict_types=1);

namespace app\components\factories;

use app\models\Tag;

class TagFactory
{
    /**
     * @param array $rawData
     * @return Tag
     */
    public function createTag(array $rawData): Tag
    {
        $tagModel = new Tag();
        $tagModel->twitch_id = $rawData['tag_id'];
        $tagModel->name = $rawData['localization_names']['en-us'];
        return $tagModel;
    }
}