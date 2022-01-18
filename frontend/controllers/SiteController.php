<?php
namespace frontend\controllers;

use common\models\Credit;
use common\models\CreditTransection;
use common\models\Pusher;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\News;
use common\libs\Constants;

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
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true
                    ],
                    [
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['site/home']);
        $this->layout = '@frontend/themes/guest/views/layouts/main.php';
        return $this->render('index');
    }

    public function actionHome()
    {
        if (\Yii::$app->user->identity->auth_roles_id == \common\libs\Constants::auth_roles_admin) {
            Yii::$app->user->logout();
            return $this->goHome();
        }        
        $credit = 0;
        $userId = Yii::$app->user->id;
        $model = Credit::find()->select(['balance'])->where(['user_id' => $userId])->one();
        if (!empty($model)) {
            $credit = $model->balance;
        }
        $creditTransection = CreditTransection::find()->where(['reciver_id' => $userId])->andWhere('remark <> ""')->orderBy('create_at DESC')->one();
    	$news = News::find()->where(['status'=>Constants::status_active,'zone' => ['res', 'rule']])->all();
    	return $this->render('home',[
    	    'news' => $news,
            'credit' => $credit,
            'creditTransection' => $creditTransection,
    	]);
    }

    public function actionDownload()
    {
        return $this->render('download');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionError($exception = null)
    {
        if (!$exception) {
            $exception = 'เนื่องจากมีการใช้งานจำนวนมากในขณะนี้กรุณากดปุ่มเข้าเล่นใหม่อีกครั้ง '.'<a href="'.Url::to(['thai-shared-chit/index']).'" class="btn btn-primary"><span style="color: white">เข้าเล่นใหม่อีกครั้ง</span></a>';
        }
        return $this->render('error', ['exception' => $exception]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    public function actionGetBreakingNews(){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $news = News::find()->where(['status'=>Constants::status_active,'zone'=>'breaking'])->all();
        $result = [];
        
        foreach($news as $new){
            $result[] = ['id'=>$new->id,'content'=>$new->message,'class'=>'msg-lightgreen'];            
        }
        return $result;    
    }
}
