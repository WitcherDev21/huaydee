<?php

namespace frontend\controllers;

use common\libs\Constants;
use common\models\Commission;
use common\models\CommissionSearch;
use common\models\CommissionTransection;
use common\models\CommissionTransectionSearch;
use common\models\LogClick;
use common\models\News;
use common\models\PostCreditTransection;
use common\models\Queue;
use common\models\SettingCommissionCredit;
use common\models\ThaiSharedGameChit;
use common\models\User;
use common\models\UserHasBank;
use common\models\UserSearch;
use common\models\YeekeeChit;
use common\models\YeekeeChitSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\Pagination;


/**
 * Site controller
 */
class RecommendController extends Controller
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
        $model = SettingCommissionCredit::find()->where(['type' => Constants::setting_commission_credit_invite])->one();
        $percent = 0;
        if (!empty($model)) {
            $percent = empty($model->value) ? 0 : $model->value;
        }

        //count click
        $countClick = LogClick::find()->where(['from_user_id' => $user_id])->count('*');

        //link
        $encrypt = Constants::Encrypt(Constants::private_key, $user_id);
        //$decrypt = Constants::Decrypt(Constants::private_key, $encrypt);
        $recommendUrl = Url::to(['user/registration/register', 'from' => $encrypt], true);

        //member
        $query = User::find()->select(['id', 'username'])->where(['agent' => $user_id]);
        $memberCount = $query->count('*');

        //tatal play
        $query = User::find()->select(['id', 'username'])->where(['agent' => $user_id]);
        $sumPlayAmountYeekee = 0;
        $sumPlayAmount = 0;
        foreach ($query->each() as $member) {
            $sumPlayAmountYeekee += YeekeeChitSearch::find()->where(['user_id' => $member->id])->andWhere(['<>', 'status', Constants::status_cancel])->sum('total_amount');
            $thaiSharedGameChit = ThaiSharedGameChit::find()->select('SUM(CASE WHEN totalDiscount > 0 THEN totalDiscount ELSE totalAmount END) AS totalAmount')->where(['userId' => $member->id])->one();
            $sumPlayAmount = $sumPlayAmountYeekee  +  $thaiSharedGameChit->totalAmount;
        }

        $totalCommissionTopup = CommissionTransection::find()->where([
            'action_id' => Constants::action_commission_top_up,
            'reason_action_id' => Constants::reason_credit_top_up,
            'reciver_id' => $user_id
        ])->sum('amount');
        $totalCommissionCancel = CommissionTransection::find()->where([
            'action_id' => Constants::action_commission_top_up,
            'reason_action_id' => Constants::reason_credit_cancel,
            'reciver_id' => $user_id
        ])->sum('amount');


        //totle income
        $totalIncome = $totalCommissionTopup - $totalCommissionCancel;

        //current income
        $currentIncome = 0;
        $model = CommissionSearch::find()->select(['balance'])->where(['user_id' => $user_id])->one();
        if (!empty($model)) {
            $currentIncome = $model->balance;
        }

        //ประกาศ
        $news = News::find()->where(['zone' => 'recommend'])->one();
        if (empty($news))
            $news = new News();

        $commissionTransactionWithDraw = CommissionTransection::find()->where(['reciver_id' => $user_id, 'reason_action_id' => 2])->sum('amount');


        return $this->render('index', [
            'activeTab' => $activeTab,
            'recommendUrl' => $recommendUrl,
            'memberCount' => $memberCount,
            'countClick' => $countClick,
            'percent' => $percent,
            'totalIncome' => $totalIncome,
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
        $user_id = \Yii::$app->user->identity->id;
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
            $playAmount = YeekeeChit::find()->where([
                'user_id' => $model->id
            ])->sum('total_amount');
            $thaiSharedGameChit = ThaiSharedGameChit::find()->select('SUM(CASE WHEN totalDiscount > 0 THEN totalDiscount ELSE totalAmount END) AS totalAmount')->where(['userId' => $model->id])->one();
            $playAmount += $thaiSharedGameChit->totalAmount;
            if (!$playAmount) {
                $playAmount = '0';
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
        $pages = new Pagination(['totalCount' => count($results)]);

        return $this->render('member', [
            'active_tab' => $active_tab,
            'results' => $results,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'pages' => $pages,
        ]);
    }

    public function actionIncome()
    {

        $user_id = \Yii::$app->user->identity->id;
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
            $amount = CommissionTransectionSearch::getIncomeInDay($activeDate, $user_id);
            $incomes[] = ['day' => date("d M Y", strtotime($activeDate)), 'income' => $amount];
        }
        $currentDate = Yii::$app->request->get('month') ? date('m/Y', strtotime(Yii::$app->request->get('month'))) : date('m/Y');

        return $this->render('income', [
            'active_tab' => $active_tab,
            'months' => $months,
            'active_month' => $active_month,
            'incomes' => $incomes,
            'currentDate' => $currentDate
        ]);
    }

    public function actionWithdraw()
    {
        $active_tab = 'withdraw';
        $user_id = \Yii::$app->user->identity->id;
        $currentIncome = 0;
        $model = CommissionSearch::find()->select(['balance'])->where(['user_id' => $user_id])->one();
        $countWatting = PostCreditTransection::find()->where(['status' => Constants::status_waitting, 'create_by' => $user_id])->count();
        if (!empty($model)) {
            $currentIncome = $model->balance;
        }
        $params = \Yii::$app->request->post();
        if (!empty($params)) {
            $models = User::find()->select(['id'])->where(['agent' => $user_id])->all();
            $checkQueueProcess = Queue::find()->where([
                'userId' => ArrayHelper::getColumn($models, 'id'),
                'status' => Constants::status_active
            ])->count();
            if ($checkQueueProcess) {
                return $this->render('/site/error', [
                    'exception' => 'กรุณารอสักครู่ แล้วทำรายการใหม่อีกครั้ง'
                ]);
            }
            $bankId = $params['bank'];
            $amount = $params['amount'];
            $blance = isset($model->balance) ? $model->balance : 0;
            if ($amount > $blance || $amount < Constants::minimum_commission_withdraw || $amount > Constants::maximum_commission_withdraw) {
                return $this->render('/site/error', [
                    'exception' => 'ยอดเงินเครดิตระบบแนะนำไม่ผ่านเงื่อนไขที่ตั้งไว้'
                ]);
            }
            if ($params['checkWallet'] === 'wallet') {
                $remark = 'ถอนเงินเข้ากระเป๋าเครดิตค่าคอมมิชชั่นหวย';
                Commission::commissionWalk(Constants::action_commission_withdraw, $user_id, $user_id, Constants::reason_commission_withdraw, $amount, $remark);
            } else {
                $remark = 'ถอนตรงค่าคอมมิชชั่นหวย';
                Commission::commissionWalk(Constants::action_commission_withdraw_direct, $user_id, $user_id, Constants::reason_commission_withdraw_direct, $amount, $remark, $bankId);
            }

        }
        $query = CommissionTransection::find()->where(['reciver_id' => $user_id])->orderBy(['create_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        $userHasBanks= UserHasBank::find()->joinWith(['bank'])->where(['user_has_bank.status' => Constants::status_active, 'user_id' => $user_id])->all();

        return $this->render('withdraw', [
            'active_tab' => $active_tab,
            'currentIncome' => $currentIncome,
            'dataProvider' => $dataProvider,
            'countWatting' => $countWatting,
            'userHasBanks' => $userHasBanks,
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
