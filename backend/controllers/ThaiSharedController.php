<?php
namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\Credit;
use common\models\Games;
use common\models\ThaiSharedGame;
use common\models\ThaiSharedGameChit;;
use common\models\ThaiSharedGameSearch;
use common\models\TypeGameShared;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 10/15/2018
 * Time: 8:46 AM
 */

class ThaiSharedController extends Controller
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
                    'cancel' => ['post'],
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

    public function actionIndex()
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('thai-shared-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการดูรายการหวยหุ้น');
        }
        $searchModel = new ThaiSharedGameSearch();
        $typeGameSharedObj = TypeGameShared::find()->all();
        $typeGameShareds = ArrayHelper::map($typeGameSharedObj, 'id', 'title');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->defaultPageSize = 26;
        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'typeGameShareds' => $typeGameShareds,
            'arrRoles' => $arrRoles,
        ]);
    }

    public function actionCreate()
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('create-shared-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการสร้างหวยหุ้น');
        }
        $model = new ThaiSharedGame();
        $model->disabled = 1;
        $model->setScenario('create');
        $gameObj = Games::find()->where(['like', 'title', 'หวย'])->all();
        $games = ArrayHelper::map($gameObj, 'id', 'title');
        $typeGameSharedObj = TypeGameShared::find()->where(['status' => 1])->all();
        $typeGameShareds = ArrayHelper::map($typeGameSharedObj, 'id', 'title');
        $model->limitAuto = 1;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->createdBy = Yii::$app->user->id;
            if (!$model->save()) {
                throw new ServerErrorHttpException('Can not save');
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'games' => $games,
            'typeGameShareds' => $typeGameShareds
        ]);
    }

    public function actionUpdate($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('update-shared-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการแก้ไขหวยหุ้น');
        }
        $model = $this->findModel($id);
        $gameObj = Games::find()->where(['like', 'title', 'หวย'])->all();
        $games = ArrayHelper::map($gameObj, 'id', 'title');
        $typeGameSharedObj = TypeGameShared::find()->where(['status' => 1])->all();
        $typeGameShareds = ArrayHelper::map($typeGameSharedObj, 'id', 'title');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->updatedBy = Yii::$app->user->id;
            if (!$model->save()) {
                throw new ServerErrorHttpException('Can not update');
            }
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
            'games' => $games,
            'typeGameShareds' => $typeGameShareds
        ]);
    }

    public function actionCopy($id)
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('create-shared-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการสร้างหวยหุ้น');
        }
        $model = $this->findModel($id);
        $gameObj = Games::find()->where(['like', 'title', 'หวย'])->all();
        $games = ArrayHelper::map($gameObj, 'id', 'title');
        $typeGameSharedObj = TypeGameShared::find()->where(['status' => 1])->all();
        $typeGameShareds = ArrayHelper::map($typeGameSharedObj, 'id', 'title');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $thaiSharedGame = new ThaiSharedGame(
                $model->getAttributes() // get all attributes and copy them to the new instance
            );
            $thaiSharedGame->id = null;
            $thaiSharedGame->save();
            if (!$thaiSharedGame->save()) {
                throw new ServerErrorHttpException('Can not copy game');
            }
            return $this->redirect(['index']);
        }
        return $this->render('copy', [
            'model' => $model,
            'games' => $games,
            'typeGameShareds' => $typeGameShareds
        ]);
    }

    public function actionCancel($id)
    {
        $thaiSharedGame = $this->findModel($id);
        if (!$thaiSharedGame->status === 1) {
            throw new ServerErrorHttpException('Can not cancel thai sharedgame');
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $thaiSharedGame->status = Constants::status_cancel;
            if (!$thaiSharedGame->save()) {
                throw new ServerErrorHttpException('Can not save thaiSharedGame');
            }
            $thaiSharedGameChits = ThaiSharedGameChit::find()->where(['thaiSharedGameId' => $id])->all();
            foreach ($thaiSharedGameChits as $thaiSharedGameChit) {
                $remark = 'คืนโพย ' . $thaiSharedGame->title . ' / ' . date('d/m/Y') . ' #' . $thaiSharedGameChit->getOrder();
                $resutl = Credit::creditWalk(Constants::action_credit_top_up, $thaiSharedGameChit->userId, Yii::$app->user->id, Constants::reason_credit_return_chit, $thaiSharedGameChit->totalAmount, $remark);
                $thaiSharedGameChit->status = Constants::status_cancel;
                $thaiSharedGameChit->updatedAt = date('Y-m-d H:i:s');
                $thaiSharedGameChit->updatedBy = Yii::$app->user->id;
                $thaiSharedGameChit->totalAmount = 0;
                if (!$thaiSharedGameChit->save()) {
                    throw new ServerErrorHttpException('Can not save Thai Shared Game Chit');
                }
            }
            $transaction->commit();
            return $this->redirect(['index']);
        }catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionPreviewList()
    {
        $identity = Yii::$app->user->getIdentity();
        $modelAuthRoles = new AuthRoles();
        $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        if(!in_array('thai-shared-game', $arrRoles)){
            throw new ServerErrorHttpException('คุณไม่มีสิทธิ์ในการดูรายการหวยหุ้น');
        }
        $searchModel = new ThaiSharedGameSearch();
        $typeGameSharedObj = TypeGameShared::find()->all();
        $typeGameShareds = ArrayHelper::map($typeGameSharedObj, 'id', 'title');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->defaultPageSize = 26;
        return $this->render('preview-list',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'typeGameShareds' => $typeGameShareds,
            'arrRoles' => $arrRoles,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = ThaiSharedGame::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}