<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/21/2018
 * Time: 11:55 AM
 */

class m181021_115550_create_type_game_shared_table extends Migration
{
    const TYPE_SHARED_GAME_TABLE_NAME = '{{%type_game_shared}}';

    public function safeUp()
    {
        $this->createTable(self::TYPE_SHARED_GAME_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'status' => $this->integer()->defaultValue(1),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

       $this->insert(self::TYPE_SHARED_GAME_TABLE_NAME, [
           'title' => 'หวยหุ้นไทย',
       ]);
        $this->insert(self::TYPE_SHARED_GAME_TABLE_NAME, [
            'title' => 'หวยหุ้นต่างประเทศ',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(self::TYPE_SHARED_GAME_TABLE_NAME);
    }
}