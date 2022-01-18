<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting_lottery_lao_set".
 *
 * @property int $id
 * @property int $gameId
 * @property double $value
 * @property int $amountNumber
 * @property int $amountSet
 */
class SettingLotteryLaoSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%setting_lottery_lao_set}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gameId', 'value'], 'required'],
            [['gameId'], 'integer'],
            [['value', 'amountNumber', 'amountSet'], 'number'],
            [['gameId'], 'exist', 'skipOnError' => true, 'targetClass' => Games::className(), 'targetAttribute' => ['gameId' => 'id']],
            ['gameId', 'unique', 'targetAttribute' => 'gameId']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gameId' => 'Game ID',
            'value' => 'Value',
            'amountNumber' => 'Amount Number',
            'amountSet' => 'Amount Set',
        ];
    }

    public function getGame()
    {
        return $this->hasOne(Games::className(), ['id' => 'gameId']);
    }
}
