<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/12/2019
 * Time: 9:25 PM
 */

class m190312_212330_add_field_set_number_in_thai_shared_game_chit_detail extends Migration
{
    const THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME =  '{{%thai_shared_game_chit_detail}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME, 'setNumber', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME, 'setNumber');
    }
}