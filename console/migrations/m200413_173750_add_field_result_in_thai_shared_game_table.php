<?php

class m200413_173750_add_field_result_in_thai_shared_game_table extends \yii\db\Migration
{
    const THAI_SHARED_GAME_TABLE_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_SHARED_GAME_TABLE_NAME, 'result', $this->string());
    }

    public function safeDown()
    {
    }
}
