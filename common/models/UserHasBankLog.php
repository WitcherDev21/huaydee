<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_has_bank_log".
 *
 * @property int $id
 * @property int $user_has_bank_id
 * @property int $user_id
 * @property int $bank_id
 * @property string $bank_account_name
 * @property string $bank_account_no
 * @property int $status
 * @property int $version
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 *
 * @property Bank $bank
 * @property User $user
 */
class UserHasBankLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_has_bank_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_has_bank_id', 'user_id', 'bank_id', 'status', 'version', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['bank_account_name'], 'string', 'max' => 100],
            [['bank_account_no'], 'string', 'max' => 30],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_has_bank_id' => 'User Has Bank ID',
            'user_id' => 'User ID',
            'bank_id' => 'Bank ID',
            'bank_account_name' => 'Bank Account Name',
            'bank_account_no' => 'Bank Account No',
            'status' => 'Status',
            'version' => 'Version',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
