<?php

class m200726_095050_add_field_limit_auto_in_thai_shared_game_table extends \yii\db\Migration
{
    const THAI_SHARED_GAME_TABLE_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_SHARED_GAME_TABLE_NAME, 'limitAuto', $this->integer()->defaultValue(1));
    }

    public function safeDown()
    {
    }
}
