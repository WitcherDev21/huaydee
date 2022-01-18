<?php

class m201105_003300_create_sms_message_table_name extends \yii\db\Migration
{
    const SMS_MESSAGE_TABLE_NAME = '{{%sms_message}}';

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable(self::SMS_MESSAGE_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'message_id' => $this->integer()->notNull(),
            'message' => $this->string()->notNull(),
            'amount' => $this->decimal('10, 2')->notNull(),
            'date' => $this->dateTime()->notNull(),
            'bank' => $this->string()->notNull(),
            'action' => $this->string()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(self::SMS_MESSAGE_TABLE_NAME);
    }
}