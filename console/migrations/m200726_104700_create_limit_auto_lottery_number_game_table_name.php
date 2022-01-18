<?php

class m200726_104700_create_limit_auto_lottery_number_game_table_name extends \yii\db\Migration
{
    const LIMIT_AUTO_LOTTERY_NUMBER_GAME_TABLE_NAME = '{{%limit_auto_lottery_number_game}}';
    const PLAY_TYPE_TABLE_NAME = '{{%play_type}}';
    const THAI_SHARED_GAME_TABLE_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->createTable(self::LIMIT_AUTO_LOTTERY_NUMBER_GAME_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'thaiSharedGameId' => $this->integer()->notNull(),
            'maximumRate' => $this->integer()->notNull(),
            'playTypeId' => $this->integer()->notNull(),
            'jackPotPerUnit' => $this->float()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedAt' => $this->dateTime(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(),
        ]);
        $this->addForeignKey(
            '{{%limit_lottery_auto_number_game_thaiSharedGameId}}',
            self::LIMIT_AUTO_LOTTERY_NUMBER_GAME_TABLE_NAME,
            'thaiSharedGameId',
            self::THAI_SHARED_GAME_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%limit_lottery_auto_number_game_playTypeId}}',
            self::LIMIT_AUTO_LOTTERY_NUMBER_GAME_TABLE_NAME,
            'playTypeId',
            self::PLAY_TYPE_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::LIMIT_AUTO_LOTTERY_NUMBER_GAME_TABLE_NAME);
    }
}