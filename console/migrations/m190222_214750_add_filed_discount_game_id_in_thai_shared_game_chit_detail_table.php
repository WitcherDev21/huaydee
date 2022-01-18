<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/22/2019
 * Time: 9:48 PM
 */

class m190222_214750_add_filed_discount_game_id_in_thai_shared_game_chit_detail_table extends Migration
{
    const THAI_SHARED_GAME_CHIT_DETAIL_TABLE = '{{%thai_shared_game_chit_detail}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE, 'discountGameId', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE, 'discountGameId');
    }
}