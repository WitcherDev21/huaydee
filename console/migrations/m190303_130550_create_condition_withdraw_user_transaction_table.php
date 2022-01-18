<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/3/2019
 * Time: 1:05 PM
 */
class m190303_130550_create_condition_withdraw_user_transaction_table extends Migration
{
    const CONDITION_WITHDRAW_USER_TRANSACTION_TABLE_NAME = '{{%condition_withdraw_user_transaction}}';
    const USER_TABLE_NAME = '{{%user}}';
    const CONDITION_WITH_DRAW_TABLE_NAME = '{{%condition_withdraw}}';

    public function safeUp()
    {
        $this->createTable(self::CONDITION_WITHDRAW_USER_TRANSACTION_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'totalConditionWithDraw' => $this->decimal(11,2),
            'amount' => $this->decimal(11,2),
            'conditionWithdrawId' => $this->integer()->notNull(),
            'updateAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%userId_condition_withdraw_user_transaction}}',
            self::CONDITION_WITHDRAW_USER_TRANSACTION_TABLE_NAME,
            'userId',
            self::USER_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%conditionWithdrawId_condition_withdraw_user_transaction}}',
            self::CONDITION_WITHDRAW_USER_TRANSACTION_TABLE_NAME,
            'conditionWithdrawId',
            self::CONDITION_WITH_DRAW_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::CONDITION_WITHDRAW_USER_TRANSACTION_TABLE_NAME);
    }
}