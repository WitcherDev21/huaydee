<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "commission_transection".
 *
 * @property int $id
 * @property int $action_id
 * @property int $operator_id
 * @property int $reciver_id
 * @property string $amount
 * @property string $balance
 * @property string $remark
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $reason_action_id
 */
class CommissionTransection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%commission_transection}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_id', 'operator_id', 'reciver_id', 'create_at', 'create_by'], 'required'],
            [['action_id', 'operator_id', 'reciver_id', 'create_by', 'update_by', 'reason_action_id'], 'integer'],
            [
                [
                    'action_id',
                    'operator_id',
                    'reciver_id',
                    'create_by',
                    'reason_action_id',
                    'amount',
                    'balance',
                    'remark'
                ],
                'unique',
                'targetAttribute' => [
                    'action_id',
                    'operator_id',
                    'reciver_id',
                    'create_by',
                    'reason_action_id',
                    'amount',
                    'balance',
                    'remark'
                ]
            ],
            [['amount', 'balance'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['remark'], 'string', 'max' => 255],
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
            'remark' => 'Remark',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'reason_action_id' => 'Reason Action ID',
        ];
    }
}
