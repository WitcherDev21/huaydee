<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/29/2018
 * Time: 8:38 PM
 */

class m181029_183850_remove_filed_game_id_in_memo_game extends Migration
{
    const NUMBER_MEMO_TABLE_NAME = '{{%number_memo}}';
    const GAME_TABLE_NAME = '{{%games}}';

    public function safeUp()
    {
        $this->dropForeignKey('{{%gameId-number-memo}}', self::NUMBER_MEMO_TABLE_NAME);
        $this->dropColumn(self::NUMBER_MEMO_TABLE_NAME, 'gameId');
    }

    public function safeDown()
    {
    }
}