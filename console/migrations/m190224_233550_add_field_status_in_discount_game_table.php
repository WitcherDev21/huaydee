<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/24/2019
 * Time: 3:40 PM
 */
class m190224_233550_add_field_status_in_discount_game_table extends Migration
{
    const DISCOUNT_GAME_TABLE = '{{%discount_game}}';

    public function safeUp()
    {
        $this->addColumn(self::DISCOUNT_GAME_TABLE, 'status', $this->integer()->defaultValue(1));
    }

    public function safeDown()
    {
        $this->dropColumn(self::DISCOUNT_GAME_TABLE, 'status');
    }
}