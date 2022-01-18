<?php

namespace frontend\controllers;

use common\libs\Constants;
use common\models\PostCreditTransection;
use common\models\ThaiSharedGameChit;
use common\models\ThaiSharedGameChitDetail;
use common\models\ThaiSharedGameChitDetailSearch;
use common\models\ThaiSharedGameChitSearch;
use common\models\YeekeeChit;
use common\models\YeekeeChitSearch;
use Yii;
use yii\base\ErrorException;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * LotteryGameController implements the CRUD actions for LotteryGameChit model.
 */
class ThaiSharedController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
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

    /**
     * Lists all LotteryGameChit models.
     * @return mixed
     */
    public function actionIndex($type = null)
    {
        $userId = Yii::$app->user->id;
        $now = date('Y-m-d');
        if ($type === 'GOVERNMENT') {
            $gameId = [Constants::LOTTERYGAME, Constants::LOTTERYGAMEDISCOUNT];
        } else if ($type === 'STOCK') {
            $gameId = [Constants::THAISHARED, Constants::VIETNAMVIP];
        } else if ($type === 'YEEKEE') {
            $gameId = Constants::YEEKEE;
        } else if ($type === 'GSB') {
            $gameId = Constants::GSB_THAISHARD_GAME;
        } else if ($type === 'BACC') {
            $gameId = Constants::BACC_THAISHARD_GAME;
        } else if ($type === 'LAOS_CHAMPASAK') {
            $gameId = Constants::LAOS_CHAMPASAK_LOTTERY_GAME;
        } else if ($type === 'VIETNAM_4D') {
            $gameId = Constants::VIETNAM4D_GAME;
        } else if ($type === 'LOTTERY_SERVE') {
            $gameId = Constants::LOTTERYRESERVEGAME;
        } else {
            $gameId = [
                Constants::LOTTERYGAME, Constants::LOTTERYGAMEDISCOUNT, Constants::THAISHARED, Constants::VIETNAMVIP,
                Constants::GSB_THAISHARD_GAME, Constants::BACC_THAISHARD_GAME, Constants::LAOS_CHAMPASAK_LOTTERY_GAME,
                Constants::VIETNAM4D_GAME, Constants::LOTTERYRESERVEGAME, Constants::YEEKEE
            ];
        }
        if (isset($type)) {
            if ($type !== 'YEEKEE') {
                $thaiSharedGameChitFinishes = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                    'userId' => $userId,
                    'gameId' => $gameId,
                ])->andWhere([
                    ThaiSharedGameChit::tableName() . '.status' => Constants::status_finish_show_result
                ])->orderBy('createdAt DESC')->all();
                $thaiSharedGameChitFinishesTodays = ThaiSharedGameChit::find()->where(['userId' => $userId])->andWhere([
                    'status' => Constants::status_finish_show_result
                ])->andWhere([
                    '=',
                    'DATE(' . ThaiSharedGameChit::tableName() . '.createdAt)',
                    $now
                ])->all();
                $thaiSharedGameChitHistories = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                    'userId' => $userId,
                    'gameId' => $gameId,
                ])->andWhere([
                    '<>',
                    ThaiSharedGameChit::tableName() . '.status',
                    Constants::status_playing
                ])->andWhere([
                    '<=',
                    'DATE(' . ThaiSharedGameChit::tableName() . '.createdAt)',
                    $now
                ])->orderBy('createdAt DESC')->all();
                $thaiSharedGameChitTodaies = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                    'userId' => $userId,
                    'gameId' => $gameId,
                ])->andWhere([
                    'DATE(' . ThaiSharedGameChit::tableName() . '.createdAt)' => $now
                ])->orderBy('createdAt DESC')->all();
                $thaiSharedGameChitWaites = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                    'userId' => $userId,
                    'gameId' => $gameId,
                ])->andWhere([
                    ThaiSharedGameChit::tableName() . '.status' => Constants::status_playing,
                ])->orderBy('createdAt DESC')->all();
            } else {
                ////
                $yeekeeGameChitFinishes = YeekeeChitSearch::find()->joinWith('yeekee')->where([
                    'user_id' => $userId,
                ])->andWhere([
                    YeekeeChitSearch::tableName() . '.status' => Constants::status_finish_show_result
                ])->orderBy('create_at DESC')->all();
                $yeekeeGameChitFinishesTodays = YeekeeChitSearch::find()->where(['user_id' => $userId])->andWhere([
                    'status' => Constants::status_finish_show_result
                ])->andWhere([
                    '=',
                    'DATE(' . YeekeeChitSearch::tableName() . '.create_at)',
                    $now
                ])->all();
                $yeekeeGameChitHistories = YeekeeChitSearch::find()->joinWith('yeekee')->where([
                    'user_id' => $userId,
                ])->andWhere([
                    '<>',
                    YeekeeChitSearch::tableName() . '.status',
                    Constants::status_playing
                ])->andWhere([
                    '<=',
                    'DATE(' . YeekeeChitSearch::tableName() . '.create_at)',
                    $now
                ])->orderBy('create_at DESC')->all();
                $yeekeeGameChitTodaies = YeekeeChitSearch::find()->joinWith('yeekee')->where([
                    'user_id' => $userId,
                ])->andWhere([
                    'DATE(' . YeekeeChitSearch::tableName() . '.create_at)' => $now
                ])->orderBy('create_at DESC')->all();
                $yeekeeGameChitWaites = YeekeeChitSearch::find()->joinWith('yeekee')->where([
                    'user_id' => $userId,
                ])->andWhere([
                    YeekeeChitSearch::tableName() . '.status' => Constants::status_playing,
                ])->orderBy('create_at DESC')->all();
            }
        } else {
            $thaiSharedGameChitFinishes = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                'userId' => $userId,
                'gameId' => $gameId,
            ])->andWhere([
                ThaiSharedGameChit::tableName() . '.status' => Constants::status_finish_show_result,
            ])->orderBy(ThaiSharedGameChit::tableName().'.createdAt DESC')->all();
            $thaiSharedGameChitFinishesTodays = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                'userId' => $userId,
                'gameId' => $gameId,
            ])->andWhere([
                ThaiSharedGameChit::tableName() . '.status' => Constants::status_finish_show_result,
            ])->andWhere([
                '=',
                'DATE(' . ThaiSharedGameChit::tableName() . '.createdAt)',
                $now
            ])->all();
            $thaiSharedGameChitHistories = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                'userId' => $userId,
                'gameId' => $gameId,
            ])->andWhere([
                '<>',
                ThaiSharedGameChit::tableName() . '.status',
                Constants::status_playing
            ])->andWhere([
                '<=',
                'DATE('.ThaiSharedGameChit::tableName().'.createdAt)',
                $now
            ])->orderBy(ThaiSharedGameChit::tableName().'.createdAt DESC')->all();
            $thaiSharedGameChitTodaies = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                'userId' => $userId,
                'gameId' => $gameId,
            ])->andWhere([
                'DATE('.ThaiSharedGameChit::tableName().'.createdAt)' => $now
            ])->orderBy(ThaiSharedGameChit::tableName().'.createdAt DESC')->all();
            $thaiSharedGameChitWaites = ThaiSharedGameChit::find()->joinWith('thaiSharedGame')->where([
                'userId' => $userId,
                'gameId' => $gameId,
            ])->andWhere([
                ThaiSharedGameChit::tableName() . '.status' => Constants::status_playing,
            ])->orderBy(ThaiSharedGameChit::tableName().'.createdAt DESC')->all();

            ////
            $yeekeeGameChitFinishes = YeekeeChitSearch::find()->where([
                'user_id' => $userId,
            ])->andWhere([
                'status' => Constants::status_finish_show_result,
            ])->orderBy('create_at DESC')->all();
            $yeekeeGameChitFinishesTodays = YeekeeChitSearch::find()->where([
                'user_id' => $userId,
            ])->andWhere([
                'status' => Constants::status_finish_show_result,
            ])->andWhere([
                '=',
                'DATE(' . YeekeeChitSearch::tableName() . '.create_at)',
                $now
            ])->all();
            $yeekeeGameChitHistories = YeekeeChitSearch::find()->where([
                'user_id' => $userId,
            ])->andWhere([
                '<>',
                'status',
                Constants::status_playing
            ])->andWhere([
                '<=',
                'DATE(create_at)',
                $now
            ])->orderBy('create_at DESC')->all();
            $yeekeeGameChitTodaies = YeekeeChitSearch::find()->where([
                'user_id' => $userId,
            ])->andWhere([
                'DATE(create_at)' => $now
            ])->orderBy('create_at DESC')->all();
            $yeekeeGameChitWaites = YeekeeChitSearch::find()->where([
                'user_id' => $userId,
            ])->andWhere([
                'status' => Constants::status_playing,
            ])->orderBy('create_at DESC')->all();
        }

        return $this->render('index', [
            'thaiSharedGameChitFinishes' => isset($thaiSharedGameChitFinishes) ? $thaiSharedGameChitFinishes : [],
            'thaiSharedGameChitHistories' => isset($thaiSharedGameChitHistories) ? $thaiSharedGameChitHistories : [],
            'thaiSharedGameChitTodaies' => isset($thaiSharedGameChitTodaies) ? $thaiSharedGameChitTodaies : [],
            'thaiSharedGameChitWaites' => isset($thaiSharedGameChitWaites) ? $thaiSharedGameChitWaites : [],
            'thaiSharedGameChitFinishesTodays' => isset($thaiSharedGameChitFinishesTodays) ? $thaiSharedGameChitFinishesTodays : [],
            'yeekeeGameChitFinishes' => isset($yeekeeGameChitFinishes) ? $yeekeeGameChitFinishes : [],
            'yeekeeGameChitFinishesTodays' => isset($yeekeeGameChitFinishesTodays) ? $yeekeeGameChitFinishesTodays : [],
            'yeekeeGameChitHistories' => isset($yeekeeGameChitHistories) ? $yeekeeGameChitHistories : [],
            'yeekeeGameChitTodaies' => isset($yeekeeGameChitTodaies) ? $yeekeeGameChitTodaies : [],
            'yeekeeGameChitWaites' => isset($yeekeeGameChitWaites) ? $yeekeeGameChitWaites : [],
        ]);
    }

    /**
     * Displays a single LotteryGameChit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ErrorException
     */
    public function actionDetail($id)
    {
        $userId = Yii::$app->user->id;
        $thaiSharedGameChit = $this->findModel($id, $userId);
        return $this->render('detail', [
            'thaiSharedGameChit' => $thaiSharedGameChit,
        ]);
    }

    /**
     * Finds the LotteryGameChit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ThaiSharedGameChit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $userId)
    {
        if (($model = ThaiSharedGameChit::find()->where(['id' => $id, 'userId' => $userId])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
