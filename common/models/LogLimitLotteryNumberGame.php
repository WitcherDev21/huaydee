<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "limit_lottery_number_game".
 *
 * @property int $id
 * @property int $thaiSharedGameId
 * @property int $playTypeId
 * @property string $number
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $createdBy
 * @property int $updatedBy
 * @property float $jackPotPerUnit
 * @property int $amountLimit
 * @property int $isLimitByUser
 *
 * @property PlayType $playType
 * @property ThaiSharedGame $thaiSharedGame
 */
class LogLimitLotteryNumberGame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_limit_lottery_number_game}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thaiSharedGameId', 'playTypeId', 'number', 'createdBy', 'jackPotPerUnit'], 'required'],
            [['thaiSharedGameId', 'playTypeId', 'createdBy', 'updatedBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['jackPotPerUnit', 'isLimitByUser', 'amountLimit'], 'number'],
            [['number'], 'string', 'max' => 255],
            [['playTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => PlayType::className(), 'targetAttribute' => ['playTypeId' => 'id']],
            [['thaiSharedGameId'], 'exist', 'skipOnError' => true, 'targetClass' => ThaiSharedGame::className(), 'targetAttribute' => ['thaiSharedGameId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thaiSharedGameId' => 'Thai Shared Game ID',
            'playTypeId' => 'Play Type ID',
            'number' => 'Number',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'jackPotPerUnit' => 'Jackpot Per Unit',
            'isLimitByUser' => 'Is Limit By User',
            'amountLimit' => 'Amount Limit'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayType()
    {
        return $this->hasOne(PlayType::className(), ['id' => 'playTypeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThaiSharedGame()
    {
        return $this->hasOne(ThaiSharedGame::className(), ['id' => 'thaiSharedGameId']);
    }
}
