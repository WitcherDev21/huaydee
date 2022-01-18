<?php
namespace backend\controllers;

use common\models\Credit;
use common\models\CreditTransection;
use common\models\ThaiSharedGameChitDetailSearch;
use XLSXWriter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\libs\Constants;
use common\models\CreditTransectionSearch;
use common\models\AuthRoles;
use common\models\UserSearch;

/**
 * Site controller
 */
class ReportController extends Controller
{
    public $defaultAction = 'credit';

    /**
     * {@inheritdoc}
     */
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
        $identity = \Yii::$app->user->getIdentity();
        if (empty($identity)) {
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        } else {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if (!in_array('report', $arrRoles)) {
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCredit()
    {

        $params = Yii::$app->request->get();

        $searchModel1 = new CreditTransectionSearch();
        $searchModel1->create_at = date('Y-m-d');
        if (!empty($params) && !empty($params['tab']) && $params['tab'] == '1' && !empty($params['CreditTransectionSearch'])) {
            if (!empty($params['CreditTransectionSearch']['filter_detail'])) {
                $searchModel1->action_id = $params['CreditTransectionSearch']['filter_detail'];
                $searchModel1->filter_detail = $params['CreditTransectionSearch']['filter_detail'];
            }
            if (!empty($params['CreditTransectionSearch']['filter_operator'])) {
                $operator = UserSearch::getUserProfile($params['CreditTransectionSearch']['filter_operator'], 'username');
                $searchModel1->operator_id = (!empty($operator) ? $operator->id : '');
                $searchModel1->filter_operator = $params['CreditTransectionSearch']['filter_operator'];
            }
        } else {
            $searchModel1->action_id = [Constants::action_credit_top_up, Constants::action_credit_withdraw, Constants::action_credit_top_up_admin, Constants::action_credit_withdraw_admin, Constants::action_credit_master_top_up, Constants::action_credit_master_withdraw];
        }
        $searchModel1->reason_action_id = [Constants::reason_credit_top_up, Constants::reason_credit_withdraw];
//        $searchModel->action_id = [Constants::action_credit_top_up,Constants::action_credit_withdraw,Constants::action_credit_top_up_admin,Constants::action_credit_withdraw_admin];
        $dataProvider1 = $searchModel1->search(Yii::$app->request->queryParams);

        $searchModel2 = new CreditTransectionSearch();
        if (!empty($params) && !empty($params['tab']) && $params['tab'] == '2' && !empty($params['CreditTransectionSearch'])) {
            if (!empty($params['CreditTransectionSearch']['filter_detail'])) {
                $searchModel2->action_id = $params['CreditTransectionSearch']['filter_detail'];
                $searchModel2->filter_detail = $params['CreditTransectionSearch']['filter_detail'];
            }
            if (!empty($params['CreditTransectionSearch']['filter_operator'])) {
                $operator = UserSearch::getUserProfile($params['CreditTransectionSearch']['filter_operator'], 'username');
                $searchModel2->operator_id = (!empty($operator) ? $operator->id : '');
                $searchModel2->filter_operator = $params['CreditTransectionSearch']['filter_operator'];
            }
        } else {
            $searchModel2->action_id = [Constants::action_credit_top_up, Constants::action_credit_withdraw, Constants::action_credit_top_up_admin, Constants::action_credit_withdraw_admin, Constants::action_credit_master_top_up, Constants::action_credit_master_withdraw];
        }
        $searchModel2->reason_action_id = [Constants::reason_credit_top_up, Constants::reason_credit_withdraw];
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);

        $searchModel3 = new CreditTransectionSearch();
        $searchModel3->create_at = date('Y-m-d');
        if (!empty($params) && !empty($params['tab']) && $params['tab'] == '3' && !empty($params['CreditTransectionSearch'])) {
            if (!empty($params['CreditTransectionSearch']['filter_detail'])) {
                $searchModel3->action_id = $params['CreditTransectionSearch']['filter_detail'];
                $searchModel3->filter_detail = $params['CreditTransectionSearch']['filter_detail'];
            }
            if (!empty($params['CreditTransectionSearch']['filter_operator'])) {
                $operator = UserSearch::getUserProfile($params['CreditTransectionSearch']['filter_operator'], 'username');
                $searchModel3->operator_id = (!empty($operator) ? $operator->id : '');
                $searchModel3->filter_operator = $params['CreditTransectionSearch']['filter_operator'];
            }
        } else {
            $searchModel3->action_id = [Constants::action_credit_top_up_admin, Constants::action_credit_withdraw_admin];
        }
        $searchModel3->reason_action_id = [Constants::reason_credit_top_up_promotion, Constants::reason_credit_withdraw_direct];
        $dataProvider3 = $searchModel3->search(Yii::$app->request->queryParams);

        $searchModel4 = new CreditTransectionSearch();
        if (!empty($params) && !empty($params['tab']) && $params['tab'] == '4' && !empty($params['CreditTransectionSearch'])) {
            if (!empty($params['CreditTransectionSearch']['filter_detail'])) {
                $searchModel4->action_id = $params['CreditTransectionSearch']['filter_detail'];
                $searchModel4->filter_detail = $params['CreditTransectionSearch']['filter_detail'];
            }
            if (!empty($params['CreditTransectionSearch']['filter_operator'])) {
                $operator = UserSearch::getUserProfile($params['CreditTransectionSearch']['filter_operator'], 'username');
                $searchModel4->operator_id = (!empty($operator) ? $operator->id : '');
                $searchModel4->filter_operator = $params['CreditTransectionSearch']['filter_operator'];
            }
        } else {
            $searchModel4->action_id = [Constants::action_credit_top_up_admin, Constants::action_credit_withdraw_admin];
        }
        $searchModel4->reason_action_id = [Constants::reason_credit_top_up_promotion, Constants::reason_credit_withdraw_direct];
        $dataProvider4 = $searchModel4->search(Yii::$app->request->queryParams);

        return $this->render('credit', [
            'searchModel1' => $searchModel1,
            'dataProvider1' => $dataProvider1,
            'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,
            'searchModel3' => $searchModel3,
            'dataProvider3' => $dataProvider3,
            'searchModel4' => $searchModel4,
            'dataProvider4' => $dataProvider4,
            'tab' => (!empty($params) && !empty($params['tab']) ? $params['tab'] : '1')
        ]);
    }

    public function actionListCurrent()
    {
        $active_tab = 'list-current';

        $searchModel = new CreditTransectionSearch();
        $reasonActionId = isset(Yii::$app->request->queryParams['CreditTransectionSearch']['reason_action_id']) ?
            Yii::$app->request->queryParams['CreditTransectionSearch']['reason_action_id'] : '';
        $searchModel->create_at = date('Y-m-d');
        if ($reasonActionId) {
            if ($reasonActionId == 3) {
                $searchModel->action_id = Constants::action_commission_withdraw_direct;
            }
            $searchModel->reason_action_id = $reasonActionId;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'active_tab' => $active_tab
        ]);
    }

