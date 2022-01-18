<?php

class m191124_204350_add_field_image_and_url_pusher_table extends \yii\db\Migration
{
    const PUSHER_TABLE_NAME = '{{%pusher}}';

    public function safeUp()
    {
        $this->addColumn(self::PUSHER_TABLE_NAME, 'image', $this->string());
        $this->addColumn(self::PUSHER_TABLE_NAME, 'url', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::PUSHER_TABLE_NAME, 'image');
        $this->dropColumn(self::PUSHER_TABLE_NAME, 'url');
    }
}
