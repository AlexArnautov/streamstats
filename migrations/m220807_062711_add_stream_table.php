<?php

use yii\db\Migration;

/**
 * Class m220807_062711_add_stream_table
 */
class m220807_062711_add_stream_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('stream', [
            'id' => $this->primaryKey(),
            'twitch_id' => $this->bigInteger()->notNull()->unsigned(),
            'twitch_user_id' => $this->bigInteger()->notNull()->unsigned(),
            'title' => $this->string()->notNull(),
            'game_name' => $this->string(),
            'channel_name' => $this->string()->notNull(),
            'started_at' => $this->dateTime()->notNull(),
            'viewer_count' => $this->integer()->notNull()->unsigned(),
            'parse_hash' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('stream');
    }
}
