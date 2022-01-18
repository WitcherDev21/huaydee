<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/29/2018
 * Time: 11:20 AM
 */

class m181016_211050_create_thai_shared_game_chit_detail_table_name extends Migration
{
    const THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME =  '{{%thai_shared_game_chit_detail}}';
    const PLAY_TYPE_TABLE_NAME = '{{%play_type}}';
    const USER_TABLE_NAME = '{{%user}}';
    const THAI_SHARED_GAME_CHIT_TABLE_NAME = '{{%thai_shared_game_chit}}';

    public function safeUp()
    {
        $this->createTable(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME,[
            'id' => $this->primaryKey(),
            'number' => $this->string(),
            'playTypeId' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'flag_result' => $this->integer()->defaultValue(0),
            'win_credit' => $this->decimal('10', '2'),
            'userId' => $this->integer()->notNull(),
            'createdBy' => $this->integer()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'thaiSharedGameChitId' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey(
            '{{%playTypeId_thai_shared_game_chit_detail}}',
            self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME,
            'playTypeId',
            self::PLAY_TYPE_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%userId_thai_shared_game_chit_detail}}',
            self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME,
            'userId',
            self::USER_TABLE_NAME,
            'id'
        );
        $this->addForeignKey(
            '{{%thaiSharedGameChitId_thai_shared_game_chit_detail}}',
            self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME,
            'thaiSharedGameChitId',
            self::THAI_SHARED_GAME_CHIT_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::THAI_SHARED_GAME_CHIT_DETAIL_TABLE_NAME);
    }
}
