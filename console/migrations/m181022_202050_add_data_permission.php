<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m181022_202050_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'แก้ไขเฉลยหวยหุ้น',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'update-answer-shared-game',
            'sorting' => '24',
            'is_active' => 1
        ]);
    }
}