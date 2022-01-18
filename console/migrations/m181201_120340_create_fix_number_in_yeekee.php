<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 12/1/2018
 * Time: 12:03 PM
 */

class m181201_120340_create_fix_number_in_yeekee extends Migration
{
    const FIX_NUMBER_BY_YEEKEE = '{{%fix_number_yeekee}}';
    const YEEKEE = '{{%yeekee}}';

    public function safeUp()
    {
        $this->createTable(self::FIX_NUMBER_BY_YEEKEE, [
            'id' => $this->primaryKey(),
            'number' => $this->text()->notNull(),
            'yeekeeId' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%yeekeeId_yeekee}}',
            self::FIX_NUMBER_BY_YEEKEE,
            'yeekeeId',
            self::YEEKEE,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable(self::FIX_NUMBER_BY_YEEKEE);
    }
}