<?php

class m200807_225800_create_minimum_auto_limit_lottery_number_game_table_name extends \yii\db\Migration
{
    const LIMIT_AUTO_LOTTERY_NUMBER_GAME_TABLE_NAME = '{{%limit_auto_lottery_number_game}}';

    public function safeUp()
    {
        $this->addColumn(self::LIMIT_AUTO_LOTTERY_NUMBER_GAME_TABLE_NAME, 'minimum', $this->integer()->notNull());
    }

    public function safeDown()
    {
    }
}