<?php

class m191102_195700_add_field_post_credit_transection_table extends \yii\db\Migration
{
    const POST_CREDIT_TRANSECTION_TABLE_NAME = '{{%post_credit_transection}}';

    public function safeUp()
    {
        $this->addColumn(self::POST_CREDIT_TRANSECTION_TABLE_NAME, 'user_has_bank_id_user', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn(self::POST_CREDIT_TRANSECTION_TABLE_NAME, 'user_has_bank_id_user');
    }
}