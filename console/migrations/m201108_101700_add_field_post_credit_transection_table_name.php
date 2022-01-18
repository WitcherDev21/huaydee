<?php

class m201108_101700_add_field_post_credit_transection_table_name extends \yii\db\Migration
{
    const POST_CREDIT_TRANSECTION_TABLE_NAME = '{{%post_credit_transection}}';

    public function safeUp()
    {
        $this->addColumn(self::POST_CREDIT_TRANSECTION_TABLE_NAME, 'is_auto', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn(self::POST_CREDIT_TRANSECTION_TABLE_NAME, 'is_auto');
    }
}