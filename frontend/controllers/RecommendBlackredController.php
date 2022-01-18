<?php

namespace frontend\controllers;

use common\models\BlackredChit;
use common\models\CommissionBlackred;
use common\models\CommissionTransectionBlackred;
use common\models\UserSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\libs\Constants;
use common\models\YeekeeChit;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use common\models\User;
use common\models\SettingCommissionCredit;
use common\models\LogClick;
use common\models\News;


/**
 * Site controller
 */
class RecommendBlackredController extends Controller
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


    public function actionIndex()
    {

        $activeTab = 'index';
        $user_id = \Yii::$app->user->id;

        //commission percent
        $model = SettingCommissionCredit::find()->where(['type' => Constants::setting_commission_game_blackred_credit_invite])->one();
        $percent = 0;
        if (!empty($model)) {
            $percent = empty($model->value) ? 0 : $model->value;
        }

        //count click
        $countClick = LogClick::find()->where(['from_user_id' => $user_id])->count('*');

        //link
        $encrypt = Constants::Encrypt(Constants::private_key, $user_id);
        //$decrypt = Constants::Decrypt(Constants::private_key, $encrypt);
        $recommendUrl = Url::toRoute(['user/registration/register', 'from' => $encrypt], true);

        //member
        $query = User::find()->select(['id', 'username'])->where(['agent' => $user_id]);
        $memberCount = $query->count('*');

        //tatal play
        $query = User::find()->select(['id', 'username'])->where(['agent' => $user_id]);
        $sumPlayAmount = 0;
        foreach ($query->each() as $member) {
            $sumPlayAmount += BlackredChit::find()->where(['user_id' => $member->id])->sum('total_amount');
        }


        //totle income
        $totleIncome = $sumPlayAmount * $percent / 100;

        //current income
        $currentIncome = 0;
        $model = CommissionBlackred::find()->select(['balance'])->where(['user_id' => $user_id])->one();
        if (!empty($model)) {
            $currentIncome = $model->balance;
        }

        //ประกาศ
        $news = News::find()->where(['zone' => 'blackred'])->one();
        if (empty($news))
            $news = new News();

        $commissionTransactionWithDraw = CommissionTransectionBlackred::find()->where(['reciver_id' => $user_id, 'reason_action_id' => 2])->sum('amount');


        return $this->render('index', [
            'activeTab' => $activeTab,
            'recommendUrl' => $recommendUrl,
            'memberCount' => $memberCount,
            'countClick' => $countClick,
            'percent' => $percent,
            'totleIncome' => $totleIncome,
            'currentIncome' => $currentIncome,
            'sumPlayAmount' => $sumPlayAmount,
            'news' => $news,
            'commissionTransactionWithDraw' => $commissionTransactionWithDraw
        ]);
    }

    public function actionMember()
    {
        $searchModel = new UserSearch();
        $active_tab = 'member';
        $user_id = \Yii::$app->user->id;
        $request = Yii::$app->request->queryParams;
        $requestSearchUserName = isset($request['UserSearch']['username']) ? $request['UserSearch']['username'] : null;

        if ($requestSearchUserName) {
            $models = User::find()->select(['id', 'username', 'created_at'])->where(['agent' => $user_id])->andWhere(['like', 'username', $requestSearchUserName])->all();
            $searchModel->username = $requestSearchUserName;
        } else {
            $models = User::find()->select(['id', 'username', 'created_at'])->where(['agent' => $user_id])->all();
        }
        $results = [];
        foreach ($models as $model) {
            $playAmount = BlackredChit::find()->where([
                'user_id' => $model->id
            ])->sum('total_amount');
            if (!$playAmount) {
                $playAmount = 0;
            }
            $results[] = ['user_id' => $model->id, 'username' => $model->username, 'playAmount' => $playAmount, 'created_at' => date('d/m/Y H:i:s', $model->created_at)];
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results,
            'sort' => false,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        return $this->render('member', [
            'active_tab' => $active_tab,
            'results' => $results,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionIncome()
    {

        $user_id = \Yii::$app->user->id;
        $active_tab = 'income';
        $active_month = \Yii::$app->request->get('month');
        if (empty($active_month)) {
            $active_month = date('Y-m');
        }

        $months = [];
        for ($i = 2; $i >= 0; $i--) {
            $months[] = date('Y-m', strtotime('-' . $i . ' month', strtotime(date('Y-m-01 00:00:00'))));
        }

        $last_day = date("t", strtotime($active_month));
        $incomes = [];
        for ($i = 1; $i <= $last_day; $i++) {
            $activeDate = $active_month . '-' . $i;
            $amount = CommissionTransectionBlackred::getIncomeInDay($activeDate, $user_id);
            $incomes[] = ['day' => date("d/m/Y", strtotime($activeDate)), 'income' => $amount];
        }

        return $this->render('income', [
            'active_tab' => $active_tab,
            'months' => $months,
            'active_month' => $active_month,
            'incomes' => $incomes
        ]);
    }

    public function actionWithdraw()
    {

        $active_tab = 'withdraw';
        $user_id = \Yii::$app->user->identity->id;

        $params = \Yii::$app->request->post();
        if (!empty($params)) {
            $amount = $params['amount'];
            $remark = 'ถอนค่าคอมมิชชั่นดำแดง';
            $result = CommissionBlackred::commissionWalk(Constants::action_commission_withdraw, $user_id, $user_id, Constants::reason_commission_withdraw, $amount, $remark, 2);

        }

        $query = CommissionTransectionBlackred::find()->where(['reciver_id' => $user_id])->orderBy(['create_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        $currentIncome = 0;
        $model = CommissionBlackred::find()->select(['balance'])->where(['user_id' => $user_id])->one();
        if (!empty($model)) {
            $currentIncome = $model->balance;
        }
        return $this->render('withdraw', [
            'active_tab' => $active_tab,
            'currentIncome' => $currentIncome,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRelate()
    {
        $params = \Yii::$app->request->get();
        $encrypt = $params['from'];
        $user_id = $decrypt = Constants::Decrypt(Constants::private_key, $encrypt);
        var_dump($user_id);
        exit;
    }


}
