<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/24/2019
 * Time: 10:26 AM
 */

class m190224_222650_add_field_game_id_in_number_memo_table extends Migration
{
    const NUMBER_MEMO_TABLE_NAME = '{{%number_memo}}';
    const GAMES_TABLE_NAME = '{{%games}}';

    public function safeUp()
    {
        $this->addColumn(self::NUMBER_MEMO_TABLE_NAME, 'gameId', $this->integer()->notNull());
        $this->addForeignKey(
            '{{%gameId_number_memo}}',
            self::NUMBER_MEMO_TABLE_NAME,
            'gameId',
            self::GAMES_TABLE_NAME,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('{{%gameId_number_memo}}', self::NUMBER_MEMO_TABLE_NAME);
        $this->dropColumn(self::NUMBER_MEMO_TABLE_NAME, 'gameId');
    }
}