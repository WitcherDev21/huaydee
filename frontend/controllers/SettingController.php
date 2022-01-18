<?php

namespace frontend\controllers;

use common\libs\Constants;
use common\models\Bank;
use common\models\UserHasBank;
use frontend\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SettingController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionBank()
    {
        $userHasBanks = UserHasBank::find()->where(['status' => Constants::status_active, 'user_id' => \Yii::$app->user->id])->all();
        $userHasBankCount = UserHasBank::find()->where(['status' => Constants::status_active, 'user_id' => \Yii::$app->user->id])->count();
        if (!$userHasBanks) {
            $this->render('/site/error', [
                'exception' => 'ไม่มีบัญชีธนาคารกรุณาติดต่อ admin'
            ]);
        }
        return $this->render('bank', [
            'userHasBanks' => $userHasBanks,
            'userHasBankCount' => $userHasBankCount,
        ]);
    }

    public function actionBankStatus()
    {
        $userHasBanks = UserHasBank::find()->where(['user_id' => \Yii::$app->user->id])->all();
        if (!$userHasBanks) {
            $this->render('/site/error', [
                'exception' => 'ไม่มีบัญชีธนาคารกรุณาติดต่อ admin'
            ]);
        }
        return $this->render('status', [
           'userHasBanks' => $userHasBanks,
        ]);
    }

    public function actionBankAdd()
    {
        $model = new UserHasBank();
        $userHasBank = UserHasBank::find()->where(['user_id' => \Yii::$app->user->id])->one();
        $userHasBankCount = UserHasBank::find()->where(['status' => Constants::status_active, 'user_id' => \Yii::$app->user->id])->count();
        if (intval($userHasBankCount) >= 2) {
            throw new NotFoundHttpException();
        }
        $bank = Bank::find()->where(['status' => Constants::status_active])
            ->all();
        $arrBank = [];
        foreach ($bank as $b) {
            $arrBank[] = ['id' => $b->id, 'title' => $b->title, 'icon' => $b->icon, 'color' => $b->color];
        }
        $model->user_id = \Yii::$app->user->id;
        $model->create_at = date('Y-m-d H:i:s', time());
        $model->create_by = \Yii::$app->user->id;
        $model->status = Constants::status_waitting;
        $model->bank_account_name = $userHasBank->bank_account_name;
        $model->version = 1;
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['bank-status']);
        }
        return $this->render('_form', [
            'model' => $model,
            'arrBank' => $arrBank,
            'userHasBank' => $userHasBank,
        ]);
    }
}