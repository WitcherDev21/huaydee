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
class SettingBenefitForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $yeekee_value;
    public $yeekee_status;
    public $blackred_value;
    public $blackred_status;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['yeekee_value', 'yeekee_status'], 'required'],
//            [['yeekee_value', 'yeekee_status', 'blackred_value', 'blackred_status'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'yeekee_value' => 'จับยี่กี่ ปรับกำไรทั้งระบบ (%)',
            'yeekee_status' => 'สถานะ',
            'blackred_value' => 'ดำ-แดง ปรับกำไรทั้งระบบ (%) ',
            'blackred_status' => 'สถานะ',
        ];
    }
}
