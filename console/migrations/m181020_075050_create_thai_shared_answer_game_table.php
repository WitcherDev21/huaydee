<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/20/2018
 * Time: 7:50 AM
 */

class m181020_075050_create_thai_shared_answer_game_table extends Migration
{
    const THAI_SHARED_ANSWER_GAME = '{{%thai_shared_answer_game}}';
    const PLAY_TYPE_TABLE = '{{%play_type}}';
    const THAI_SHARED_GAME = '{{%thai_shared_game}}';


    public function safeUp()
    {
        $this->createTable(self::THAI_SHARED_ANSWER_GAME, [
            'id' => $this->primaryKey(),
            'thaiSharedGameId' => $this->integer()->notNull(),
            'playTypeId' => $this->integer()->notNull(),
            'number' => $this->string()->notNull(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedAt' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey(
            '{{%thaiSharedGameId_thai_shared_answer_game}}',
            self::THAI_SHARED_ANSWER_GAME,
            'thaiSharedGameId',
            self::THAI_SHARED_GAME,
            'id'
        );
        $this->addForeignKey(
            '{{%playTypeId_thai_shared_answer_game}}',
            self::THAI_SHARED_ANSWER_GAME,
            'playTypeId',
            self::PLAY_TYPE_TABLE,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::THAI_SHARED_ANSWER_GAME);
    }
}