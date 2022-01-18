<?php

class m190729_080650_add_field_total_set_number_in_limit_lottery_number_game extends  \yii\db\Migration
{
    const LIMIT_LOTTERY_NUMBER_GAME = '{{%limit_lottery_number_game}}';

    public function safeUp()
    {
        $this->addColumn(self::LIMIT_LOTTERY_NUMBER_GAME, 'totalSetNumber', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn(self::LIMIT_LOTTERY_NUMBER_GAME, 'totalSetNumber');
    }
}