<?php

class m190724_092620_create_limit_lottery_number_game_table_name extends \yii\db\Migration
{
    const LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME = '{{%limit_lottery_number_game}}';
    const PLAY_TYPE_TABLE_NAME = '{{%play_type}}';
    const THAI_SHARED_GAME_TABLE_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->createTable(self::LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'thaiSharedGameId' => $this->integer()->notNull(),
            'playTypeId' => $this->integer()->notNull(),
            'number' => $this->string()->notNull(),
            'jackPotPerUnit' => $this->float()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedAt' => $this->dateTime(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(),
        ]);
        $this->addForeignKey(
            '{{%limit_lottery_number_game_thaiSharedGameId}}',
            self::LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME,
            'thaiSharedGameId',
            self::THAI_SHARED_GAME_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%limit_lottery_number_game_playTypeId}}',
            self::LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME,
            'playTypeId',
            self::PLAY_TYPE_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME);
    }
}