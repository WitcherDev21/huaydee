<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\BlackRed;
use common\models\BlackredChit;
use common\models\BlackredChitSearch;
use common\models\Running;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class BlackredController extends Controller
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if (empty($identity)) {
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        } else {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if (!in_array('blackred', $arrRoles)) {
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
            'access'=> [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
    }

    public function actionListCurrent()
    {
        $active_tab = 'list-current';
        $running = Running::find()->where(['game_id' => Constants::BLACKRED])->one();
        $blackRed = BlackRed::find()->select(['date_at'])->where(['group' => $running->running])->one();
        $searchModel = new BlackredChitSearch();
        $searchModel->date_at = $blackRed->date_at;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'active_tab' => $active_tab
        ]);
    }

    public function actionListHistory()
    {
        $userId = \Yii::$app->user->id;
        $active_tab = 'list-history';

        $searchModel = new BlackredChitSearch();

        $searchModel->date_at = isset(Yii::$app->request->queryParams['BlackredChitSearch']['date_at']) ? Yii::$app->request->queryParams['BlackredChitSearch']['date_at'] : '';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'active_tab' => $active_tab
        ]);
    }

    public function actionDetail($id, $blackRedId)
    {
        $query = BlackredChit::find()->where(['id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);
        $blackRed = BlackRed::findOne($blackRedId);
        $orderId = $blackRed->getOrderId();

        return $this->render('detail', [
            'dataProvider' => $dataProvider,
            'active_tab' => 'list-current',
            'orderId' => $orderId
        ]);
    }
}
