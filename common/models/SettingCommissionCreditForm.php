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
class SettingCommissionCreditForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $invite_value;
    public $agent_value;
    public $credit_value;
    public $withdrawCredit;
    public $noteValue;
    public $noteWithdraw;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invite_value', 'agent_value', 'credit_value'], 'required'],
            [['withdrawCredit'], 'number'],
            [['noteValue', 'noteWithdraw'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'invite_value' => 'คอมมิชชั่นแนะนำ ทั้งระบบ (%)',
            'agent_value' => 'คอมมิชชั่นเอเยนต์ ทั้งระบบ (%)',
            'credit_value' => 'เติมเงินเครดิตเพื่อ กำหนดการใช้งานของ Admin',
            'withdrawCredit' => 'ถอนเงินเครดิตออกจากระบบ',
            'noteValue' => 'โน๊ตเติมเงินเครดิต',
            'noteWithdraw' => 'โน๊ตถอนเงินเครดิต',
        ];
    }
}
