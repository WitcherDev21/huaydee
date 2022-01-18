<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m190419_204200_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'สรุปยอดรายเกม',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'summary-game',
            'sorting' => '38',
            'is_active' => 1
        ]);
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'สรุปยอดรายวัน',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'summary-daily',
            'sorting' => '39',
            'is_active' => 1
        ]);
    }
}