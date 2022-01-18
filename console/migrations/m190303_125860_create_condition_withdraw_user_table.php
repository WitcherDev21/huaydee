<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/3/2019
 * Time: 12:59 PM
 */

class m190303_125860_create_condition_withdraw_user_table extends Migration
{
    const CONDITION_WITHDRAW_USER_TABLE_NAME = '{{%condition_withdraw_user}}';
    const USER_TABLE_NAE = '{{%user}}';

    public function safeUp()
    {
        $this->createTable(self::CONDITION_WITHDRAW_USER_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'totalConditionWithDraw' => $this->decimal(11,2),
            'amount' => $this->decimal(11,2),
            'updateAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%userId_condition_withdraw_user}}',
            self::CONDITION_WITHDRAW_USER_TABLE_NAME,
            'userId',
            self::USER_TABLE_NAE,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::CONDITION_WITHDRAW_USER_TABLE_NAME);
    }
}