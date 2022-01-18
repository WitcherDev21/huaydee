<?php
namespace backend\controllers;

use common\models\SmsMessage;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

use yii;
use common\libs\Constants;
use common\models\PostCreditTransection;
use common\models\UserHasBank;
use common\models\UserHasBankSearch;
use common\models\CreditTransectionSearch;
use common\models\PostCreditTransectionSearch;
use common\models\Credit;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class ApiMessageController extends Controller
{
	
    public function actionCheckCredit()
    {

    //หา sms ที่เข้ามาก่อนหน้า 15 นาที และยังไม่ได้ใช้งาน 
    $time = date('Y-m-d H:i:s', strtotime('-15 minutes'));
    $smsMessage = SmsMessage::find()->where([
        '>' , 'createdAT' , $time,
    ])
    ->andWhere(['is_used' => 0])
    ->all();

    // id ของข้อความที่เข้ามา 15 นาที
    $amountId = ArrayHelper::getColumn($smsMessage, 'amount');

    //หารายการแจ้งฝาก ที่แจ้งเข้ามา 15 นาที
    $postCredit = PostCreditTransection::find()->where([
        '>' , 'create_at' , $time,
    ])
    ->andWhere(['is_auto' => 0])
    ->andWhere(['in','amount', $amountId])
    ->all();

    //id ที่ยอดแจ้งฝากตรงกันกับ sms ที่เข้ามา
    $postId = ArrayHelper::getColumn($postCredit, 'poster_id');

    // print_r ($postId);
    $status = (int)$postId === 1 ? Constants::status_approve : Constants::status_waitting;

    //ถ้า error ให้ย้อนกลับ
    $transaction = Yii::$app->db->beginTransaction();
    try {
        foreach ($postCredit as $model)
            {
                $model->status = $status;
                $model->is_auto = $status === Constants::status_approve ? 1 : 0;
                
                if ($model->update()) {
                    
                    $CreditMasterBalance = CreditTransectionSearch::checkCreditMasterBalance($model->action_id, Constants::reason_credit_top_up, $model->amount);
                    if (!empty($CreditMasterBalance) && isset($CreditMasterBalance['amount']) && $CreditMasterBalance['amount'] < 0) {
                        return ['result' => 'not allow method post only'];
                    }
                    $result = Credit::creditWalk($model->action_id, $model->poster_id, Constants::user_system_id, Constants::reason_credit_top_up, $model->amount);
                    if (!$result) {
                        throw new ServerErrorHttpException('Can not save credit');
                    }

                    //หารายการแจ้งฝาก ที่แจ้งเข้ามา 30 นาที และใช้งานแล้ว
                    $postCreditUse = PostCreditTransection::find()->where([
                        '>' , 'create_at' , $time,
                    ])
                    ->andWhere(['is_auto' => 1])
                    ->andWhere(['in','amount', $amountId])
                    ->all();

                    $postCreditID = ArrayHelper::getColumn($postCreditUse, 'amount');

                    $smsMessageUse = SmsMessage::find()->where([
                        '>' , 'createdAt' , $time,
                    ])
                    ->andWhere(['in','amount', $postCreditID])
                    ->all();

                    foreach ($smsMessageUse as $modelSMS)
                    {
                        $modelSMS->is_used = Constants::status_active;
                        $modelSMS->update();
                    }
                }
            }
		$transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionReceived()
    {
//API Key
$api_key = 'pcervE-HEopWcVhQiXaNZQ';
//initialize class with apiKey and AuthToken(if available)
        $mysms = new \common\libs\Mysms($api_key);
//lets login user to get AuthToken
        $login_data = array('msisdn' => '+66910452954', 'password' => '290636');
        
        $login = $mysms->ApiCall('json', '/user/login', $login_data);  //providing REST type(json/xml), resource from http://api.mysms.com/index.html and POST data
        $user_info = json_decode($login); //decode json string to get AuthToken
        $_SESSION['AuthToken'] = $user_info->authToken; //saving auth Token in session for more calls
        $mysms->setAuthToken($user_info->authToken); //setting up auth Token in class (optional)

        /*$_SESSION['AuthToken'] = 'FiN8qqlfui3xLSdDqtDl0dvx6tWCql5rqf4y-L8Dce6RFvb2l5g8_xTcZJiPau0zd7GAu7goqX0';
        if (!isset($_SESSION['AuthToken'])) {
            $login = $mysms->ApiCall('json', '/user/login', $login_data);  //providing REST type(json/xml), resource from http://api.mysms.com/index.html and POST data
            $user_info = json_decode($login); //decode json string to get AuthToken
            $_SESSION['AuthToken'] = $user_info->authToken;
        }
        $mysms->setAuthToken($_SESSION['AuthToken']); //setting up auth Token in class (optional)*/
        
        //ไืทยพาณิชย์
        $bankName = 'ธนาคารไทยพาณิชย์';
        $sender = '+6627777777';
        $req_data = array('apiKey' => $api_key, 'authToken' => $_SESSION['AuthToken'], 'address' => $sender); //providing AuthToken as per mysms developer doc
        $usercontacts = $mysms->ApiCall('json', '/user/message/get/by/conversation', $req_data); //calling method ->ApiCall
        $messages = json_decode($usercontacts)->messages;
        $smsMessage = SmsMessage::find()->where(['bank' => $bankName])->orderBy(['message_id' => SORT_DESC])->asArray()->all();
        $messageIds = ArrayHelper::getColumn($smsMessage, 'message_id');

        foreach ($messages as $message) {
            $now = date('Y-m-d');
            $date = date('Y-m-d', $message->dateSent / 1000);
            if ($date !== $now || array_search($message->messageId, $messageIds) !== false) {
                continue;
            }
            $textMessages = explode(' ', $message->message);
            if (strpos($message->message, 'เข้า') !== false){
                $amount = floatval(preg_replace('/[^\d\.]+/', '', $textMessages[1]));
                $dateTimes = explode('@', $textMessages[0]);
                $date = $dateTimes[0].'/'.date("Y");
                $res = explode("/", $date);
                $changedDate = $res[2]."-".$res[1]."-".$res[0];
                $dateTime = $changedDate.' '.$dateTimes[1];
                $smsMessage = new SmsMessage();
                $smsMessage->message = $message->message;
                $smsMessage->message_id = $message->messageId;
                $smsMessage->action = 'ฝาก/โอนเงินเข้า';
                $smsMessage->amount = $amount;
                $smsMessage->date = date('Y-m-d H:i:s', strtotime($dateTime));
                $smsMessage->bank = $bankName;
                $smsMessage->save();
            }
        }

        //กสิกร
        $bankName = 'ธนาคารกสิกรไทย';
        $sender = 'KBank';
        $req_data = array('apiKey' => $api_key, 'authToken' => $_SESSION['AuthToken'], 'address' => $sender); //providing AuthToken as per mysms developer doc
        $usercontacts = $mysms->ApiCall('json', '/user/message/get/by/conversation', $req_data); //calling method ->ApiCall
        $messages = json_decode($usercontacts)->messages;
        $smsMessage = SmsMessage::find()->where(['bank' => $bankName])->orderBy(['message_id' => SORT_DESC])->asArray()->all();
        $messageIds = ArrayHelper::getColumn($smsMessage, 'message_id');
        foreach ($messages as $message) {
            $now = date('Y-m-d');
            $date = date('Y-m-d', $message->dateSent / 1000);
            if ($date !== $now || array_search($message->messageId, $messageIds) !== false) {
                continue;
            }
            $messages = $message->message;
                    print_r ($messages);

            if (strpos($messages, 'รับโอนจาก') !== false || strpos($messages, 'เงินเข้า') !== false){
                $textMessages = explode(' ', $messages);
                $dateTimes = explode('/', $textMessages[0]);
                $changedDate = date('Y')."-".$dateTimes[1]."-".$dateTimes[0];
                $dateTime = $changedDate.' '.$textMessages[1];
                if (strpos($messages, 'รับโอนจาก') !== false) {
                    $amount = floatval(preg_replace('/[^\d\.]+/', '', $textMessages[4]));
                } else {
                    $amount = floatval(preg_replace('/[^\d\.]+/', '', $textMessages[3]));
                }
                $smsMessage = new SmsMessage();
                $smsMessage->message = $messages;
                $smsMessage->message_id = $message->messageId;
                $smsMessage->action = 'ฝาก/โอนเงินเข้า';
                $smsMessage->amount = $amount;
                $smsMessage->date = date('Y-m-d H:i:s', strtotime($dateTime));
                $smsMessage->bank = $bankName;
                $smsMessage->save();
            }
        }

        //กรุงศรี
        $bankName = 'ธนาคารกรุงศรีอยุธยา';
        $sender = 'Krungsri';
        $req_data = array('apiKey' => $api_key, 'authToken' => $_SESSION['AuthToken'], 'address' => $sender); //providing AuthToken as per mysms developer doc
        $usercontacts = $mysms->ApiCall('json', '/user/message/get/by/conversation', $req_data); //calling method ->ApiCall
        $messages = json_decode($usercontacts)->messages;
        $smsMessage = SmsMessage::find()->where(['bank' => $bankName])->orderBy(['message_id' => SORT_DESC])->asArray()->all();
        $messageIds = ArrayHelper::getColumn($smsMessage, 'message_id');
        
        foreach ($messages as $message) {
            $now = date('Y-m-d');
            $date = date('Y-m-d', $message->dateSent / 1000);
            if ($date !== $now || array_search($message->messageId, $messageIds) !== false) {
                continue;
            }
            $messages = $message->message;
            if (strpos($messages, 'โอนเข้า') !== false){
                $textMessages = explode(' ', $messages);
                $amount = floatval(preg_replace('/[^\d\.]+/', '', $textMessages[2]));
                $dateTimes = explode('/', $textMessages[5]);
                $changedDate = date('Y')."-".$dateTimes[1]."-".str_replace('(', '', $dateTimes[0]);
                $time = explode(',', $dateTimes[2]);
                $dateTime = $changedDate.' '.str_replace(')', '', $time[1]);
                $smsMessage = new SmsMessage();
                $smsMessage->message = $messages;
                $smsMessage->message_id = $message->messageId;
                $smsMessage->action = 'ฝาก/โอนเงินเข้า';
                $smsMessage->amount = $amount;
                $smsMessage->date = date('Y-m-d H:i:s', strtotime($dateTime));
                $smsMessage->bank = $bankName;
                $smsMessage->save();
            }
        }

        //tmb
        $bankName = 'ธนาคารทหารไทย';
        $sender = 'TMBBANK';
        $req_data = array('apiKey' => $api_key, 'authToken' => $_SESSION['AuthToken'], 'address' => $sender); //providing AuthToken as per mysms developer doc
        $usercontacts = $mysms->ApiCall('json', '/user/message/get/by/conversation', $req_data); //calling method ->ApiCall
        $messages = json_decode($usercontacts)->messages;
        $smsMessage = SmsMessage::find()->where(['bank' => $bankName])->orderBy(['message_id' => SORT_DESC])->asArray()->all();
        $messageIds = ArrayHelper::getColumn($smsMessage, 'message_id');
        foreach ($messages as $message) {
            $now = date('Y-m-d');
            $date = date('Y-m-d', $message->dateSent / 1000);
            if ($date !== $now || array_search($message->messageId, $messageIds) !== false) {
                continue;
            }
            $messages = $message->message;
            if (strpos($messages, 'เข้า') !== false){
                $textMessages = explode('.', $messages);
                $amount = floatval(preg_replace('/[^\d\.]+/', '', $textMessages[0].'.'.$textMessages[1]));
                $dateTimes = explode('/', $textMessages[4]);
                $changedDate = date('Y')."-".$dateTimes[1]."-".$dateTimes[0];
                $time = explode('@', $dateTimes[2]);
                $dateTime = $changedDate.' '.$time[1];
                $smsMessage = new SmsMessage();
                $smsMessage->message = $messages;
                $smsMessage->message_id = $message->messageId;
                $smsMessage->action = 'ฝาก/โอนเงินเข้า';
                $smsMessage->amount = $amount;
                $smsMessage->date = date('Y-m-d H:i:s', strtotime($dateTime));
                $smsMessage->bank = $bankName;
                $smsMessage->save();
            }
        }

        //กรุงไทย
        $bankName = 'ธนาคารกรุงไทย';
        $sender = 'Krungthai';
        $req_data = array('apiKey' => $api_key, 'authToken' => $_SESSION['AuthToken'], 'address' => $sender); //providing AuthToken as per mysms developer doc
        $usercontacts = $mysms->ApiCall('json', '/user/message/get/by/conversation', $req_data); //calling method ->ApiCall
        $messages = json_decode($usercontacts)->messages;
        $smsMessage = SmsMessage::find()->where(['bank' => $bankName])->orderBy(['message_id' => SORT_DESC])->asArray()->all();
        $messageIds = ArrayHelper::getColumn($smsMessage, 'message_id');
        foreach ($messages as $message) {
            $now = date('Y-m-d');
            $date = date('Y-m-d', $message->dateSent / 1000);
            if ($date !== $now || array_search($message->messageId, $messageIds) !== false) {
                continue;
            }
            $messages = $message->message;
            if (strpos($messages, 'เงินเข้า') !== false){
                $textMessages = explode(' ', $messages);
                $amount = floatval(preg_replace('/[^\d\.]+/', '', $textMessages[3]));
                $dateTimes = explode('-', $textMessages[0]);
                $changedDate = date('Y')."-".$dateTimes[1]."-".$dateTimes[0];
                $time = explode('@', $dateTimes[2]);
                $dateTime = $changedDate.' '.$time[1];
                $smsMessage = new SmsMessage();
                $smsMessage->message = $messages;
                $smsMessage->message_id = $message->messageId;
                $smsMessage->action = 'ฝาก/โอนเงินเข้า';
                $smsMessage->amount = $amount;
                $smsMessage->date = date('Y-m-d H:i:s', strtotime($dateTime));
                $smsMessage->bank = $bankName;
                $smsMessage->save();
            }
        }

        //ธนชาต
        $bankName = 'ธนาคารธนชาต';
        $sender = 'TBANK';
        $req_data = array('apiKey' => $api_key, 'authToken' => $_SESSION['AuthToken'], 'address' => $sender); //providing AuthToken as per mysms developer doc
        $usercontacts = $mysms->ApiCall('json', '/user/message/get/by/conversation', $req_data); //calling method ->ApiCall
        $messages = json_decode($usercontacts)->messages;
        $smsMessage = SmsMessage::find()->where(['bank' => $bankName])->orderBy(['message_id' => SORT_DESC])->asArray()->all();
        $messageIds = ArrayHelper::getColumn($smsMessage, 'message_id');
        foreach ($messages as $message) {
            $now = date('Y-m-d');
            $date = date('Y-m-d', $message->dateSent / 1000);
            if ($date !== $now || array_search($message->messageId, $messageIds) !== false) {
                continue;
            }
            $messages = $message->message;
            if (strpos($messages, 'เงินเข้า') !== false){
                $textMessages = explode(' ', $messages);
                $dateTimes = explode('/', $textMessages[9]);
                $changedDate = date('Y')."-".$dateTimes[1]."-".$dateTimes[0];
                $time = date('H:i', strtotime($textMessages[10]));
                $dateTime = $changedDate.' '.$time;
                $smsMessage = new SmsMessage();
                $smsMessage->message = $messages;
                $smsMessage->message_id = $message->messageId;
                $smsMessage->action = 'ฝาก/โอนเงินเข้า';
                $smsMessage->amount = $amount;
                $smsMessage->date = date('Y-m-d H:i:s', strtotime($dateTime));
                $smsMessage->bank = $bankName;
                $smsMessage->save();
            }
        }
        return ['message' => 'success'];
    }

}