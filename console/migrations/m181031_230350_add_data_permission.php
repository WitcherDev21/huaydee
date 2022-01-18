<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m181031_230350_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'รายการโพยดำแดง',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'blackred',
            'sorting' => '26',
            'is_active' => 1
        ]);
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'สร้างเกมดำแดง',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'create-game-blackred',
            'sorting' => '27',
            'is_active' => 1
        ]);
    }
}