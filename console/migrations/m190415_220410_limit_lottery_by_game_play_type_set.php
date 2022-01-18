<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 4/15/2019
 * Time: 10:04 PM
 */

class m190415_220410_limit_lottery_by_game_play_type_set extends Migration
{
    const LIMIT_LOTTERY_BY_GAME_PLAY_TYPE_SET = '{{%limit_lottery_by_game_play_type_set}}';
    const PLAY_TYPE = '{{%play_type}}';

    public function safeUp()
    {
        $this->createTable(self::LIMIT_LOTTERY_BY_GAME_PLAY_TYPE_SET, [
            'id' => $this->primaryKey(),
            'playTypeId' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'number' => $this->string(),
            'status' => $this->integer()->defaultValue(1),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey(
            '{{%limit_lottery_by_game_play_type_set_playTypeId}}',
            self::LIMIT_LOTTERY_BY_GAME_PLAY_TYPE_SET,
            'playTypeId',
            self::PLAY_TYPE,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::LIMIT_LOTTERY_BY_GAME_PLAY_TYPE_SET);
    }
}