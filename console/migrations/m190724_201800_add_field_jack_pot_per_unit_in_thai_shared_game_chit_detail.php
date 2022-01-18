<?php

class m190724_201800_add_field_jack_pot_per_unit_in_thai_shared_game_chit_detail extends  \yii\db\Migration
{
    const THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME = '{{%thai_shared_game_chit_detail}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME, 'jackPotPerUnit', $this->float());
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME, 'jackPotPerUnit');
    }
}
