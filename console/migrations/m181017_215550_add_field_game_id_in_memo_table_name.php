<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/17/2018
 * Time: 9:57 PM
 */
class m181017_215550_add_field_game_id_in_memo_table_name extends Migration
{
    const NUMBER_MEMO_TABLE_NAME = '{{%number_memo}}';
    const GAME_TABLE_NAME = '{{%games}}';

    public function safeUp()
    {
        $this->addColumn(self::NUMBER_MEMO_TABLE_NAME, 'gameId', $this->integer()->defaultValue(\common\libs\Constants::YEEKEE));
        $this->addForeignKey('{{%gameId-number-memo}}', self::NUMBER_MEMO_TABLE_NAME, 'gameId', self::GAME_TABLE_NAME, 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('{{%gameId-number-memo}}', self::NUMBER_MEMO_TABLE_NAME);
        $this->dropColumn(self::NUMBER_MEMO_TABLE_NAME, 'gameId');
    }

}