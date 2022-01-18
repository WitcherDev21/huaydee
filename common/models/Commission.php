<?php

namespace common\models;

use Yii;
use common\libs\Constants;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "commission".
 *
 * @property int $id
 * @property string $no
 * @property int $user_id
 * @property string $balance
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 *
 * @property User $user
 */
class Commission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%commission}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'create_by', 'update_by'], 'integer'],
            [['balance'], 'number'],
            [['create_at', 'create_by'], 'required'],
            [['create_at', 'update_at'], 'safe'],
            [['no'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no' => 'No',
            'user_id' => 'User ID',
            'balance' => 'Balance',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /** ใช้ทำ เพิ่ม เครดิต ลดเครดิต แล้วทำ transection auto
     * @return boolean
     * action_commission_top_up, //เพิ่ม หรือ ลด commission
     * $yeekeeChit->user_id, //เพิ่ม หรือ ลดให้ใคร
     * $user_id, //ใครเป็นคน เพิ่ม หรือ ลด
     * Constants::reason_commission_bet_win, //เหตุผลของการ เพิ่ม  หรือ ลด
     * $yeekeeChit->total_amount //จำนวนเครดิตที่ เพิ่ม  หรือ ลด
     * $remark
     */
    public static function commissionWalk($action, $user_id, $user_action_id, $reason, $amount = 0, $remark = '', $bankId = null)
    {
            
        $creditModel = Commission::findOne(['user_id'=>$user_id]);
       
        if(empty($creditModel)){
            $creditModel = new Commission();
            $creditModel->user_id = $user_id;
            $creditModel->balance = 0;
            $creditModel->create_at = date('Y-m-d H:i:s');
            $creditModel->create_by = Constants::user_system_id;
            $creditModel->update_at = date('Y-m-d H:i:s');
            $creditModel->update_by = Constants::user_system_id;
            if(!$creditModel->save()){
               return false;
            }
        }
        $creditModel->update_at = date('Y-m-d H:i:s');
        $creditModel->update_by = $user_action_id;
        
        $transaction = \Yii::$app->db->beginTransaction();
        
        if(empty($creditModel)){
            $transaction->rollBack();
            return false;
        }
        $current_credit = $creditModel->balance;
        //เติม
        if(in_array($action,[Constants::action_commission_top_up])){
            $creditModel->balance = ($current_credit + $amount);
            $creditModel->save();
        //ถอน
        }else if(in_array($action,[Constants::action_commission_withdraw])){
              if($current_credit >= $amount){
                $creditModel->balance = ($current_credit - $amount);
                $result = $creditModel->save();
                if($result){    
                    $result = Credit::creditWalk(Constants::action_credit_top_up, $user_id, $user_action_id, Constants::reason_credit_commission_in, $amount,$remark);
                    if(!$result){
                        $transaction->rollBack();
                        return false;
                    }
                }
            }else{
                $transaction->rollBack();
                return false;
            }
        }else if(in_array($action,[Constants::action_commission_withdraw_direct])){
            if($current_credit >= $amount){
                $creditModel->balance = ($current_credit - $amount);
                $result = $creditModel->save();
                if($result){
                    $result = self::commissionWithdraw($amount, $bankId);
                    if(!$result){
                        $transaction->rollBack();
                        return false;
                    }
                }
            }else{
                $transaction->rollBack();
                return false;
            }
        }
        
        //create credit transection
        $creditTransectionModel = new CommissionTransection();
        $creditTransectionModel->action_id = $action;
        $creditTransectionModel->reason_action_id = $reason;
        $creditTransectionModel->operator_id = $user_action_id;
        $creditTransectionModel->reciver_id = $user_id;
        $creditTransectionModel->amount = $amount;
        $creditTransectionModel->balance = $creditModel->balance;
        $creditTransectionModel->create_at = date('Y-m-d H:i:s',time());
        $creditTransectionModel->create_by = $user_id;
        $creditTransectionModel->remark = $remark;
        $result = $creditTransectionModel->save();
        
        if($result){
            $transaction->commit();
            return true;
        }else{
            $transaction->rollBack();
            return false;
        }
    }

    public static function commissionWithdraw($amount, $bankId)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userId = Yii::$app->user->id;
            $userHasBank = UserHasBank::find()->where(['user_id' => $userId, 'id' => $bankId, 'status' => Constants::status_active])->orderBy('update_at DESC')->limit(1)->one();
            if (!$userHasBank) {
                throw new ServerErrorHttpException('Not Found UserHasBank');
            }
            $model = new PostCreditTransection();
            $model->poster_id = $userId;
            $model->action_id = Constants::action_commission_withdraw_direct;
            $model->user_has_bank_id = $userHasBank->id;
            $model->user_has_bank_version = UserHasBankSearch::getCurrentVersion($userHasBank->id);
            $model->status = Constants::status_waitting;
            $model->amount = $amount;
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->create_by = $userId;
            $model->post_requir_time = date('Y-m-d H:i:s');
            $model->user_has_bank_id_user = $userHasBank->id;
            if (!$model->save()) {
                throw new ServerErrorHttpException('Can not save post credit transection');
            }
            $transaction->commit();
            return true;
        }catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
