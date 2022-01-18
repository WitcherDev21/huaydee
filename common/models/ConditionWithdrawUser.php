<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "condition_withdraw_user".
 *
 * @property int $id
 * @property int $userId
 * @property string $totalConditionWithDraw
 * @property string $amount
 * @property string $updateAt
 *
 * @property User $user
 */
class ConditionWithdrawUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%condition_withdraw_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'integer'],
            [['totalConditionWithDraw', 'amount'], 'number'],
            [['updateAt'], 'safe'],
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
            'updateAt' => 'Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
