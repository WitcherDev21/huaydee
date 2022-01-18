<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 4/15/2019
 * Time: 10:04 PM
 */

class m190415_220450_limit_lottery_by_game_play_type extends Migration
{
    const LIMIT_LOTTERY_BY_GAME_PLAY_TYPE = '{{%limit_lottery_by_game_play_type}}';
    const LIMIT_LOTTERY_BY_GAME_PLAY_TYPE_SET = '{{%limit_lottery_by_game_play_type_set}}';

    public function safeUp()
    {
        $this->createTable(self::LIMIT_LOTTERY_BY_GAME_PLAY_TYPE, [
            'id' => $this->primaryKey(),
            'level' => $this->string()->notNull(),
            'min' => $this->integer()->notNull(),
            'max' => $this->integer()->notNull(),
            'discountPlayTypeJackPotPerUnit' => $this->integer()->notNull(),
            'limitLotteryGamePlayTypeSetId' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey(
            '{{%limit_lottery_by_game_play_type_set_limitLotteryGamePlayTypeSet}}',
            self::LIMIT_LOTTERY_BY_GAME_PLAY_TYPE,
            'limitLotteryGamePlayTypeSetId',
            self::LIMIT_LOTTERY_BY_GAME_PLAY_TYPE_SET,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::LIMIT_LOTTERY_BY_GAME_PLAY_TYPE);
    }
}