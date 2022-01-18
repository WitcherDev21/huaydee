<?php

class m190804_222930_create_lottery_show_number_result_win extends \yii\db\Migration
{
    const LOTTERY_SHOW_NUMBER_RESULT_WIN_NAME = '{{%lottery_show_number_result_win}}';
    const THAI_SHARED_GAME_TABLE_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->createTable(self::LOTTERY_SHOW_NUMBER_RESULT_WIN_NAME, [
            'id' => $this->primaryKey(),
            'thaiSharedGameId' => $this->integer()->notNull(),
            'number' => $this->string()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%lottery_show_number_result_win_thaiSharedGameId}}',
            self::LOTTERY_SHOW_NUMBER_RESULT_WIN_NAME,
            'thaiSharedGameId',
            self::THAI_SHARED_GAME_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::LOTTERY_SHOW_NUMBER_RESULT_WIN_NAME);
    }
}