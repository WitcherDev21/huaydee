<?php

namespace frontend\controllers;

use common\libs\Constants;
use common\models\BlackRed;
use common\models\BlackredChit;
use common\models\BlackredChitSearch;
use common\models\Credit;
use common\models\Running;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class BlackredchitController extends Controller
{

    /**
     *
     * {@inheritdoc}
     *
     */
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

    public function actionListCurrent()
    {
        $userId = \Yii::$app->user->id;
        $active_tab = 'list-current';
        $running = Running::find()->where(['game_id' => Constants::BLACKRED])->one();
        $blackRed = BlackRed::find()->select(['date_at'])->where(['group' => $running->running])->one();
        $searchModel = new BlackredChitSearch();
        $searchModel->create_by = $userId;
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
        $searchModel->create_by = $userId;

        $searchModel->date_at = isset(Yii::$app->request->queryParams['BlackredChitSearch']['date_at'])
            ? Yii::$app->request->queryParams['BlackredChitSearch']['date_at'] : '';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'active_tab' => $active_tab
        ]);
    }

    public function actionDetail($id, $blackRedId)
    {
        $userId = \Yii::$app->user->id;
        $query = BlackredChit::find()->where(['id' => $id, 'user_id' => $userId]);
        $blackredChit = $query;
        $blackredChitObj = $blackredChit->one();
        if (!$blackredChitObj) {
            throw new \yii\web\NotFoundHttpException();
        }
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

    public function actionSummary()
    {
        $userId = \Yii::$app->user->id;
        $active_tab = 'summary';
        $modelsInDay = BlackredChit::find()->where(['create_by' => $userId])->andWhere('DATE(create_at) = CURDATE()')->all();

        $sum_total_amount_inprogress = 0;
        $sum_total_amount_finish = 0;
        foreach ($modelsInDay as $model) {
            //$arrInDay[date('Y-m-d',strtotime($model->create_at))]
            if ($model->status != Constants::status_cancel) {
                if ($model->status == Constants::status_finish_show_result) {
                    $sum_total_amount_finish += $model->total_amount;
                } else {
                    $sum_total_amount_inprogress += $model->total_amount;
                }
            }
        }
        $inDay = ['sum_total_amount_inprogress' => $sum_total_amount_inprogress,
            'sum_total_amount_finish' => $sum_total_amount_finish];


        //yeekee chit history
// 		$modelsHistory = YeekeeChitSearch::find()->where(['create_by'=>$user_id])
// 		->andWhere(['not in','yeekee_id',$yeekeeCurentGroup])->all();
        $modelsHistory = BlackredChit::find()->where(['create_by' => $userId])
            ->andWhere('DATE(create_at) < CURRENT_DATE()')->all();


        $sum_total_amount_inprogress = 0;
        $sum_total_amount_finish = 0;
        $arrHistory = [];
        $total = 0;
        foreach ($modelsHistory as $model) {
            $create_at = date('Y-m-d', strtotime($model->create_at));
            if (!isset($arrHistory[$create_at])) {
                $arrHistory[$create_at] = ['sum_total_amount_inprogress' => 0, 'sum_total_amount_finish' => 0];
            }

            if ($model->status != Constants::status_cancel) {
                if ($model->status == Constants::status_finish_show_result) {
                    $arrHistory[$create_at]['sum_total_amount_finish'] += $model->total_amount;
                    $total += $model->total_amount;
                } else {
                    $arrHistory[$create_at]['sum_total_amount_inprogress'] += $model->total_amount;
                    $total += $model->total_amount;
                }
            }
        }

        return $this->render('summary', [
            'inDay' => $inDay,
            'arrHistory' => $arrHistory,
            'active_tab' => $active_tab,
            'total' => $total,
        ]);
    }

    public function actionCancel()
    {
        $userId = \Yii::$app->user->id;
        $blackRedId = \Yii::$app->request->get('id');

        $blackRedChit = BlackredChit::findOne(['id' => $blackRedId]);
        if (empty($blackRedChit)) {
            return false;
        }

        if (($blackRedChit->blackred->status == Constants::status_active) && ($blackRedChit->status == Constants::status_playing)) {
            $remark = 'คืนโพย ดำแดง รอบที่ ' . $blackRedChit->blackred->round . ' / ' . date('d/m/Y', strtotime($blackRedChit->blackred->date_at)) . ' #' . $blackRedChit->blackred->getOrderId();
            $resutl = Credit::creditWalk(Constants::action_credit_top_up, $blackRedChit->user_id, $userId, Constants::reason_credit_return_chit, $blackRedChit->total_amount, $remark);

            $blackRedChit->status = Constants::status_cancel;
            $blackRedChit->update_at = date('Y-m-d H:i:s');
            $blackRedChit->update_by = $userId;
            $blackRedChit->total_amount = 0;

            if (!$blackRedChit->save()) {
                return $this->render('site/error',[
                    'exception' => 'เกิดข้อผิดพลาดกรุณาลองใหม่อีกครั้ง',
                ]);
            }
            return $resutl;
        } else {
            return false;
        }
    }
}
