<?php

namespace frontend\controllers;

use common\models\User;
use common\models\UsersForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;


/**
 * Site controller
 */
class InfoController extends Controller
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

    public function actionUser()
    {
        $userId = \Yii::$app->user->identity->id;
        $user = User::find()->where(['id' => $userId])->one();
        if (!$user) {
            throw new NotFoundHttpException();
        }
        $userForm = new UsersForm();
        if ($userForm->load(Yii::$app->request->post()) && $userForm->validate()) {
            if ($userForm->change_password) {
                $user->setPassword($userForm->change_password);
            }
            $user->tel = $userForm->tel;
            if (!$user->save()) {
                throw new ServerErrorHttpException('Can not update profile');
            }
            return $this->redirect(['info/user']);
        }
        return $this->render('user-form', [
            'userForm' => $userForm,
            'user' => $user,
        ]);
    }
}
