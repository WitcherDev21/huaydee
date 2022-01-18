<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post_credit_transection".
 *
 * @property int $id
 * @property int $poster_id
 * @property int $action_id
 * @property int $user_has_bank_id
 * @property int $user_has_bank_version
 * @property int $status
 * @property double $amount
 * @property string $remark
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property string $post_requir_time
 * @property string $channel
 * @property int $user_has_bank_id_user
 * @property int $is_auto
 *
 * @property User $poster
 * @property User $createBy
 * @property User $updateBy
 * @property UserHasBank $userHasBank
 * @property UserHasBank $userHasBankUser
 */
class PostCreditTransection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_credit_transection}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poster_id', 'action_id', 'user_has_bank_id', 'user_has_bank_version', 'amount', 'create_at', 'create_by', 'user_has_bank_id_user'], 'required'],
            [['poster_id', 'action_id', 'user_has_bank_id', 'user_has_bank_version', 'status', 'create_by', 'update_by', 'user_has_bank_id_user', 'is_auto'], 'integer'],
            [['amount'], 'number'],
            [['create_at', 'update_at', 'post_requir_time'], 'safe'],
            [['remark', 'channel'], 'string', 'max' => 255],
            [['poster_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['poster_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['update_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['update_by' => 'id']],
            [['user_has_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserHasBank::className(), 'targetAttribute' => ['user_has_bank_id' => 'id']],
            [['user_has_bank_id_user'], 'exist', 'skipOnError' => true, 'targetClass' => UserHasBank::className(), 'targetAttribute' => ['user_has_bank_id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poster_id' => 'Poster ID',
            'action_id' => 'Action ID',
            'user_has_bank_id' => 'User Has Bank ID',
            'user_has_bank_version' => 'User Has Bank Version',
            'status' => 'Status',
            'amount' => 'Amount',
            'remark' => 'Remark',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'post_requir_time' => 'Post Requir Time',
            'channel' => 'Channel',
            'user_has_bank_id_user' => 'User Has Bank ID User',
            'is_auto' => 'Is Auto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoster()
    {
        return $this->hasOne(User::className(), ['id' => 'poster_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'update_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasBank()
    {
        return $this->hasOne(UserHasBank::className(), ['id' => 'user_has_bank_id']);
    }

    public function getUserHasBankUser()
    {
        return $this->hasOne(UserHasBank::className(), ['id' => 'user_has_bank_id_user']);
    }

    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id'])
            ->via('userHasBank');
    }
}
