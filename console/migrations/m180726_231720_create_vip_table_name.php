<?php

use yii\db\Migration;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 7/26/2018
 * Time: 11:18 PM
 */

class m180726_231720_create_vip_table_name extends Migration
{
    const VIP_TABLE_NAME = '{{%vip}}';

    public function safeUp()
    {
        $this->createTable(self::VIP_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'point' => $this->float()->notNull(),
            'commissionThreeTop' => $this->float(),
            'commissionThreeTod' => $this->float(),
            'commissionTwoTop' => $this->float(),
            'commissionTwoTod' => $this->float(),
            'commissionRunOn' => $this->float(),
            'commissionRunUnder' => $this->float(),
            'createdBy' => $this->integer()->notNull(),
            'createdAt' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(self::VIP_TABLE_NAME);
    }
}