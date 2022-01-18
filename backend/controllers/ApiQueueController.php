<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\Credit;
use common\models\CreditTransection;
use common\models\CreditTransectionSearch;
use common\models\QueueProcessing;
use yii\rest\Controller;
use common\models\FilteringCredit;
use yii\web\ServerErrorHttpException;

class ApiQueueController extends Controller
{
    public function actionCredit($authKey, $limit = null)
    {
        if (\Yii::$app->params['creditAuthKey'] !== $authKey) {
            return ['message' => 'Auth key Not found'];
        }
        $filteringCredit = FilteringCredit::find();
        if ($limit) {
            $filteringCredit->limit($limit);
        }
        $filteringCreditObjs = $filteringCredit->all();
        if ($filteringCreditObjs) {
            $queueCredit = QueueProcessing::find()->where(['module' => 'credit'])->count();
            if ($queueCredit) {
                return ['message' => 'queue credit processing'];
            }
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $queueCredit = new QueueProcessing();
                $queueCredit->module = 'credit';
                $queueCredit->createdBy = Constants::user_system_id;
                if (!$queueCredit->save()) {
                    throw new ServerErrorHttpException('Can not save QueueProcessing');
                }
                foreach ($filteringCreditObjs as $filteringCreditObj) {
                    /* @var $filteringCreditObj FilteringCredit */

                    $actionId = $filteringCreditObj->actionId;
                    $reasonId = $filteringCreditObj->reasonActionId;
                    $amount = $filteringCreditObj->amount;
                    $userId = $filteringCreditObj->reciverId;
                    $remark = $filteringCreditObj->remark;
                    $userActionId = Constants::user_system_id;
                    //------------ตรวจสอบ credit master------------//
                    $CreditMaster = 0;
                    $CreditMasterBalance = CreditTransectionSearch::checkCreditMasterBalance($actionId, $reasonId, $amount);
                    if (!empty($CreditMasterBalance) && isset($CreditMasterBalance['amount'])) {
                        if ($CreditMasterBalance['amount'] < 0) {
                            throw new ServerErrorHttpException('CreditMasterBalance Inadequacy');
                        } else {
                            $CreditMaster = $CreditMasterBalance['amount'];
                        }
                    }
                    //------------ตรวจสอบ credit master------------//

                    $creditModel = Credit::findOne(['user_id' => $userId]);
                    if (!$creditModel) {
                        throw new ServerErrorHttpException('Can not found Credit User Id: ' . $userId);
                    }
                    $creditModel->update_at = date('Y-m-d H:i:s');
                    $creditModel->update_by = $userActionId;
                    $current_credit = $creditModel->balance;
                    //เติม
                    if (in_array($actionId, [Constants::action_credit_top_up, Constants::action_credit_top_up_admin, Constants::action_credit_top_up_admin_special])) {
                        $creditModel->balance = ($current_credit + $amount);
                        if (!$creditModel->save()) {
                            return false;
                        }
                        if ($reasonId == Constants::reason_credit_top_up) {
                            Credit::conditionWithdraw($userId, $amount, Constants::reason_credit_top_up);
                        } else if ($reasonId == Constants::reason_credit_commission_in) {
                            Credit::conditionWithdraw($userId, $amount, Constants::reason_credit_commission_in);
                        } else if ($reasonId == Constants::reason_credit_top_up_promotion) {
                            Credit::conditionWithdraw($userId, $amount, Constants::reason_credit_top_up_promotion);
                        }
                        //ถอน
                    } else if (in_array($actionId, [Constants::action_credit_withdraw, Constants::action_credit_withdraw_admin, Constants::action_credit_withdraw_admin_special])) {
                        if ($current_credit >= $amount) {
                            $creditModel->balance = ($current_credit - $amount);
                            if (!$creditModel->save()) {
                                throw new ServerErrorHttpException('Can not save credit balance in process withdraw');
                            }
                            if ($reasonId == Constants::reason_credit_withdraw_direct) {
                                Credit::conditionWithdraw($userId, $amount, Constants::reason_credit_withdraw_direct);
                            }
                        } else {
                            throw new ServerErrorHttpException('current credit less then amount');
                        }
                    }

                    //create credit transection
                    $now = date('Y-m-d H:i:s');
                    $creditTransectionModel = new CreditTransection();
                    $creditTransectionModel->action_id = $actionId;
                    $creditTransectionModel->reason_action_id = $reasonId;
                    $creditTransectionModel->operator_id = $userActionId;
                    $creditTransectionModel->reciver_id = $userId;
                    $creditTransectionModel->amount = $amount;
                    $creditTransectionModel->balance = $creditModel->balance;
                    $creditTransectionModel->credit_master_balance = $CreditMaster;
                    $creditTransectionModel->create_at = $now;
                    $creditTransectionModel->create_by = $userId;
                    $creditTransectionModel->remark = $remark;
                    $creditTransectionModel->save();
                }
                $transaction->commit();
                FilteringCredit::deleteAll();
                QueueProcessing::deleteAll(['module' => 'credit']);
                return ['message' => 'SUCCESS'];
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return ['message' => 'not found filtering credit'];
    }
}
