<?php

namespace common\models;

use common\libs\Constants;
use Yii;

/**
 * This is the model class for table "lottery_game_chit".
 *
 * @property int $id
 * @property int $thaiSharedGameId
 * @property int $userId
 * @property int $createdBy
 * @property string $createdAt
 * @property string $totalAmount
 * @property int $status
 * @property string $updatedAt
 * @property int $updatedBy
 * @property string $totalDiscount
 *
 * @property Lottery-game-chit-detail[] $lottery-game-chit-details
 * @property Lottery-game $gameLottery
 * @property User $user
 */
class ThaiSharedGameChit extends \yii\db\ActiveRecord
{
    public $setNumber;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%thai_shared_game_chit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thaiSharedGameId', 'userId', 'createdBy', 'status'], 'required'],
            [['thaiSharedGameId', 'userId', 'createdBy', 'status', 'updatedBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['totalAmount', 'totalDiscount'], 'number'],
            [['thaiSharedGameId'], 'exist', 'skipOnError' => true, 'targetClass' => ThaiSharedGame::className(), 'targetAttribute' => ['thaiSharedGameId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thaiSharedGameId' => 'Thai Shared Game Id',
            'userId' => 'User ID',
            'createdBy' => 'Created By',
            'createdAt' => 'Created At',
            'totalAmount' => 'Total Amount',
            'status' => 'Status',
            'updatedBy' => 'Updated By',
            'updatedAt' => 'Updated At',
            'totalDiscount' => 'Total Discount'
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThaiSharedGame()
    {
        return $this->hasOne(ThaiSharedGame::className(), ['id' => 'thaiSharedGameId']);
    }

    public function getThaiSharedGameChitDetail()
    {
        return $this->hasMany(ThaiSharedGameChitDetail::className(), ['thaiSharedGameChitId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function getThaiSharedGameChitDetails()
    {
        return $this->hasMany(ThaiSharedGameChitDetail::className(), ['thaiSharedGameChitId' => 'id']);
    }

    public static function getCreditCanPay($playData = [],$user_id, $reason = 0, $gameId){
        $payAmount = 0;
        $is_pass = false; //ขอให้มีการซื้อมาจริงๆ
        $maximumPlay = 0;
        $minimumPlay = 0;
        foreach($playData as $type => $data2){
            if ($gameId) {
                $playType = PlayType::find()->where(['code' => $type, 'game_id' => $gameId])->one();
                if ($gameId === Constants::LOTTERYLAODISCOUNTGAME || $gameId === Constants::LOTTERYLAOGAME) {
                    $settingLotteryLaoSet = SettingLotteryLaoSet::find()->where(['gameId' => $gameId])->one();
                    $maximumPlay = $settingLotteryLaoSet->amountSet;
                    $minimumPlay = 1;
                }else {
                    $maximumPlay = $playType->maximum_play;
                    $minimumPlay = $playType->minimum_play;
                }
                $discountGame = DiscountGame::find()->where(['playTypeId' => $playType->id, 'status' => 1])->orderBy('id DESC')->one();
            }
            $numSet = json_decode($data2);
            foreach($numSet as $number=>$amount){
                if ($amount > $maximumPlay) {
                    $amount = $maximumPlay;
                }elseif ($amount < $minimumPlay) {
                    $amount = $minimumPlay;
                }
                if ($gameId && isset($discountGame)) {
                    $discount = $amount * $discountGame->discount / 100;
                    $totalAmount = $amount - $discount;
                    $payAmount += $totalAmount;
                } else {
                    $payAmount += $amount;
                }
                $is_pass = true;
            }
            if ($gameId === Constants::LOTTERYLAODISCOUNTGAME || $gameId === Constants::LOTTERYLAOGAME) {
                $payAmount = $payAmount * $settingLotteryLaoSet->value;
            }
        }
        $creditBalance = CreditSearch::find()->select(['balance'])->where(['user_id'=>$user_id])->one();
        if(empty($creditBalance)){
            if($reason == 1){
                return ['result'=>false,'reason'=>'ไม่มีข้อมูลเครดิต '];
            }
            return false;
        }
        //หายอดที่จะขอถอนที่ยังไม่อนุมัติ
        $sumWaittingPostWithDraw = PostCreditTransectionSearch::find()
            ->where(['poster_id'=>$user_id,'action_id' => Constants::action_credit_withdraw,'status' => Constants::status_waitting])
            ->sum('amount');
        $realCredit = ($creditBalance->balance - $sumWaittingPostWithDraw);
        //จ่ายมากกว่ามี
        if(($payAmount>$realCredit) && $is_pass){
            if($reason == 1){
                return ['result'=>false,'reason'=>'จำนวนเครดิตของคุณไม่เพียงพอ หรืออาจมีการแจ้งถอนที่รออนุมัติอยู่'];
            }
            return false;
        }else {
            if($reason == 1){
                return ['result'=>true,'reason'=>'success '];
            }
            return true;
        }
    }

    public function getIsWin()
    {
        $models = $this->thaiSharedGameChitDetails;
        $result = 0;
        foreach($models as $model){
            if($model->flag_result == 1){
                $result = 1;
            }
        }
        return $result;
    }

    public function getTotalWinCredit()
    {
        $models = $this->thaiSharedGameChitDetails;
        $total_win_credit = 0;
        foreach($models as $model){
            $total_win_credit += $model->win_credit;
        }
        return $total_win_credit;
    }

    public function getCanReChit()
    {

        $now = date('Y-m-d H:i:s',time());
        $endTime = strtotime("-3 days", strtotime($now));
        //$endTime = strtotime($now);
        if(strtotime($this->thaiSharedGame->endDate) > $endTime && $this->status == Constants::status_playing || $this->status == Constants::status_finish_show_result){
            return true;
        }else{
            return false;
        }
    }

    public function getOrder()
    {
        return $this->thaiSharedGame->gameId.$this->id;
    }
}
