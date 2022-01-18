<?php
namespace backend\controllers;

use yii\helpers\ArrayHelper;
use yii\rest\Controller;

use yii;
use common\libs\Constants;
use common\models\UserSearch;
use common\models\UserHasBank;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class ApproveUserController extends Controller
{
    public function actionApprove($bankaccount)
    {
        $userHasBank = UserHasBank::find()->where(['bank_account_no'=>$bankaccount])->one();
        if ($userHasBank->status == Constants::user_status_active) {
            return ['user นี้ได้กดอนุมัติไปแล้ว'];
        }
        if (!$userHasBank) {
            throw new NotFoundHttpException('Not Found');
        }
        $userHasBank->status = Constants::user_status_active;
        if (!$userHasBank->save()) {
            throw new ServerErrorHttpException('Can not update user has bank status');
        }
        return ['message' => 'อนุมัติ user เรียบร้อย'];
        
    }
}