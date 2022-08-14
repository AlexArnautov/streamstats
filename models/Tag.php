<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $twitch_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Stream[] $streams
 */
class Tag extends ActiveRecord
{
    public function behaviors(): array
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
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['twitch_id', 'name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'twitch_id'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];
    }

    /**
     * Gets query for [[Streams]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getStreams(): ActiveQuery
    {
        return $this->hasMany(Stream::class, ['id' => 'stream_id'])
            ->viaTable('stream_tag', ['tag_id' => 'id']);
    }
}
