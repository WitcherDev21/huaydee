<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 8/15/2018
 * Time: 8:56 PM
 */

class m180815_205650_add_field_recommend_in_user_table_name extends Migration
{
    const USER_TABLE_NAME = '{{%user}}';

    public function safeUp()
    {
        $this->addColumn(self::USER_TABLE_NAME, 'recommend', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::USER_TABLE_NAME, 'recommend');
    }
}