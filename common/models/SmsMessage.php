<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sms_message".
 *
 * @property int $id
 * @property int $message_id
 * @property string $message
 * @property string $amount
 * @property string $date
 * @property string $bank
 * @property string $action
 * @property string $createdAt
 * @property integer $is_used
 */
class SmsMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms_message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'message', 'amount', 'date', 'bank', 'action'], 'required'],
            [['message_id', 'is_used'], 'integer'],
            [['amount'], 'number'],
            [['date', 'createdAt'], 'safe'],
            [['message', 'bank', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'message' => 'Message',
            'amount' => 'Amount',
            'date' => 'Date',
            'bank' => 'Bank',
            'action' => 'Action',
            'createdAt' => 'Created At',
            'is_used' => 'Is Used'
        ];
    }
}
