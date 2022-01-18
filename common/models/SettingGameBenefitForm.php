<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting_benefit".
 *
 * @property int $id
 * @property int $game_id
 * @property int $status
 * @property string $value
 * @property string $value_type percent, is_use = 1
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 */
class SettingGameBenefitForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $post_order_1;
    public $post_order_16;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_order_1', 'post_order_16'], 'required'],
            [['post_order_1', 'post_order_16'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'post_order_1' => 'รางวัลลำดับที่ยิงเลขที่ 1',
            'post_order_16' => 'รางวัลลำดับที่ยิงเลขที่ 16',
        ];
    }
}
