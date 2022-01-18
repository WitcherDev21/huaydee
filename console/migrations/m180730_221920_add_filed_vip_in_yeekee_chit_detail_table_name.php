<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 7/30/2018
 * Time: 10:19 PM
 */

class m180730_221920_add_filed_vip_in_yeekee_chit_detail_table_name extends Migration
{
    const  YEEKEE_CHIT_DETAIL = '{{%yeekee_chit_detail}}';

    public function safeUp()
    {
        $this->addColumn(self::YEEKEE_CHIT_DETAIL, 'vipId', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn(self::YEEKEE_CHIT_DETAIL, 'vipId');
    }
}