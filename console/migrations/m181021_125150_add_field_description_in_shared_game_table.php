<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/21/2018
 * Time: 12:51 PM
 */
class m181021_125150_add_field_description_in_shared_game_table extends Migration
{
    const THAI_STOCK_GAME_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_STOCK_GAME_NAME, 'description', $this->text(), 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_STOCK_GAME_NAME, 'description');
    }
}