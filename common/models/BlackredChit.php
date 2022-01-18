<?php

namespace common\models;

use common\libs\Constants;
use DateTime;

/**
 * This is the model class for table "blackred_chit".
 *
 * @property int $id
 * @property int $user_id
 * @property int $blackred_id
 * @property int $total_amount
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $status
 * @property int $play_type_code 1 = black, 2 = red
 * @property int $flag_result win, no_win
 * @property double $win_credit ถ้าชนะได้เงินเท่าไหร่
 *
 * @property Yeekee $blackred
 */
class BlackredChit extends \yii\db\ActiveRecord
{
    public $win;
    public $nowin;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uity_blackred_chit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'blackred_id', 'create_at', 'create_by', 'play_type_code'], 'required'],
            [['user_id', 'blackred_id', 'total_amount', 'create_by', 'update_by', 'status', 'play_type_code', 'flag_result'], 'integer'],
            [['total_amount'], 'number', 'min' => '0', 'max' => '2500'],
            [['create_at', 'update_at'], 'safe'],
            [['win_credit'], 'number'],
            [['blackred_id'], 'exist', 'skipOnError' => true, 'targetClass' => BlackRed::className(), 'targetAttribute' => ['blackred_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['blackred_id'], 'validateBuyBlackred', 'on' => 'create'],
        ];
    }

    public function scenarios()
    {
        $scenario = parent::scenarios();
        $scenario['create'] = array_merge($scenario['default'], ['blackred_id']);
        return $scenario;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'blackred_id' => 'Blackred ID',
            'total_amount' => 'Total Amount',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'status' => 'Status',
            'play_type_code' => 'Play Type Code',
            'flag_result' => 'Flag Result',
            'win_credit' => 'Win Credit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlackred()
    {
        return $this->hasOne(BlackRed::className(), ['id' => 'blackred_id']);
    }

    public function getCanReChit()
    {
        $time = new DateTime();
        $time->modify('+30 second');
        $endTime = $time->format('U');
        if(strtotime($this->blackred->finish_at) > $endTime && $this->status == Constants::status_playing){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getAgentBlackredChit($user_id)
    {
        $total = 0;
        $UserId = User::find()->select(['id'])->where(['agent'=>$user_id])->asArray()->all();
        if(!empty($UserId)){
            $arrId = array();
            foreach ($UserId as $id){
                $arrId[] = $id['id'];
            }
            $total = BlackredChit::find()->where(['user_id' => $arrId])->sum('total_amount');
        }
        return $total;
    }

    public function getAgentBlackredChitAll($user_id)
    {
        $amount = 0;
        $amountAll = self::getAgentBlackredChit($user_id);
        if(!empty($amountAll)){
            $ComInvite = SettingCommissionCredit::find()->where(['type'=>Constants::setting_commission_game_blackred_credit_invite,'is_active'=>Constants::status_active])->one();
            if(!empty($ComInvite)){
                $amount = ($amountAll * $ComInvite->value) / 100;
            }
        }
        return $amount;
    }

    public function validateBuyBlackred()
    {
        $BlackredChit = BlackredChit::find()->where([
            'blackred_id' => $this->blackred_id,
            'user_id' => $this->user_id,
            'status' => Constants::status_playing
        ])->count();
        if ($BlackredChit) {
            $this->addError('blackred_id', 'unique blackredId and userId');
        }
    }
}
