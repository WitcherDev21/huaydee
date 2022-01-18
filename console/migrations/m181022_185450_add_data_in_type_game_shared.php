<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 6:54 PM
 */

class m181022_185450_add_data_in_type_game_shared extends Migration
{
    const TYPE_SHARED_GAME_TABLE_NAME = '{{%type_game_shared}}';


    public function safeUp()
    {
        $this->insert(self::TYPE_SHARED_GAME_TABLE_NAME, [
            'title' => 'หวยหุ้นไทย 20 คู่',
        ]);
    }
}