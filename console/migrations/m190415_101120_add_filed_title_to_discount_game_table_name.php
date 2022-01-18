<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 4/15/2019
 * Time: 10:11 AM
 */

class m190415_101120_add_filed_title_to_discount_game_table_name extends Migration
{
    const DISCOUNT_GAME_TABLE_NAME = '{{%discount_game}}';

    public function safeUp()
    {
        $this->addColumn(self::DISCOUNT_GAME_TABLE_NAME, 'title', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::DISCOUNT_GAME_TABLE_NAME, 'title');
    }
}