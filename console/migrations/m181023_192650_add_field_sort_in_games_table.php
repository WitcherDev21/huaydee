<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/23/2018
 * Time: 7:26 PM
 */

class m181023_192650_add_field_sort_in_games_table extends Migration
{
    const GAMES_TABLE_NAME = '{{%games}}';

    public function safeUp()
    {
        $this->addColumn(self::GAMES_TABLE_NAME, 'sort', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::GAMES_TABLE_NAME, 'sort');
    }
}