<?php

class m190725_162330_create_pusher_table_name extends \yii\db\Migration
{
    const PUSHER_TABLE_NAME = '{{%pusher}}';

    public function safeUp()
    {
        $this->createTable(self::PUSHER_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'message' => $this->string()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedAt' => $this->dateTime(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable(self::PUSHER_TABLE_NAME);
    }
}