<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\libs\Constants;
use common\models\Yeekee;
use common\models\Running;
use common\models\Games;
use common\models\YeekeePost;
use common\models\PlayType;
use common\models\YeekeeChitDetail;
use common\models\YeekeeChit;
use yii\data\ActiveDataProvider;
use common\models\YeekeeSearch;
use common\models\YeekeeChitSearch;
use common\models\Credit;
use yii\helpers\ArrayHelper;
use common\models\NumberMemo;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class NumberMemoController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /**
     *
     * {@inheritdoc}
     *
     */
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $userId = \Yii::$app->user->id;
        $model = NumberMemo::find()->where(['id' => Yii::$app->request->post('id'), 'user_id' => $userId])->one();
        if (!$model) {
            return ['result' => 'not found'];
        }
        $model->delete();
        return ['result' => 'success'];
    }

    public function actionIndex()
    {
        $user_id = \Yii::$app->user->identity->id;
        $numberMemoList = NumberMemo::find()->where(['user_id' => $user_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $numberMemoList,
            'sort' => false,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {

        $params = \Yii::$app->request->post();
        $user_id = \Yii::$app->user->identity->id;
        $memo_id = \Yii::$app->request->get('id');
        $numberMemo = NumberMemo::find()->where(['id' => $memo_id])->one();
        if (empty($numberMemo)) {

            $numberMemo = new NumberMemo();
            $numberMemo->create_at = date('Y-m-d H:i:s');
            $numberMemo->create_by = $user_id;
            $numberMemo->user_id = $user_id;
            $numberMemo->title = '';
        } else {
            $params['gameId'] = $numberMemo->gameId;
        }

        if (!empty($params)) {
            $values = [];
            if (!isset($params['number']) || !isset($params['option']) ||  !isset($params['title'])) {
                $title = $params['title'];
                return $this->redirect(['number-memo/_form', 'title' => $title]);
            }

            for ($i = 0; $i < count($params['option']); $i++) {
                $code = $params['option'][$i];
                $number = $params['number'][$i];
                $playTmp = PlayType::find()->where(['game_id' => [Constants::THAISHARED, Constants::LOTTERYGAME], 'code' => $code])->one();
                $length = $playTmp->group->number_length;
                if ($length == strlen($number)) {
                    $values[$code][$number] = 'memo-minimum';
                }
            }

            $tmpResult = [];
            foreach ($values as $type => $result) {
                $tmpResult[$type] = json_encode($result, JSON_FORCE_OBJECT);
            }
            $jsonMap = json_encode($tmpResult, JSON_FORCE_OBJECT);
            $numberMemo->json_value = $jsonMap;
            $numberMemo->title = $params['title'];
            $numberMemo->update_at = date('Y-m-d H:i:s');
            $numberMemo->update_by = $user_id;
            $numberMemo->gameId = Constants::THAISHARED;

            if ($numberMemo->save()) {
                return $this->redirect(['number-memo/index']);
            } else {
                return $this->redirect(['number-memo/_form']);
            }

        }

        $query = PlayType::find()->where(['game_id' => [Constants::YEEKEE]]);
        $playType = ArrayHelper::map($query->all(), 'code', 'title');
        $lotteryPlayTypes = PlayType::find()->where(['game_id' => [Constants::LOTTERYGAME]])->all();
        $playTypeRule = [];
        $gameObjs = Games::find()->where(['id' => [Constants::YEEKEE, Constants::THAISHARED, Constants::LOTTERYGAME, Constants::LOTTERYGAMEDISCOUNT]])->all();
        $games = ArrayHelper::map($gameObjs, 'id', 'title');


        return $this->render('_form', [
            'numberMemo' => $numberMemo,
            'playType' => $playType,
            'playTypeRule' => $playTypeRule,
            'games' => $games,
            'lotteryPlayTypes' => $lotteryPlayTypes,
        ]);
    }

    public function actionView($id)
    {
        $userId = Yii::$app->user->id;
        $numberMemo = NumberMemo::find()->where(['id' => $id, 'create_by' => $userId])->one();
        if (!$numberMemo) {
            throw new NotFoundHttpException('Not Found');
        }
        $numberPlayTypes = json_decode($numberMemo->json_value, true);
        foreach ($numberPlayTypes as $playType => $numberPlayType) {
            $numbers = json_decode($numberPlayType);
            $playTypeObj = PlayType::find()->where(['code' => $playType, 'game_id' => Constants::THAISHARED])->one();
            $key = 1;
            foreach ($numbers as $number => $value) {
                $numberSets[$playType][$key]['title'] = $playTypeObj->title;
                $numberSets[$playType][$key]['number'] = $number;
                $key++;
            }
        }
        return $this->render('view', [
            'numberMemo' => $numberMemo,
            'numberSets' => $numberSets,
        ]);
    }
}
