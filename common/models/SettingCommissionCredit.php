<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting_commission_credit".
 *
 * @property int $id
 * @property int $type
 * @property string $value
 * @property int $is_active
 */
class SettingCommissionCredit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%setting_commission_credit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'is_active'], 'integer'],
            [['value'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'value' => 'Value',
            'is_active' => 'Is Active',
        ];
    }
}
