<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lottery_game_chit_answer".
 *
 * @property int $id
 * @property int $thaiSharedGameId
 * @property int $playTypeId
 * @property string $number
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property ThaiSharedGame $thaiSharedGame
 * @property PlayType $playType
 */
class ThaiSharedAnswerGame extends \yii\db\ActiveRecord
{
    public $endDate;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%thai_shared_answer_game}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thaiSharedGameId', 'playTypeId', 'number', 'createdBy'], 'required'],
            [['thaiSharedGameId', 'playTypeId', 'createdBy', 'updatedBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['number'], 'string', 'max' => 255],
            [['thaiSharedGameId'], 'exist', 'skipOnError' => true, 'targetClass' => ThaiSharedGame::className(), 'targetAttribute' => ['thaiSharedGameId' => 'id']],
            [['playTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => PlayType::className(), 'targetAttribute' => ['playTypeId' => 'id']],
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
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThaiSharedGame()
    {
        return $this->hasOne(ThaiSharedGame::className(), ['id' => 'thaiSharedGameId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayType()
    {
        return $this->hasOne(PlayType::className(), ['id' => 'playTypeId']);
    }
}
