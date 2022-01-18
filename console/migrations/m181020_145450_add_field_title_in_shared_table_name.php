<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/20/2018
 * Time: 2:55 PM
 */

class m181020_145450_add_field_title_in_shared_table_name extends Migration
{
    const THAI_STOCK_GAME_NAME = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_STOCK_GAME_NAME,'title', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_STOCK_GAME_NAME, 'title');
    }
}