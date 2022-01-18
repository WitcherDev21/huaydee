<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/3/2019
 * Time: 11:39 AM
 */

class m190303_113950_create_condition_withdraw_table extends Migration
{
    const CONDITION_WITHDRAW_TABLE_NAME = '{{%condition_withdraw}}';

    public function safeUp()
    {
        $this->createTable(self::CONDITION_WITHDRAW_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'percent' => $this->float()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedAt' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable(self::CONDITION_WITHDRAW_TABLE_NAME);
    }
}