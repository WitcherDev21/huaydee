<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/14/2019
 * Time: 9:29 PM
 */

class m190314_212750_add_field_setting_lottery_lao_set extends Migration
{
    const SETTING_LOTTERY_LAO_SET = '{{%setting_lottery_lao_set}}';

    public function safeUp()
    {
        $this->addColumn(self::SETTING_LOTTERY_LAO_SET, 'amountNumber', $this->integer()->defaultValue(0));
        $this->addColumn(self::SETTING_LOTTERY_LAO_SET, 'amountSet', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn(self::SETTING_LOTTERY_LAO_SET, 'amountNumber');
        $this->dropColumn(self::SETTING_LOTTERY_LAO_SET, 'amountSet');
    }
}