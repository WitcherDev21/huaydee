<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 4/8/2019
 * Time: 11:06 PM
 */

class m190408_230640_add_bot_black_red_fix_result_table_name extends Migration
{
    const BOT_BLACK_RED_FIX_RESULT_TABLE_NAME = '{{%bot_black_red_fix_result}}';

    public function safeUp()
    {
        $this->createTable(self::BOT_BLACK_RED_FIX_RESULT_TABLE_NAME,[
            'id' => $this->primaryKey(),
            'play_type_code' => $this->integer()->notNull(),
            'round' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(self::BOT_BLACK_RED_FIX_RESULT_TABLE_NAME);
    }
}