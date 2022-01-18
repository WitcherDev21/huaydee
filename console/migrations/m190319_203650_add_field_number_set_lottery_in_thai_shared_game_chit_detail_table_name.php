<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/19/2019
 * Time: 8:33 PM
 */

class m190319_203650_add_field_number_set_lottery_in_thai_shared_game_chit_detail_table_name extends Migration
{
    const THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME = '{{%thai_shared_game_chit_detail}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME, 'numberSetLottery', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME, 'numberSetLottery');
    }
}