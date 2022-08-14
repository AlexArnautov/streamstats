<?php

use yii\db\Migration;

/**
 * Class m220807_203259_add_tag_table
 */
class m220807_203259_add_tag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220807_203259_add_tag_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220807_203259_add_tag_table cannot be reverted.\n";

        return false;
    }
    */
}
