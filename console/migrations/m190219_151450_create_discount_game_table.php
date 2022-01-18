<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/19/2019
 * Time: 3:17 PM
 */

class m190219_151450_create_discount_game_table extends Migration
{
    const DISCOUNT_GAME_TABLE_NAME = '{{%discount_game}}';
    const PLAY_TYPE_TABLE_NAME = '{{%play_type}}';

    public function safeUp()
    {
       $this->createTable(self::DISCOUNT_GAME_TABLE_NAME, [
           'id' => $this->primaryKey(),
           'playTypeId' => $this->integer()->notNull(),
           'discount' => $this->float()->notNull(),
           'createdBy' => $this->integer()->notNull(),
           'updatedBy' => $this->integer()->notNull(),
           'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
           'updatedAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
       ]);

        $this->addForeignKey(
            '{{%playTypeId_discount_game}}',
            self::DISCOUNT_GAME_TABLE_NAME,
            'playTypeId',
            self::PLAY_TYPE_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::DISCOUNT_GAME_TABLE_NAME);
    }
}