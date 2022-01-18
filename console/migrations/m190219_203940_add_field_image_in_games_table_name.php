<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/19/2019
 * Time: 8:39 PM
 */

class m190219_203940_add_field_image_in_games_table_name extends Migration
{
    const GAMES_TABLE_NAME = '{{%games}}';

    public function safeUp()
    {
        $this->addColumn(self::GAMES_TABLE_NAME, 'image', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::GAMES_TABLE_NAME, 'image');
    }
}