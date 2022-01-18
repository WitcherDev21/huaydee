<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "condition_withdraw_user_transaction".
 *
 * @property int $id
 * @property int $userId
 * @property string $totalConditionWithDraw
 * @property string $amount
 * @property int $conditionWithdrawId
 * @property string $updateAt
 *
 * @property ConditionWithdraw $conditionWithdraw
 * @property User $user
 */
class ConditionWithdrawUserTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%condition_withdraw_user_transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'conditionWithdrawId'], 'required'],
            [['userId', 'conditionWithdrawId'], 'integer'],
            [['totalConditionWithDraw', 'amount'], 'number'],
            [['updateAt'], 'safe'],
            [['conditionWithdrawId'], 'exist', 'skipOnError' => true, 'targetClass' => ConditionWithdraw::className(), 'targetAttribute' => ['conditionWithdrawId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'totalConditionWithDraw' => 'Total Condition With Draw',
            'conditionWithdrawId' => 'Condition Withdraw ID',
            'updateAt' => 'Update At',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConditionWithdraw()
    {
        return $this->hasOne(ConditionWithdraw::className(), ['id' => 'conditionWithdrawId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
