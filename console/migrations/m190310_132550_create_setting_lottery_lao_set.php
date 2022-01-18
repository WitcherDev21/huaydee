<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/10/2019
 * Time: 1:26 PM
 */

class m190310_132550_create_setting_lottery_lao_set extends Migration
{
    const SETTING_LOTTERY_LAO_SET = '{{%setting_lottery_lao_set}}';
    const GAMES_TABLE_NAME = '{{%games}}';

    public function safeUp()
    {
        $this->createTable(self::SETTING_LOTTERY_LAO_SET, [
            'id' => $this->primaryKey(),
            'gameId' => $this->integer()->notNull(),
            'value' => $this->float()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%gameId_setting_lottery_lao_set}}',
            self::SETTING_LOTTERY_LAO_SET,
            'gameId',
            self::GAMES_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::SETTING_LOTTERY_LAO_SET);
    }
}