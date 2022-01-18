<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/23/2019
 * Time: 10:55 AM
 */

class m190323_105450_add_filed_disabled_bot_in_yeekee extends Migration
{
    const GAMES_TABLE_NAME = '{{%yeekee}}';

    public function safeUp()
    {
        $this->addColumn(self::GAMES_TABLE_NAME, 'isOpenBot', $this->integer()->defaultValue(1));
    }

    public function safeDown()
    {
        $this->dropColumn(self::GAMES_TABLE_NAME, 'isOpenBot');
    }
}