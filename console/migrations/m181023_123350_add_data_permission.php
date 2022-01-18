<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/22/2018
 * Time: 8:11 PM
 */

class m181023_123350_add_data_permission extends Migration
{
    const PERMISSION_TABLE_NAME = '{{%auth_permission}}';

    public function safeUp()
    {
        $this->insert(self::PERMISSION_TABLE_NAME, [
            'name' => 'ประเภทการเล่น',
            'auth_route_type_id' => 1,
            'route_controller_name' => 'play-type-game',
            'sorting' => '25',
            'is_active' => 1
        ]);
    }
}