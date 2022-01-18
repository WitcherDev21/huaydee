<?php

namespace common\models;

use common\libs\Constants;
use Yii;

/**
 * This is the model class for table "yeekee_chit".
 *
 * @property int $id
 * @property int $user_id
 * @property int $yeekee_id
 * @property string $total_amount
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $status
 *
 * @property Yeekee $yeekee
 * @property User $user
 * @property YeekeeChitDetail[] $yeekeeChitDetails
 */
class YeekeeChit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yeekee_chit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'yeekee_id', 'create_at', 'create_by'], 'required'],
            [['user_id', 'yeekee_id', 'create_by', 'update_by', 'status'], 'integer'],
            [['total_amount'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['yeekee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yeekee::className(), 'targetAttribute' => ['yeekee_id' => 'id']],
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
            'user_id' => 'User ID',
            'yeekee_id' => 'Yeekee ID',
            'total_amount' => 'Total Amount',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekee()
    {
        return $this->hasOne(YeekeeSearch::className(), ['id' => 'yeekee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekeeChitDetails()
    {
        return $this->hasMany(YeekeeChitDetail::className(), ['yeekee_chit_id' => 'id']);
    }

    public function getOrder()
    {
        return Constants::YEEKEE.$this->id;
    }
}
