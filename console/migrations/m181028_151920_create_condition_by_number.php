<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/28/2018
 * Time: 3:20 PM
 */

class m181028_151920_create_condition_by_number extends Migration
{
    const CONDITION_LIMIT_NUMBER_TABLE_NAME = '{{%condition_limit_number}}';
    const GAMES_TABLE_NAME = '{{%games}}';
    const PLAY_TYPE_TABLE_NAME = '{{%play_type}}';

    public function safeUp()
    {
        $this->createTable(self::CONDITION_LIMIT_NUMBER_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'limit' => $this->string()->notNull(),
            'playTypeId' => $this->integer()->notNull(),
            'gameId' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%playTypeId_condition_limit_number}}',
            self::CONDITION_LIMIT_NUMBER_TABLE_NAME,
            'playTypeId',
            self::PLAY_TYPE_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%gameId_condition_limit_number}}',
            self::CONDITION_LIMIT_NUMBER_TABLE_NAME,
            'gameId',
            self::GAMES_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::CONDITION_LIMIT_NUMBER_TABLE_NAME);
    }
}