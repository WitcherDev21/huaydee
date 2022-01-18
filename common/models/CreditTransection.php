<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "credit_transection".
 *
 * @property int $id
 * @property int $action_id
 * @property int $operator_id
 * @property int $reciver_id
 * @property string $amount
 * @property string $balance
 * @property string $credit_master_balance
 * @property string $remark
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $reason_action_id
 *
 * @property User $operator
 * @property User $reciver
 * @property User $createBy
 * @property User $updateBy
 */
class CreditTransection extends \yii\db\ActiveRecord
{
    public $game;
    public $deposit;
    public $withdraw;
    public $total;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%credit_transection}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'action_id',
                    'operator_id',
                    'reciver_id',
                    'create_at',
                    'create_by'
                ],
                'required'
            ],
            [
                [
                    'action_id',
                    'operator_id',
                    'reciver_id',
                    'create_by',
                    'update_by',
                    'reason_action_id'
                ],
                'integer'
            ],
            [['amount', 'balance', 'credit_master_balance'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['remark'], 'string', 'max' => 255],
//            [
//                [
//                    'action_id',
//                    'operator_id',
//                    'reciver_id',
//                    'create_at',
//                    'reason_action_id',
//                    'amount',
//                    'remark'
//                ],
//                'unique',
//                'targetAttribute' => [
//                    'action_id',
//                    'operator_id',
//                    'reciver_id',
//                    'create_at',
//                    'reason_action_id',
//                    'amount',
//                    'remark'
//                ]
//            ],
            [
                ['operator_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['operator_id' => 'id']
            ],
            [
                [
                    'reciver_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['reciver_id' => 'id']
            ],
            [
                ['create_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['create_by' => 'id']
            ],
            [
                ['update_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['update_by' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_id' => 'Action ID',
            'operator_id' => 'Operator ID',
            'reciver_id' => 'Reciver ID',
            'amount' => 'Amount',
            'balance' => 'Balance',
            'credit_master_balance' => 'Credit Master Balance',
            'remark' => 'Remark',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'reason_action_id' => 'Reason Action ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(User::className(), ['id' => 'operator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReciver()
    {
        return $this->hasOne(User::className(), ['id' => 'reciver_id']);
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
}
