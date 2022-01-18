<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/21/2018
 * Time: 11:38 AM
 */

class m181021_113850_add_data_thai_shared_game_answer_in_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'เฉลยหวยหุ้น',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'thai-shared-game-answer',
            'sorting' => '22',
            'is_active' => 1
        ]);
    }
}