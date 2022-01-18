<?php
namespace backend\controllers;

use common\models\BlackredChit;
use common\models\ThaiSharedGameChit;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Credit;
use common\models\CreditTransection;
use common\libs\Constants;
use common\models\YeekeeChit;
use common\models\User;
use common\models\PostCreditTransection;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $amountUser = User::find()->where(['auth_roles_id'=>[Constants::auth_roles_member]])->count();
        $amountUserWithhold = User::find()->where(['auth_roles_id'=>[Constants::auth_roles_agent,Constants::auth_roles_member],'user_status'=>Constants::user_status_withhold])->count();
        $amountYeekeeChit = YeekeeChit::find()->where('DATE(`create_at`) = CURDATE()')->count();
        $amountBlackredChit = BlackredChit::find()->where('DATE(`create_at`) = CURDATE()')->count();
        $amountSharedChit = ThaiSharedGameChit::find()->where( 'DATE(createdAt) = CURDATE()')->count();
        $amountPostIn = PostCreditTransection::find()->select('sum(amount) as amount')
                ->where(['action_id'=>Constants::action_credit_top_up])
                ->andWhere(['like','create_at',date('Y-m-d')])
                ->andWhere(['status' => 7])
                ->one();
        $countAmountToday = PostCreditTransection::find()->where('DATE(create_at) = CURDATE()')->andWhere([
            'action_id'=>Constants::action_credit_top_up,
            'status' => 7,
        ])->count();
        $amountPostOut = PostCreditTransection::find()->select('sum(amount) as amount')
                ->where(['action_id'=>Constants::action_credit_withdraw])
                ->andWhere(['like','create_at',date('Y-m-d')])
                ->andWhere(['status' => 7])
                ->one();
        $amountCredit = Credit::find()->select('sum(balance) as balance')->one();
        $amountCreditTopUpPromotion = CreditTransection::find()->select('sum(amount) as amount')->where([
            'action_id' => Constants::action_credit_top_up_admin,
            'reason_action_id' => Constants::reason_credit_top_up_promotion
        ])->andWhere(['like','create_at',date('Y-m-d')])->one();
        $amountCreditWithDrawPromotion = CreditTransection::find()->select('sum(amount) as amount')->where([
            'action_id' => Constants::action_credit_withdraw_admin,
            'reason_action_id' => Constants::reason_credit_withdraw_direct
        ])->andWhere(['like','create_at',date('Y-m-d')])->one();
        
        return $this->render('index', [
            'amountUser'=>$amountUser,
            'amountUserWithhold'=>$amountUserWithhold,
            'amountYeekeeChit'=>$amountYeekeeChit,
            'amountPostIn'=>$amountPostIn,
            'amountPostOut'=>$amountPostOut,
            'amountCredit'=>$amountCredit,
            'amountBlackredChit' => $amountBlackredChit,
            'amountSharedChit' => $amountSharedChit,
            'amountCreditTopUpPromotion' => $amountCreditTopUpPromotion,
            'amountCreditWithDrawPromotion' => $amountCreditWithDrawPromotion,
            'countAmountToday' => $countAmountToday,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
