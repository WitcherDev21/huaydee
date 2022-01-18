<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m190221_215050_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'สร้างเกมหวยรัฐ',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'create-lottery-game',
            'sorting' => '30',
            'is_active' => 1
        ]);
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'แก้ไขเกมหวยรัฐ',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'update-lottery-game',
            'sorting' => '31',
            'is_active' => 1
        ]);
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'สร้างเฉลยเกมหวยรัฐ',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'answer-lottery-game',
            'sorting' => '32',
            'is_active' => 1
        ]);
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'แก้ไขเฉลยหวยรัฐ',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'update-answer-lottery-game',
            'sorting' => '33',
            'is_active' => 1
        ]);
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'ยืนยันเฉลยหวยรัฐ',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'confirm-answer-lottery-game',
            'sorting' => '34',
            'is_active' => 1
        ]);
    }
}