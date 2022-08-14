<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "stream".
 *
 * @property int $id
 * @property int $twitch_id
 * @property string $title
 * @property string $game_name
 * @property string $channel_name
 * @property string $started_at
 * @property string $parse_hash
 * @property int $viewer_count
 * @property string $created_at
 * @property string $updated_at
 */
class Stream extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()')
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'stream';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['twitch_id', 'title', 'channel_name', 'started_at', 'viewer_count', 'parse_hash'], 'required'],
            [['twitch_id', 'viewer_count'], 'integer'],
            [['started_at', 'created_at', 'updated_at'], 'safe'],
            [['title', 'game_name', 'channel_name', 'parse_hash'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'twitch_id' => 'Twitch ID',
            'title' => 'Title',
            'game_name' => 'Game Name',
            'channel_name' => 'Channel Name',
            'started_at' => 'Started At',
            'viewer_count' => 'Viewer Count',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];
    }
}
