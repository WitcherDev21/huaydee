<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/23/2019
 * Time: 9:22 AM
 */

class m190223_092120_modifly_column_thai_shared_game_chit_and_detail_table extends Migration
{
    const THAI_SHARED_GAME_CHIT_TABLE_NAME = '{{%thai_shared_game_chit}}';
    const THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME = '{{%thai_shared_game_chit_detail}}';

    public function safeUp()
    {
        $this->alterColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME, 'amount', $this->decimal(11,2));
        $this->alterColumn(self::THAI_SHARED_GAME_CHIT_TABLE_NAME, 'totalAmount', $this->decimal(11,2));
        $this->alterColumn(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME, 'win_credit', $this->decimal(10,2));
    }
}