<?php

class m190509_205150_create_queue_table_name extends \yii\db\Migration
{
    const QUEUE_TABLE_NAME = '{{%queue}}';
    const GAMES_TABLE_NAME = '{{%games}}';
    const USER_TABLE_NAME = '{{%user}}';

    public function safeUp()
    {
        $this->createTable(self::QUEUE_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'gameId' => $this->integer()->notNull(),
            'userId' => $this->integer()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'requestId' => $this->integer(),
        ]);
        $this->addForeignKey(
            '{{%gameId_queue}}',
            self::QUEUE_TABLE_NAME,
            'gameId',
            self::GAMES_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%userId_queue}}',
            self::QUEUE_TABLE_NAME,
            'userId',
            self::USER_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::QUEUE_TABLE_NAME);
    }
}