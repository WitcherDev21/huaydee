<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\libs\Constants;
use common\models\YeekeeChit;
use common\models\YeekeeChitDetail;
use common\models\YeekeeChitSearch;
use common\models\Credit;
use common\models\Yeekee;
use common\models\YeekeeSearch;
use common\models\Running;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class YeekeechitController extends Controller
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

    public function actionIndex()
    {

        return $this->redirect(['yeekeechit/list-current']);
    }

    public function actionListCurrent()
    {
        $user_id = \Yii::$app->user->identity->id;
        $active_tab = 'list-current';
        $running = Running::find()->where(['game_id' => Constants::YEEKEE])->one();
        $yeekee = Yeekee::find()->select(['date_at'])->where(['group' => $running->running])->one();

        if (empty($yeekee)) {
            $yeekee = new Yeekee();
        }
        $searchModel = new YeekeeChitSearch();
        $searchModel->date_at = $yeekee->date_at;
        $searchModel->from = 'frontend';
        $searchModel->page = YeekeeChitSearch::CURRENT;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'active_tab' => $active_tab
        ]);
    }

    public function actionListHistory()
    {
        $user_id = \Yii::$app->user->identity->id;
        $active_tab = 'list-history';

        $running = Running::find()->where(['game_id' => Constants::YEEKEE])->one();
        $yeekee = YeekeeSearch::find()->select(['date_at'])->where(['<>', 'date_at', date('Y-m-d')])->one();
            if (empty($yeekee)) {
            $yeekee = new YeekeeSearch();
        }
        $searchModel = new YeekeeChitSearch();
        $searchModel->from = 'frontend';
        $searchModel->page = YeekeeChitSearch::HISTORY;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['<>', 'date_at', date('Y-m-d')]);

        return $this->render('_list-history', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'active_tab' => $active_tab
        ]);
    }

    public function actionDetail($id)
    {

        $userId = Yii::$app->user->id;
        $yeekeeGameChit = $this->findModel($id, $userId);
        return $this->render('detail',[
            'yeekeeGameChit' => $yeekeeGameChit,
        ]);
    }

    public function actionSummary()
    {
        $user_id = \Yii::$app->user->id;
        $active_tab = 'summary';
        //$run = Running::findOne(['game_id'=>Constants::YEEKEE]);
        //$yeekeeGroup = Yeekee::find()->where(['group'=>$run->running])->all();
        //$yeekeeCurentGroup = ArrayHelper::map($yeekeeGroup, 'id', 'id');

        //yeekee chit in day
        $modelsInDay = YeekeeChitSearch::find()->where(['create_by' => $user_id])
            //->andWhere(['in','yeekee_id',$yeekeeCurentGroup])->all();
            ->andWhere(['between', 'create_at', date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->all();

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
        $modelsHistory = YeekeeChitSearch::find()->where(['create_by' => $user_id])
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

    public function actionCancelYeekeechit()
    {
        $user_id = \Yii::$app->user->identity->id;
        $yeekeechit_id = \Yii::$app->request->get('id');

        $yeekeeChit = YeekeeChitSearch::findOne(['id' => $yeekeechit_id]);
        if (empty($yeekeeChit)) {
            return false;
        }

        if (($yeekeeChit->getCanReChit())) {
            $remark = 'คืนโพย จับยี่กี รอบที่ ' . $yeekeeChit->yeekee->round . ' / ' . date('d/m/Y', strtotime($yeekeeChit->yeekee->date_at)) . ' #' . $yeekeeChit->getOrder();
            $resutl = Credit::creditWalk(Constants::action_credit_top_up, $yeekeeChit->user_id, $user_id, Constants::reason_credit_return_chit, $yeekeeChit->total_amount, $remark);

            $yeekeeChit->status = Constants::status_cancel;
            $yeekeeChit->update_at = date('Y-m-d H:i:s');
            $yeekeeChit->update_by = $user_id;
            $yeekeeChit->total_amount = 0;

            if ($yeekeeChit->save()) {
                YeekeeChitDetail::updateAll(['amount' => 0], ['yeekee_chit_id' => $yeekeeChit->id]);
            }
            return $resutl;
        } else {
            return false;
        }
    }

    /**
     * Finds the LotteryGameChit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return YeekeeChit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $userId)
    {
        if (($model = YeekeeChitSearch::find()->where(['id' => $id, 'user_id' => $userId])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
