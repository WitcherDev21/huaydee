<?php

namespace common\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\validators\RequiredValidator;

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
 * @property int $totalSetNumber
 * @property int $amountLimit
 * @property int $isLimitByUser
 *
 * @property PlayType $playType
 * @property ThaiSharedGame $thaiSharedGame
 */
class LimitLotteryNumberGame extends \yii\db\ActiveRecord
{
    public $numberFrom;
    public $numberTo;
    public $limits;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%limit_lottery_number_game}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thaiSharedGameId', 'playTypeId', 'jackPotPerUnit'], 'required'],
            [['number', 'totalSetNumber'], 'required', 'on' => 'lotteryLaoSet'],
            [['numberFrom'], 'required'],
            [['thaiSharedGameId', 'playTypeId', 'createdBy', 'updatedBy'], 'integer'],
            [['isLimitByUser'], 'default', 'value'=> 0],
            [['totalSetNumber'], 'integer', 'on' => 'lotteryLaoSet'],
            ['numberTo', 'validateNumberBetween'],
            [['numberTo','numberFrom'], 'ValidatePlayType'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['jackPotPerUnit', 'numberFrom', 'numberTo', 'isLimitByUser', 'amountLimit'], 'number'],
            [['number'], 'string', 'max' => 255],
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

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['lotteryLaoSet'] = ['number','totalSetNumber'];//Scenario Values Only Accepted
        return $scenarios;
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
            'totalSetNumber' => 'Total Set Number',
            'numberFrom' => 'Number From',
            'numberTo' => 'Number To',
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

    public function ValidateNumberBetween()
    {
        if ($this->numberTo !== '') {
            if ($this->numberFrom === '') {
                $this->addError('numberFrom', 'Required Number From');
            }elseif ($this->numberFrom > $this->numberTo) {
                $this->addError('numberTo', 'Number To > Number From');
            }
        }
        return true;
    }

    public function ValidatePlayType($attribute_name)
    {
        $numberLength = $this->playType->group->number_length;
        if ($attribute_name !== '' && (string)$numberLength != strlen((string)$this->$attribute_name)) {
            $this->addError($attribute_name, 'number length in valid');
        }
        return true;
    }
}
