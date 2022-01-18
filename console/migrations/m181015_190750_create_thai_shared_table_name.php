<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/26/2018
 * Time: 8:55 PM
 */

class m181015_190750_create_thai_shared_table_name extends Migration
{
    const THAI_STOCK_GAME_NAME = '{{%thai_shared_game}}';
    const GAME_TABLE_NAME = '{{%games}}';

    public function safeUp()
    {
        $this->createTable(self::THAI_STOCK_GAME_NAME, [
            'id' => $this->primaryKey(),
            'gameId' => $this->integer()->notNull(),
            'round' => $this->string(),
            'startDate' => $this->dateTime(),
            'endDate' => $this->dateTime(),
            'createdBy' => $this->integer(),
            'updatedBy' => $this->integer(),
            'status' => $this->integer(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updateAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey(
            '{{%gameId_thai_shared_game}}',
            self::THAI_STOCK_GAME_NAME,
            'gameId',
            self::GAME_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::THAI_STOCK_GAME_NAME);
    }
}