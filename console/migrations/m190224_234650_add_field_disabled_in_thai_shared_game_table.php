<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/24/2019
 * Time: 8:49 PM
 */

class m190224_234650_add_field_disabled_in_thai_shared_game_table extends Migration
{
    const THAI_SHARED_GAME_TABLE = '{{%thai_shared_game}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_SHARED_GAME_TABLE, 'disabled', $this->integer()->defaultValue(1));
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_SHARED_GAME_TABLE, 'disabled');
    }
}