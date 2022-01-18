<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m181115_223450_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'ยกเลิกหวยหุ้น',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'cancel-shared-game',
            'sorting' => '28',
            'is_active' => 1
        ]);
    }
}