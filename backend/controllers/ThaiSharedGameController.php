<?php
namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\PlayType;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use common\models\ThaiSharedGameChitDetailSearch;
use common\models\ThaiSharedGameChitSearch;
use common\models\Credit;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\Response;
/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/26/2018
 * Time: 9:21 PM
 */

class ThaiSharedGameController extends Controller
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
            if(!in_array('thai-shared-game', $arrRoles)){
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

    public function actionList()
    {
        $active_tab = 'list';
        $searchModel = new ThaiSharedGameChitSearch();
        if (isset(Yii::$app->request->queryParams['ThaiSharedGameChitSearch']['title'])) {
            $searchModel->title = Yii::$app->request->queryParams['ThaiSharedGameChitSearch']['title'];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('date('.ThaiSharedGameChit::tableName().'.createdAt) = DATE((NOW() - INTERVAL 7 HOUR))');
        $dataProvider->query->orderBy('createdAt DESC');

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'active_tab' => $active_tab,
        ]);
    }

    public function actionHistory()
    {
        $userId = Yii::$app->user->id;
        $active_tab = 'history';
        $searchModel = new ThaiSharedGameChitSearch();
        if (isset(Yii::$app->request->queryParams['ThaiSharedGameChitSearch']['title'])) {
            $searchModel->title = Yii::$app->request->queryParams['ThaiSharedGameChitSearch']['title'];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('date('.ThaiSharedGameChit::tableName().'.createdAt) <> DATE((NOW() - INTERVAL 7 HOUR))');
        $dataProvider->query->orderBy('createdAt DESC');

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'active_tab' => $active_tab,
        ]);
    }

    /**
     * Displays a single LotteryGameChit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDetail($thaiSharedGameChitId, $from)
    {
        $searchModel = new ThaiSharedGameChitDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['thaiSharedGameChitId' => $thaiSharedGameChitId]);
        $dataProvider->setSort([
            'defaultOrder' => ['number'=>SORT_DESC],
        ]);
        $thaiSharedGameChit = ThaiSharedGameChit::findOne(['id'=> $thaiSharedGameChitId]);
        $gameId = $thaiSharedGameChit->thaiSharedGame->gameId;
        if ($gameId === Constants::LOTTERYLAOGAME || $gameId === Constants::LOTTERYLAODISCOUNTGAME || $gameId === Constants::LOTTERY_VIETNAM_SET) {
            throw new ServerErrorHttpException('Can not view detail because game id not match');
        }

        return $this->render('detail',[
            'thaiSharedGameChit' => $thaiSharedGameChit,
            'dataProvider' => $dataProvider,
            'active_tab' => $from,
            'from' => $from
        ]);
    }
    public function actionCancelNumberByUser($number,$createdAt,$endDate,$playTypeId,$uid)
    {
      // echo "<pre>",var_dump($number),"</pre>";exit();
       \Yii::$app->response->format = Response::FORMAT_JSON;
       $thaiSharedGameChit = ThaiSharedGameChitDetail::find()->Where(['number'=> $number,'userId' => $uid])->andFilterWhere(['between', 'createdAt', $createdAt, $endDate.' 23:59:59'])->one();
       //echo "<pre>",var_dump($thaiSharedGameChit),"</pre>";exit();
       if (!$thaiSharedGameChit) {
           return ['message' => Yii::t('app', 'ไม่สามารถแก้ไขข้อมูลได้ Code:01'),'s' => 'error'];
       }
       $Credit = Credit::findOne(['user_id'=> $uid]);
       $balance = $Credit->balance;
       $Credit->balance = $balance+ $thaiSharedGameChit->amount;
       if (!$Credit->save()) {
           return ['message' => Yii::t('app', 'ไม่สามารถแก้ไขข้อมูลได้ Code:C'),'s' => 'error'];
       }
       $credit_transection =  Yii::$app->db->createCommand("INSERT INTO `credit_transection` 
       (`id`, `action_id`, `operator_id`, `reciver_id`, `amount`, `balance`, `remark`, `create_at`, `create_by`, `update_at`, `update_by`, `reason_action_id`, `credit_master_balance`) 
       VALUES 
       (NULL, '1', $uid, $uid, $thaiSharedGameChit->amount, $balance, 'คืนยอดแทงหวย / ".date('Y-m-d H:i:s')." #".$number."', '".date('Y-m-d H:i:s')."', $uid, NULL, NULL, 6, $Credit->balance);")->execute();
       if(!$credit_transection){
           return ['message' => Yii::t('app', 'ไม่สามารถแก้ไขข้อมูลได้ Code:CT'),'s' => 'error'];
       }
       $ThaiSharedGameChitDetail = ThaiSharedGameChitDetail::findOne($thaiSharedGameChit->id);
       if (!$ThaiSharedGameChitDetail->delete()) {
           return ['message' => Yii::t('app', 'ไม่สามารถแก้ไขข้อมูลได้ Code:TSHCD'),'s' => 'error'];
       }    

    }
    public function actionCancelNumber($number,$createdAt,$endDate,$playTypeId)
    {     
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $thaiSharedGameChit = ThaiSharedGameChitDetail::find()->Where(['number'=> $number,'playTypeId' => $playTypeId])->andFilterWhere(['between', 'createdAt', $createdAt, $endDate.' 23:59:59'])->all();
        //echo "<pre>",var_dump($thaiSharedGameChit),"</pre>";exit();
        if (!$thaiSharedGameChit) {
            return ['message' => Yii::t('app', 'ไม่สามารถแก้ไขข้อมูลได้ Code:01'),'s' => 'error'];
        }
        foreach($thaiSharedGameChit as $key => $val){ 
            //echo "<pre>",var_dump($val),"</pre>";exit();
            $Credit = Credit::findOne(['user_id'=> $val->userId]);
            $balance = $Credit->balance;
            $Credit->balance = $balance+ $val->amount;
            if (!$Credit->save()) {
                return ['message' => Yii::t('app', 'ไม่สามารถแก้ไขข้อมูลได้ Code:C'),'s' => 'error'];
            }
            $credit_transection =  Yii::$app->db->createCommand("INSERT INTO `credit_transection` 
            (`id`, `action_id`, `operator_id`, `reciver_id`, `amount`, `balance`, `remark`, `create_at`, `create_by`, `update_at`, `update_by`, `reason_action_id`, `credit_master_balance`) 
            VALUES 
            (NULL, '1', $val->userId, $val->userId, $val->amount, $balance, 'คืนยอดแทงหวย / ".date('Y-m-d H:i:s')." #".$number."', '".date('Y-m-d H:i:s')."', $val->userId, NULL, NULL, 6, $Credit->balance);")->execute();
            if(!$credit_transection){
                return ['message' => Yii::t('app', 'ไม่สามารถแก้ไขข้อมูลได้ Code:CT'),'s' => 'error'];
            }
            $ThaiSharedGameChitDetail = ThaiSharedGameChitDetail::findOne($val->id);
            if (!$ThaiSharedGameChitDetail->delete()) {
                return ['message' => Yii::t('app', 'ไม่สามารถแก้ไขข้อมูลได้ Code:TSHCD'),'s' => 'error'];
            }
        }
        return ['message' => Yii::t('app','แก้ไขข้อมูลเรียบร้อยแล้ว'),'s' => 'success'];
    }
    public function actionDetailNumber($thaiSharedGameChitId,$number, $from, $playTypeId,$createdAt,$endDate)
    {
        $searchModel = new ThaiSharedGameChitDetailSearch();
        $dataProvider = $searchModel->searchDetail(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['number' => $number, 'playTypeId' => $playTypeId]);
        $thaiSharedGameChit = ThaiSharedGameChit::findOne(['id'=> $thaiSharedGameChitId]);
        $gameId = $thaiSharedGameChit->thaiSharedGame->gameId;
        if ($gameId === Constants::LOTTERYLAOGAME || $gameId === Constants::LOTTERYLAODISCOUNTGAME || $gameId === Constants::LOTTERY_VIETNAM_SET) {
            throw new ServerErrorHttpException('Can not view detail because game id not match');
        }

        return $this->render('detail-number',[
            'thaiSharedGameChit' => $thaiSharedGameChit,
            'dataProvider' => $dataProvider,
            'active_tab' => $from,
            'from' => $from
        ]);
    }

    public function actionDetailLotteryLaoSet($thaiSharedGameChitId, $from)
    {
        $thaiSharedGameChit = ThaiSharedGameChit::findOne(['id'=> $thaiSharedGameChitId]);
        $gameId = $thaiSharedGameChit->thaiSharedGame->gameId;
        if ($gameId !== Constants::LOTTERYLAOGAME && $gameId !== Constants::LOTTERYLAODISCOUNTGAME && $gameId !== Constants::LOTTERY_VIETNAM_SET) {
            throw new ServerErrorHttpException('Can not view detail because game id not match');
        }
        $thaiSharedGameChitDetailCorrects = ThaiSharedGameChitDetail::find()->where([
            'thaiSharedGameChitId' => $thaiSharedGameChit->id,
            'flag_result' => 1
        ])->groupBy('numberSetLottery')->all();


        $thaiSharedGameChitDetails = ThaiSharedGameChitDetail::find()->where([
            'thaiSharedGameChitId' => $thaiSharedGameChit->id,
            'flag_result' => 0
        ]);
        if ($thaiSharedGameChitDetailCorrects) {
            foreach ($thaiSharedGameChitDetailCorrects as $thaiSharedGameChitDetailCorrect) {
                $numberSetLotterys[] = $thaiSharedGameChitDetailCorrect->numberSetLottery;
            }
            $thaiSharedGameChitDetails->andWhere(['not in', 'numberSetLottery', $numberSetLotterys]);
        }
        $thaiSharedGameChitDetails->groupBy('numberSetLottery');

        $dataProvider = new ArrayDataProvider([
            'allModels' => array_merge($thaiSharedGameChitDetailCorrects, $thaiSharedGameChitDetails->all()),
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);

        return $this->render('detail-lottery-lao-set',[
            'thaiSharedGameChit' => $thaiSharedGameChit,
            'dataProvider' => $dataProvider,
            'active_tab' => $from,
            'from' => $from
        ]);
    }

    public function actionResultNumber()
    {
        $userId = Yii::$app->user->id;
        $active_tab = 'result-number';
        $searchModel = new ThaiSharedGameChitDetailSearch();
        $gameIds = [
            Constants::LOTTERYLAOGAME,
            Constants::LOTTERYLAODISCOUNTGAME,
            Constants::THAISHARED,
            Constants::LOTTERYGAME,
            Constants::LOTTERYGAMEDISCOUNT,
            Constants::LOTTERY_VIETNAM_SET,
            Constants::VIETNAMVIP,
            Constants::BACC_THAISHARD_GAME,
            Constants::GSB_THAISHARD_GAME,
            Constants::LAOS_CHAMPASAK_LOTTERY_GAME,
            Constants::VIETNAM4D_GAME,
            Constants::LOTTERYRESERVEGAME
        ];
        $playTypeObjs = PlayType::find()->where(['game_id' => $gameIds])->all();
        $playTypeObjs = ArrayHelper::map($playTypeObjs, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });
        $dataProvider = $searchModel->searchResultNumber(Yii::$app->request->queryParams);

        return $this->render('result-number', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'active_tab' => $active_tab,
            'playTypeObjs' => $playTypeObjs,
        ]);
    }
}
