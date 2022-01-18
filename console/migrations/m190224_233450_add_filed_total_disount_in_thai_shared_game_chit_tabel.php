<?php
/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/24/2019
 * Time: 3:36 PM
 */

class m190224_233450_add_filed_total_disount_in_thai_shared_game_chit_tabel extends  \yii\db\Migration
{
    const THAI_SHARED_GAME_CHIT = '{{%thai_shared_game_chit}}';
    const THAI_SHARED_GAME_CHIT_DETAIL = '{{%thai_shared_game_chit_detail}}';

    public function safeUp()
    {
        $this->addColumn(self::THAI_SHARED_GAME_CHIT_DETAIL, 'discount', $this->decimal(11,2));
        $this->addColumn(self::THAI_SHARED_GAME_CHIT, 'totalDiscount', $this->decimal(11,2));
    }

    public function safeDown()
    {
        $this->dropColumn(self::THAI_SHARED_GAME_CHIT_DETAIL, 'discount');
        $this->dropColumn(self::THAI_SHARED_GAME_CHIT, 'totalDiscount');
    }
}