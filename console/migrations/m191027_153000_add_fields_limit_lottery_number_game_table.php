<?php

class m191027_153000_add_fields_limit_lottery_number_game_table extends \yii\db\Migration
{
    const LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME = '{{%limit_lottery_number_game}}';

    public function safeUp()
    {
        $this->addColumn(self::LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME, 'isLimitByUser', $this->integer()->defaultValue(0));
        $this->addColumn(self::LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME, 'amountLimit', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn(self::LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME, 'isLimitByUser');
        $this->dropColumn(self::LIMIT_LOTTERY_NUMBER_GAME_TABLE_NAME, 'amountLimit');
    }
}