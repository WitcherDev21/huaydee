<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\BlackredChit;
use common\models\YeekeeChitDetail;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/22/2018
 * Time: 9:37 PM
 */

class ReportGameController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        $identity = Yii::$app->user->getIdentity();
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }else{
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if(!in_array('members', $arrRoles)){
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
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

    public function actionBlackred()
    {
        $query = BlackredChit::find()->select([
            'user_id, flag_result, SUM(win_credit) as win_credit, SUM(total_amount) as total_amount, 
            SUM(flag_result = 1) AS `win`, SUM(flag_result = 0) AS `nowin`'
        ])->joinWith('user')->where(['status' => Constants::status_finish_show_result])->groupBy('user_id')->orderBy('win_credit DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);
        if (Yii::$app->request->queryParams) {
            $dataProvider->query->andFilterWhere(['like', 'username', Yii::$app->request->queryParams['username']]);
        }
        return $this->render('blackred', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionYeekee()
    {
        $query = YeekeeChitDetail::find()->select([
            'create_by, flag_result, SUM(win_credit) as win_credit, SUM(amount) as amount, 
            SUM(flag_result = 1) AS `win`, SUM(flag_result = 0) AS `nowin`'
        ])->joinWith('user')->groupBy('create_by')->orderBy('win_credit DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);
        if (Yii::$app->request->queryParams) {
            $dataProvider->query->andFilterWhere(['like', 'username', Yii::$app->request->queryParams['username']]);
        }
        return $this->render('yeekee', [
            'dataProvider' => $dataProvider
        ]);
    }
}