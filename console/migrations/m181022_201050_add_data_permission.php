<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m181022_201050_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'สร้างเกมหวยหุ้น',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'create-shared-game',
            'sorting' => '22',
            'is_active' => 1
        ]);
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'แก้ไขเกมหวยหุ้น',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'update-shared-game',
            'sorting' => '23',
            'is_active' => 1
        ]);
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'สร้างเฉลยเกมหวยหุ้น',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'answer-shared-game',
            'sorting' => '23',
            'is_active' => 1
        ]);
    }
}