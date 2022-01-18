<?php
class m190721_193700_add_field_channel_in_post_credit_transection_table_name extends \yii\db\Migration
{
    const POST_CREDIT_TRANSECTION_TABLE_NAME = '{{%post_credit_transection}}';

    public function safeUp()
    {
        $this->addColumn(self::POST_CREDIT_TRANSECTION_TABLE_NAME, 'channel', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::POST_CREDIT_TRANSECTION_TABLE_NAME, 'channel');
    }
}
