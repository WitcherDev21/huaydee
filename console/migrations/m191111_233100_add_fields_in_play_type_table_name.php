<?php

class m191111_233100_add_fields_in_play_type_table_name extends \yii\db\Migration
{
    const PLAY_TYPE_TABLE_NAME = '{{%play_type}}';

    public function safeUp()
    {
        $this->addColumn(self::PLAY_TYPE_TABLE_NAME, 'limitByUser', $this->float());
    }

    public function safeDown()
    {
        $this->dropColumn(self::PLAY_TYPE_TABLE_NAME, 'limitByUser');
    }
}
