<?php

use yii\db\Migration;

/**
 * Class m220812_155024_add_stream_tag_table
 */
class m220812_155024_add_stream_tag_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('stream_tag', [
            'stream_id' => $this->integer(),
            'tag_id' => $this->integer(),
            'PRIMARY KEY(stream_id, tag_id)',
        ]);

        // creates index for column `stream_id`
        $this->createIndex(
            'idx_stream_tag_stream_id',
            'stream_tag',
            'stream_id'
        );

        // add foreign key for table `stream`
        $this->addForeignKey(
            'fk_stream_tag_stream_id',
            'stream_tag',
            'stream_id',
            'stream',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx_stream_tag_tag_id',
            'stream_tag',
            'tag_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk_stream_tag_tag_id',
            'stream_tag',
            'tag_id',
            'tag',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops foreign key for table `stream`
        $this->dropForeignKey(
            'fk_stream_tag_stream_id',
            'stream_tag'
        );

        // drops index for column `stream_id`
        $this->dropIndex(
            'idx_stream_tag_stream_id',
            'stream_tag'
        );

        // drops foreign key for table `tag`
        $this->dropForeignKey(
            'fk_stream_tag_tag_id',
            'stream_tag'
        );

        // drops index for column `tag_id`
        $this->dropIndex(
            'idx_stream_tag_tag_id',
            'stream_tag'
        );

        $this->dropTable('stream_tag');
    }
}
