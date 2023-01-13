<?php

use yii\db\Migration;

/**
 * Class m230109_005043_add_column_in_tbl_mail_message
 */
class m230109_005043_add_column_in_tbl_mail_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('tbl_mail_message', 'post_id', $this->integer()->notNull());
        $this->addColumn('tbl_mail_message', 'total_count', $this->integer()->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230109_005043_add_column_in_tbl_mail_message cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230109_005043_add_column_in_tbl_mail_message cannot be reverted.\n";

        return false;
    }
    */
}