    public function actionListHistory()
    {
        $active_tab = 'list-history';

        $searchModel = new CreditTransectionSearch();
        $reasonActionId = isset(Yii::$app->request->queryParams['CreditTransectionSearch']['reason_action_id']) ?
            Yii::$app->request->queryParams['CreditTransectionSearch']['reason_action_id'] : '';
        if ($reasonActionId) {
            if ($reasonActionId == 3) {
                $searchModel->action_id = Constants::action_commission_withdraw_direct;
            }
            $searchModel->reason_action_id = $reasonActionId;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'active_tab' => $active_tab
        ]);
    }

    public function actionListPerson()
    {
        $active_tab = 'list-person';
        $query = CreditTransection::find()->select([
            'reciver_id',
            ''.Credit::tableName().'.balance',
            'coalesce(sum(case when action_id IN (1,5) then amount end), 0) as deposit, coalesce(sum(case when action_id IN (2,3,6) then amount end), 0) as withdraw',
            ' coalesce(sum(case when action_id IN (1,5) then amount end) - sum(case when action_id IN (2,3,6) then amount end), 0) - credit.balance as total',
        ])->
        innerJoin(Credit::tableName(), ''.Credit::tableName().'.user_id = '.CreditTransection::tableName().'.reciver_id')->
        groupBy('reciver_id')->having('total > 0')->orderBy('total DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('_list_person', [
            'dataProvider' => $dataProvider,
            'active_tab' => $active_tab
        ]);
    }

    public function actionReportGame()
    {
        $searchModel = new ThaiSharedGameChitDetailSearch();
        $dataProvider = $searchModel->searchReportGame(Yii::$app->request->queryParams);

        return $this->render('report-game', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
}
