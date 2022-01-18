<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m190405_220400_update_stracture_filed_balance extends Migration
{
    const COMMISSION_TABLE_NAME = '{{%commission}}';
    const COMMISSION_TRANSACTION_TABLE_NAME = '{{%commission_transection}}';

    public function safeUp()
    {
        $this->alterColumn(self::COMMISSION_TABLE_NAME, 'balance', $this->decimal(10,3));
        $this->alterColumn(self::COMMISSION_TRANSACTION_TABLE_NAME, 'amount', $this->decimal(10,3));
        $this->alterColumn(self::COMMISSION_TRANSACTION_TABLE_NAME, 'balance', $this->decimal(10,3));
    }
}