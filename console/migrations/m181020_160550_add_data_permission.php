<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/20/2018
 * Time: 4:05 PM
 */

class m181020_160550_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'รายการโพยหวยหุ้น',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'thai-shared-game',
            'sorting' => '21',
            'is_active' => 1
        ]);
    }
}