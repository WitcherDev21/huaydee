<?php

namespace backend\controllers\user;

use common\models\Session;
use dektrium\user\controllers\SecurityController as BaseSecurityController;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
use dektrium\user\models\User;
use dektrium\user\models\LoginForm;
use common\models\UserAccessSearch;
use common\libs\Constants;
use common\models\AuthPermissionParent;
use common\models\AuthRoles;

class SecurityController extends BaseSecurityController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?', '@', 'admin'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@', 'admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionLogin()
    {

        if (!\Yii::$app->user->isGuest) {
//			$this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::createObject(LoginForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            Session::deleteAll('user_id = :user_id', [':user_id' => \Yii::$app->user->id]);

            $checkRole = AuthPermissionParent::find()
                ->where(['auth_rule_id' => \Yii::$app->user->identity->auth_roles_id, 'is_active' => Constants::status_active])->count();

            if (\Yii::$app->user->identity->user_status == Constants::user_status_active && $checkRole > 0) {
//                    if(\Yii::$app->user->identity->user_status == Constants::user_status_active && in_array(\Yii::$app->user->identity->auth_roles_id, [Constants::auth_roles_super_admin,Constants::auth_roles_admin])){
                $modelUserAccess = new UserAccessSearch();
                $modelUserAccess->save_access();

                $this->trigger(self::EVENT_AFTER_LOGIN, $event);


                $modelAuthRoles = new AuthRoles();
                $arrRoles = $modelAuthRoles->_getRoles(\Yii::$app->user->identity->auth_roles_id);

                if (in_array('site', $arrRoles)) {
                    return $this->redirect(['/site/index']);
                } else {
                    return $this->redirect(['/' . $arrRoles[0]]);
                }
            } else {
                return $this->redirect(['/user/logout']);
            }
        }

        return $this->render('login2', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }

}