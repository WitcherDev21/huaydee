<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 7/29/2018
 * Time: 11:30 PM
 */

class m180729_233030_create_session_table_name extends Migration
{
    const SESSION_TABLE_NAME = '{{%session}}';

    public function safeUp()
    {
        $this->createTable(self::SESSION_TABLE_NAME, [
            'id' => $this->char(40)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            'user_id' => $this->integer()
        ]);
        $this->addPrimaryKey('session_pk', 'session', 'id');
    }

    public function safeDown()
    {
        $this->dropTable(self::SESSION_TABLE_NAME);
    }
}