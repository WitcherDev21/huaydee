<?php

namespace common\models;
use common\libs\Constants;
use Yii;

/**
 * This is the model class for table "thai_shared_game_chit_detail".
 *
 * @property int $id
 * @property string $number
 * @property int $playTypeId
 * @property int $amount
 * @property int $flag_result
 * @property string $win_credit
 * @property int $userId
 * @property int $createdBy
 * @property string $createdAt
 * @property int $thaiSharedGameChitId
 * @property string $discount
 * @property int $setNumber
 * @property string $numberSetLottery
 * @property float $jackPotPerUnit
 *
 * @property ThaiSharedGameChit $lotteryGameChit
 * @property PlayType $playType
 * @property User $user
 * @property int $discountGameId
 * @property ThaiSharedGameChit $thaiSharedGameChit
 * @property $getWinCredit
 */
class ThaiSharedGameChitDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%thai_shared_game_chit_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['playTypeId', 'amount', 'userId', 'createdBy', 'thaiSharedGameChitId'], 'required'],
            [['playTypeId', 'flag_result', 'userId', 'createdBy', 'thaiSharedGameChitId', 'discountGameId', 'setNumber'], 'integer'],
            [['win_credit', 'discount', 'numberSetLottery', 'jackPotPerUnit'], 'number'],
            [['discountGameId', 'setNumber'], 'default', 'value' => 0],
            [['discountGameId'], 'validateValueIsExits'],
            [['createdAt'], 'safe'],
            [['number', 'numberSetLottery'], 'string', 'max' => 255],
            [['thaiSharedGameChitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ThaiSharedGameChit::className(),
                'targetAttribute' => ['thaiSharedGameChitId' => 'id']
            ],
            [['playTypeId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PlayType::className(),
                'targetAttribute' => ['playTypeId' => 'id']
            ],
            [['userId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['userId' => 'id']
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
            'number' => 'Number',
            'playTypeId' => 'Play Type ID',
            'amount' => 'Amount',
            'flag_result' => 'Flag Result',
            'win_credit' => 'Win Credit',
            'userId' => 'User ID',
            'createdBy' => 'Created By',
            'createdAt' => 'Created At',
            'thaiSharedGameChitId' => 'Thai Shared Game Chit ID',
            'discountGameId' => 'Discount Game Id',
            'discount' => 'Discount',
            'numberSetLottery' => 'Number Set Lottery',
            'chit_amount' => 'Total Chit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThaiSharedGameChit()
    {
        return $this->hasOne(ThaiSharedGameChit::className(), ['id' => 'thaiSharedGameChitId']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function validateValueIsExits($attribute)
    {
        if ($this->$attribute) {
            $countDiscount = DiscountGame::find()->where(['id' => $this->$attribute])->count();
            if (!$countDiscount) {
                $this->addError($attribute, 'discount game id not found');
            }
        }
    }

    public function getWinCredit()
    {
        if ($this->thaiSharedGameChit->thaiSharedGame->gameId === \common\libs\Constants::LOTTERYLAOGAME && $this->thaiSharedGameChit->thaiSharedGame->gameId === \common\libs\Constants::LOTTERYLAODISCOUNTGAME) {
            $winCredit = $this->playType->jackpot_per_unit * $this->setNumber;
        } else {
            $winCredit = $this->playType->jackpot_per_unit * $this->amount;
        }
        return $winCredit;
    }
}
