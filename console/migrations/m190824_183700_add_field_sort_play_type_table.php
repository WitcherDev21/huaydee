<?php

class m190824_183700_add_field_sort_play_type_table extends \yii\db\Migration
{
    const PLAY_TYPES_TABLE_NAME = '{{%play_type}}';

    public function safeUp()
    {
        $this->addColumn(self::PLAY_TYPES_TABLE_NAME, 'sort', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::PLAY_TYPES_TABLE_NAME, 'sort');
    }
}