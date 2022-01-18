<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m190329_235500_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'รายงานเครดิตมาสเตอร์',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'report-credit-master',
            'sorting' => '37',
            'is_active' => 1
        ]);
    }
}