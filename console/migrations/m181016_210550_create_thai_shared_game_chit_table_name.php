<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/29/2018
 * Time: 11:02 AM
 */

class m181016_210550_create_thai_shared_game_chit_table_name extends Migration
{
    const THAI_SHARED_GAME_CHIT_TABLE_NAME = '{{%thai_shared_game_chit}}';
    const THAI_SHARED_GAME_TABLE_NAME = '{{%thai_shared_game}}';
    const USER_TABLE_NAME = '{{%user}}';

    public function safeUp()
    {
        $this->createTable(self::THAI_SHARED_GAME_CHIT_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'thaiSharedGameId' => $this->integer()->notNull(),
            'userId' => $this->integer()->notNull(),
            'createdBy' => $this->integer()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'totalAmount' => $this->decimal('11','2'),
            'status' => $this->integer()->notNull(),
            'updatedAt' => $this->dateTime(),
            'updatedBy' => $this->integer()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey(
            '{{%gameLotteryId_thai_shared_game_chit}}',
            self::THAI_SHARED_GAME_CHIT_TABLE_NAME,
            'thaiSharedGameId',
            self::THAI_SHARED_GAME_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%userId_thai_shared_game_chit}}',
            self::THAI_SHARED_GAME_CHIT_TABLE_NAME,
            'userId',
            self::USER_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::THAI_SHARED_GAME_CHIT_TABLE_NAME);
    }
}
