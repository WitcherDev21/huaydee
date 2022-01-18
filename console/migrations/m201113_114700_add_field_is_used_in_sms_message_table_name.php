<?php

class m201113_114700_add_field_is_used_in_sms_message_table_name extends \yii\db\Migration
{
    const SMS_MESSAGE_TABLE_NAME = '{{%sms_message}}';

    public function safeUp()
    {
        $this->addColumn(self::SMS_MESSAGE_TABLE_NAME, 'is_used', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn(self::SMS_MESSAGE_TABLE_NAME, 'is_used');
    }
}