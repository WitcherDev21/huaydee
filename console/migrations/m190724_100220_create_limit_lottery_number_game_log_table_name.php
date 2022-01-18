<?php

class m190724_100220_create_limit_lottery_number_game_log_table_name extends \yii\db\Migration
{
    const LOG_LIMIT_LOTTERY_NUMBER_GAME = '{{%log_limit_lottery_number_game}}';
    const PLAY_TYPE_TABLE_NAME = '{{%play_type}}';
    const THAI_SHARED_GAME_TABLE_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->createTable(self::LOG_LIMIT_LOTTERY_NUMBER_GAME, [
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
            '{{%log_limit_lottery_number_game_thaiSharedGameId}}',
            self::LOG_LIMIT_LOTTERY_NUMBER_GAME,
            'thaiSharedGameId',
            self::THAI_SHARED_GAME_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%log_limit_lottery_number_game_playTypeId}}',
            self::LOG_LIMIT_LOTTERY_NUMBER_GAME,
            'playTypeId',
            self::PLAY_TYPE_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::LOG_LIMIT_LOTTERY_NUMBER_GAME);
    }
}