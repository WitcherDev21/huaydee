<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "limit_auto_lottery_number_game".
 *
 * @property int $id
 * @property int $thaiSharedGameId
 * @property int $minimumRate
 * @property int $maximumRate
 * @property int $playTypeId
 * @property double $jackPotPerUnit
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $createdBy
 * @property int $updatedBy
 *
 * @property PlayType $playType
 * @property ThaiSharedGame $thaiSharedGame
 */
class LimitAutoLotteryNumberGame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%limit_auto_lottery_number_game}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thaiSharedGameId', 'maximumRate', 'playTypeId', 'jackPotPerUnit', 'minimumRate'], 'required'],
            [['thaiSharedGameId', 'playTypeId', 'createdBy', 'updatedBy', 'maximumRate', 'minimumRate'], 'integer'],
            [['jackPotPerUnit'], 'number'],
            [['playTypeId'], 'validateMaximum'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['playTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => PlayType::className(), 'targetAttribute' => ['playTypeId' => 'id']],
            [['thaiSharedGameId'], 'exist', 'skipOnError' => true, 'targetClass' => ThaiSharedGame::className(), 'targetAttribute' => ['thaiSharedGameId' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('NOW()'),
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
            'thaiSharedGameId' => 'Thai Shared Game ID',
            'minimumRate' => 'Minimum Rate',
            'maximumRate' => 'Maximum Rate',
            'playTypeId' => 'Play Type ID',
            'jackPotPerUnit' => 'Jack Pot Per Unit',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
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

    public function validateMaximum($model, $attribute)
    {
        $limitCount = LimitAutoLotteryNumberGame::find()->where(['playTypeId' => $this->playTypeId, 'thaiSharedGameId' => $this->thaiSharedGameId])->count();
        $limitCount = $this->isNewRecord === false && $this->getOldAttribute('playTypeId') == $this->playTypeId ? $limitCount - 1 : $limitCount;
        if ($limitCount >= 6) {
            $this->addError('playTypeId', 'สามารถกำหนดประเภท '.$this->playType->title.' ได้สูงสุด 6');
        }
    }
}
