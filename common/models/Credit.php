<?php

namespace common\models;

use Yii;
use common\libs\Constants;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "credit".
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
class Credit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit';
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
     * action_credit_top_up, //เพิ่ม หรือ ลด credit
     * $yeekeeChit->user_id, //เพิ่ม หรือ ลดให้ใคร
     * $user_id, //ใครเป็นคน เพิ่ม หรือ ลด
     * Constants::reason_credit_bet_win, //เหตุผลของการ เพิ่ม  หรือ ลด
     * $yeekeeChit->total_amount //จำนวนเครดิตที่ เพิ่ม  หรือ ลด
     * $remark
     */

    /*
     * ต้องตรวจสอบ credit master ก่อนว่าเกินยอดไหม ถ้าเกินไม่สามารถทำรายการได้
     * ยอดฝาก จะเอาไปลบกับ credit master
     * ยอดถอน จะเอาไปบวกกับ credit master
     */
    public static function creditWalk($action, $user_id, $user_action_id, $reason, $amount = 0, $remark = '')
    {
        //------------ตรวจสอบ credit master------------//
        $CreditMaster = 0;
        $CreditMasterBalance = CreditTransectionSearch::checkCreditMasterBalance($action, $reason, $amount);
        if (!empty($CreditMasterBalance) && isset($CreditMasterBalance['amount'])) {
            if ($CreditMasterBalance['amount'] < 0) {
                return false;
            } else {
                $CreditMaster = $CreditMasterBalance['amount'];
            }
        }
        //------------ตรวจสอบ credit master------------//

        $creditModel = Credit::findOne(['user_id' => $user_id]);

        if (empty($creditModel)) {
            $creditModel = new Credit();
            $creditModel->user_id = $user_id;
            $creditModel->balance = 0;
            $creditModel->create_at = date('Y-m-d H:i:s');
            $creditModel->create_by = Constants::user_system_id;
            $creditModel->update_at = date('Y-m-d H:i:s');
            $creditModel->update_by = Constants::user_system_id;
            if (!$creditModel->save()) {
                return false;
            }
        }
        $creditModel->update_at = date('Y-m-d H:i:s');
        $creditModel->update_by = $user_action_id;

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (empty($creditModel)) {
                throw new ServerErrorHttpException('Can not credit model not found');
            }
            $creditModel = Credit::findOne(['user_id' => $user_id]);
            $current_credit = $creditModel->balance;
            //เติม
            if (in_array($action, [Constants::action_credit_top_up, Constants::action_credit_top_up_admin, Constants::action_credit_top_up_admin_special])) {
                $creditModel->balance = ($current_credit + $amount);
                if (!$creditModel->save()) {
                    return false;
                }
                if ($reason == Constants::reason_credit_top_up) {
                    self::conditionWithdraw($user_id, $amount, Constants::reason_credit_top_up);
                } else if ($reason == Constants::reason_credit_commission_in) {
                    self::conditionWithdraw($user_id, $amount, Constants::reason_credit_commission_in);
                } else if ($reason == Constants::reason_credit_top_up_promotion) {
                    self::conditionWithdraw($user_id, $amount, Constants::reason_credit_top_up_promotion);
                }
                //ถอน
            } else if (in_array($action, [Constants::action_credit_withdraw, Constants::action_credit_withdraw_admin, Constants::action_credit_withdraw_admin_special])) {
                if ($current_credit >= $amount) {
                    $creditModel->balance = ($current_credit - $amount);
                    if (!$creditModel->save()) {
                        throw new ServerErrorHttpException('Can not save credit model');
                    }
                    if ($reason == Constants::reason_credit_withdraw_direct) {
                        self::conditionWithdraw($user_id, $amount, Constants::reason_credit_withdraw_direct);
                    }
                } else {
                    throw new ServerErrorHttpException('Can not condition balanced >= amount');
                }
            }
            //create credit transection
            $now = date('Y-m-d H:i:s');
            $creditTransectionModel = new CreditTransection();
            $creditTransectionModel->action_id = $action;
            $creditTransectionModel->reason_action_id = $reason;
            $creditTransectionModel->operator_id = $user_action_id;
            $creditTransectionModel->reciver_id = $user_id;
            $creditTransectionModel->amount = $amount;
            $creditTransectionModel->balance = $creditModel->balance;
            $creditTransectionModel->credit_master_balance = $CreditMaster;
            $creditTransectionModel->create_at = $now;
            $creditTransectionModel->create_by = $user_id;
            $creditTransectionModel->remark = $remark;
            if (!$creditTransectionModel->save()) {
                throw new ServerErrorHttpException('Can not save credit transection model');
            }
            \Yii::info('action Id: '.$action, 'credit');
            \Yii::info('user Id: '.$user_id, 'credit');
            \Yii::info('amount: '.$amount, 'credit');
            \Yii::info('current Credit before Update: '.$current_credit, 'credit');
            \Yii::info('credit transection Id: '.$creditTransectionModel->id, 'credit');
            \Yii::info('current Credit after Update: '.$creditModel->balance, 'credit');
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return true;
    }

    public static function conditionWithdraw($userId, $totalCredit, $reason)
    {
        $conditionWithdraw = ConditionWithdraw::find()->where(['status' => 1])->one();
        if (!$conditionWithdraw) {
            return true;
        }
        $totalConditionWithdraw = $totalCredit * $conditionWithdraw->percent / 100;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = ConditionWithdrawUser::find()->where(['userId' => $userId])->one();
            if ($reason == Constants::reason_credit_withdraw_direct) {
                if (!$model) {
                    $model = new ConditionWithdrawUser();
                    $model->userId = $userId;
                    $model->amount = $totalCredit;
                    $model->totalConditionWithDraw = $totalConditionWithdraw;
                }else {
                    $model->totalConditionWithDraw -= $totalConditionWithdraw;
                    $model->amount -= $totalCredit;
                }
            } else {
                if (!$model) {
                    $model = new ConditionWithdrawUser();
                    $model->userId = $userId;
                    $model->amount = $totalCredit;
                    $model->totalConditionWithDraw = $totalConditionWithdraw;
                } else {
                    $model->totalConditionWithDraw += $totalConditionWithdraw;
                    $model->amount += $totalCredit;
                }
            }
            if (!$model->save()) {
                throw new ServerErrorHttpException('Can not save ConditionWithdrawUser');
            }
            $conditionWithdrawUserTransaction = new ConditionWithdrawUserTransaction();
            $conditionWithdrawUserTransaction->userId = $model->userId;
            $conditionWithdrawUserTransaction->conditionWithdrawId = $conditionWithdraw->id;
            $conditionWithdrawUserTransaction->amount = $model->amount;
            $conditionWithdrawUserTransaction->totalConditionWithDraw = $model->totalConditionWithDraw;
            if (!$conditionWithdrawUserTransaction->save()) {
                throw new ServerErrorHttpException('Can not save ConditionWithdrawUserTransaction');
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
