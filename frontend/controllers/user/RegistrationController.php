<?php

namespace frontend\controllers\user;

use dektrium\user\controllers\RegistrationController as BaseRegistrationController;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
//use dektrium\user\models\RegistrationForm;
use frontend\models\RegistrationForm;
use common\models\Bank;
use common\libs\Constants;
use common\models\UserHasBank;
use common\models\UserHasBankLog;
use dektrium\user\models\User;
use common\models\Alian;
use common\models\Credit;
use common\models\LogClick;
use common\models\UserHasBankSearch;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

class RegistrationController extends BaseRegistrationController
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
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['view', 'search', 'register'],
                        'allow' => true,
                        'roles' => ['?', '@', 'admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionRegister()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = '@frontend/themes/login/views/layouts/main.php';
        //user related
        $relateUserId = '';
        $encryptAgentId = \Yii::$app->request->get('from');
        $recommend = User::find()->where(['recommend' => $encryptAgentId])->count();
        if (!$recommend) {
            return $this->render('@frontend/views/site/error', [
                'exception' => 'ลื้งค์ระบบแนะนำไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง'
            ]);
        }

        if (!empty($encryptAgentId)) {
            $relateUserId = Constants::Decrypt(Constants::private_key, $encryptAgentId);
            $is_user = \common\models\User::findOne($relateUserId);
            if (empty($is_user)) {
                $relateUserId = '';
            } else {
                $ip = Constants::getIP();
                $model = new LogClick();
                $model->from_ip = $ip;
                $model->from_source = 'link';
                $model->from_user_id = $relateUserId;
                $model->create_at = date('Y-m-d H:i:s');
                $model->save();
            }
        }
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }
        /** @var RegistrationForm $model */
        $user = \Yii::createObject(RegistrationForm::className());
        $event = $this->getFormEvent($user);
        $this->trigger(self::EVENT_BEFORE_REGISTER, $event);
        $this->performAjaxValidation($user);

        if ($user->load(\Yii::$app->request->post()) && $user->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            $error = false;
            $params = \Yii::$app->request->post('register-form');
            $email = $params['username'];
            $hasInAlian = Alian::findOne(['alian_name' => $params['username']]);
            if (!empty($hasInAlian)) {
                \Yii::$app->session->setFlash(
                    'warning',
                    \Yii::t('user', 'มี username นี้แล้ว')
                );
                return $this->redirect(['registration/register', 'from' => $encryptAgentId]);
            }
            $user->email = $email . '@mail.com';//หลอก ระบบเรื่องเมล์ไปก่อน
            if ($user->register()) {
                $tmp_user = User::find()->where(['username' => $params['username']])->one();
                $tmp_user->tel = $params['tel'];
                $tmp_user->user_status = 1;
                $tmp_user->auth_roles_id = Constants::auth_roles_member;
                $tmp_user->save();
                if (!empty($relateUserId)) {
                    $tmp_user->agent = $relateUserId;// relate user agent
                    $tmp_user->auth_roles_id = Constants::auth_roles_member;
                    $tmp_user->save();
                }

                //register bank
                $hasBank = new UserHasBank();
                $hasBank->user_id = $tmp_user->id;
                $hasBank->bank_id = $params['bank_id'];
                $hasBank->bank_account_name = $params['bank_account_name'];
                $hasBank->bank_account_no = $params['bank_account_no'];
                $hasBank->create_at = date('Y-m-d H:i:s', time());
                $hasBank->create_by = $tmp_user->id;
                $hasBank->status = Constants::status_waitting;
                $hasBank->version = 1;
                if (!$hasBank->save()) {
                    $error = true;
                }

                $hasBankLog = new UserHasBankLog();
                $hasBankLog->user_has_bank_id = $hasBank->id;
                $hasBankLog->user_id = $tmp_user->id;
                $hasBankLog->bank_id = $params['bank_id'];
                $hasBankLog->bank_account_name = $params['bank_account_name'];
                $hasBankLog->bank_account_no = $params['bank_account_no'];
                $hasBankLog->create_at = date('Y-m-d H:i:s', time());
                $hasBankLog->create_by = $tmp_user->id;
                $hasBankLog->status = Constants::status_active;
                $hasBankLog->version = 1;
                if (!$hasBankLog->save()) {
                    $error = true;
                }
                $credit = new Credit();
                $credit->create_by = $tmp_user->id;
                $credit->create_at = date('Y-m-d H:i:s', time());
                $credit->user_id = $tmp_user->id;
                $credit->balance = 0;
                if (!$credit->save()) {
                    $error = true;
                }
                if (!$error) {
                    Yii::$app->getSession()->setFlash('alert',[
                        'body'=>'ลงทะเบียนเรียบร้อย!',
                        'options'=>['class'=>'alert-success']
                    ]);
                    $transaction->commit();
					
					echo $this->actionLineNotify($hasBank->bank_account_no);
					
                } else {
                    $transaction->rollBack();
                }

                return $this->redirect(['/user/security/login']);
            } else {
                $transaction->rollBack();
            }
        }
        $bank = Bank::find()->where(['status' => Constants::status_active])
            ->all();
        $arrBank = [];
        foreach ($bank as $b) {
            $arrBank[] = ['id' => $b->id, 'title' => $b->title, 'icon' => $b->icon, 'color' => $b->color];
        }
        return $this->render('register', [
            'model' => $user,
            'module' => $this->module,
            'arrBank' => $arrBank
        ]);
    }
	
	
	    public function actionLineNotify($bankaccount)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        date_default_timezone_set("Asia/Bangkok");

        $sToken = "iq4qFsU9sPBYYvaLTzl09K2ZtonUyKdThsgahZIgf58";
        $sMessage = "มีสมาชิกสมัครเข้ามาใหม่ กรุณากดยืนยันที่ ลิงค์ด้านล่าง \r\n https://".$_SERVER['SERVER_NAME'].'/backend/web/approve-user/approve?bankaccount='.$bankaccount;

        $chOne = curl_init(); 
        curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt( $chOne, CURLOPT_POST, 1); 
        curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
        $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec( $chOne ); 
    
        //Result error 
        if(curl_error($chOne)) 
        { 
            echo 'error:' . curl_error($chOne); 
        } 
        else { 
            $result_ = json_decode($result, true); 
            echo "status : ".$result_['status']; echo "message : ". $result_['message'];
        } 
        curl_close( $chOne );   
    }

}