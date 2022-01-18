<?php

class m191105_221800_add_field_time_in_pusher_table extends \yii\db\Migration
{
    const PUSHER_TABLE_NAME = '{{%pusher}}';

    public function safeUp()
    {
        $this->addColumn(self::PUSHER_TABLE_NAME, 'time', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn(self::PUSHER_TABLE_NAME, 'time');
    }
}