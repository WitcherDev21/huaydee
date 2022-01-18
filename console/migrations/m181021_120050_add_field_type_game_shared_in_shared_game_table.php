<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/21/2018
 * Time: 12:00 PM
 */
class m181021_120050_add_field_type_game_shared_in_shared_game_table extends Migration
{
    const THAI_STOCK_GAME_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_STOCK_GAME_NAME, 'typeSharedGameId', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_STOCK_GAME_NAME, 'typeSharedGameId');
    }
}