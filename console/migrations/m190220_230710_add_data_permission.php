<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 2/20/2019
 * Time: 11:08 PM
 */

class m190220_230710_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'จัดการส่วนลด',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'discount-management',
            'sorting' => '29',
            'is_active' => 1
        ]);
    }
}