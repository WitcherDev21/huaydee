<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use common\models\LimitLotteryByGamePlayTypeSet;
use common\models\LimitLotteryByGamePlayTypeSetSearch;
use common\models\Model;
use common\models\PlayType;
use Yii;
use common\models\LimitLotteryByGamePlayType;
use common\models\LimitLotteryByGamePlayTypeSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * LimitLotteryByGamePlayTypeController implements the CRUD actions for LimitLotteryByGamePlayType model.
 */
class LimitLotteryByGamePlayTypeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $identity = Yii::$app->user->getIdentity();
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }else{
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if(!in_array('members', $arrRoles)){
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
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

    /**
     * Lists all LimitLotteryByGamePlayType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LimitLotteryByGamePlayTypeSetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $playTypes = PlayType::find()->where(['game_id' => [
            Constants::LOTTERYGAME,
            Constants::LOTTERYGAMEDISCOUNT,
            Constants::THAISHARED,
            Constants::LOTTERYLAOGAME,
            Constants::LOTTERYLAODISCOUNTGAME]
        ])->joinWith('game')->all();
        $gameObjs = ArrayHelper::map($playTypes, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gameObjs' => $gameObjs,
        ]);
    }

    /**
     * Displays a single LimitLotteryByGamePlayType model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $limitLotteryGamePlayTypeSets = LimitLotteryByGamePlayType::find()->where(['limitLotteryGamePlayTypeSetId' => $model->id])->all();
        return $this->render('view', [
            'model' => $model,
            'limitLotteryGamePlayTypeSets' => $limitLotteryGamePlayTypeSets,
        ]);
    }

    /**
     * Creates a new LimitLotteryByGamePlayType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LimitLotteryByGamePlayTypeSet;
        $limitLotteryByGamePlayTypes = [new LimitLotteryByGamePlayType];
        $playTypes = PlayType::find()->where(['game_id' => [
            Constants::LOTTERYGAME,
            Constants::LOTTERYGAMEDISCOUNT,
            Constants::THAISHARED,
            Constants::LOTTERYLAOGAME,
            Constants::LOTTERYLAODISCOUNTGAME]
        ])->joinWith('game')->all();
        $gameObjs = ArrayHelper::map($playTypes, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });

        if ($model->load(Yii::$app->request->post())) {

            $limitLotteryByGamePlayTypes = Model::createMultiple(LimitLotteryByGamePlayType::classname());
            Model::loadMultiple($limitLotteryByGamePlayTypes, Yii::$app->request->post());

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($limitLotteryByGamePlayTypes) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $model->save()) {
                        foreach ($limitLotteryByGamePlayTypes as $key => $limitLotteryByGamePlayTypes) {
                            $limitLotteryByGamePlayTypes->level = (int)$key + 1;
                            $limitLotteryByGamePlayTypes->limitLotteryGamePlayTypeSetId = $model->id;
                            if (! ($flag = $limitLotteryByGamePlayTypes->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'limitLotteryByGamePlayTypes' => (empty($limitLotteryByGamePlayTypes)) ? [new LimitLotteryByGamePlayType()] : $limitLotteryByGamePlayTypes,
            'gameObjs' => $gameObjs,
        ]);
    }

    /**
     * Updates an existing LimitLotteryByGamePlayType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $limitLotteryByGamePlayTypes = $model->limitLotteryByGamePlayTypes;
        $playTypes = PlayType::find()->where(['game_id' => [
            Constants::LOTTERYGAME,
            Constants::LOTTERYGAMEDISCOUNT,
            Constants::THAISHARED,
            Constants::LOTTERYLAOGAME,
            Constants::LOTTERYLAODISCOUNTGAME]
        ])->joinWith('game')->all();
        $gameObjs = ArrayHelper::map($playTypes, 'id', function($model) {
            return $model['game']['title'].'-'.$model['title'];
        });

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($limitLotteryByGamePlayTypes, 'id', 'id');
            $limitLotteryByGamePlayTypes = Model::createMultiple(LimitLotteryByGamePlayType::classname(), $limitLotteryByGamePlayTypes);
            Model::loadMultiple($limitLotteryByGamePlayTypes, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($limitLotteryByGamePlayTypes, 'id', 'id')));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($limitLotteryByGamePlayTypes),
                    ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($limitLotteryByGamePlayTypes) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save()) {
                        if (! empty($deletedIDs)) {
                            LimitLotteryByGamePlayType::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($limitLotteryByGamePlayTypes as $key => $limitLotteryByGamePlayTypes) {
                            $limitLotteryByGamePlayTypes->level = (int)$key + 1;
                            $limitLotteryByGamePlayTypes->limitLotteryGamePlayTypeSetId = $model->id;
                            if (!($flag = $limitLotteryByGamePlayTypes->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'limitLotteryByGamePlayTypes' => (empty($limitLotteryByGamePlayTypes)) ? [new LimitLotteryByGamePlayType()] : $limitLotteryByGamePlayTypes,
            'gameObjs' => $gameObjs,
        ]);
    }

    public function actionEnabled($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->status === 1 ? 0 : 1;
        $model->save();
        $limitLotteryGamePlayTypeSets = LimitLotteryByGamePlayType::find()->where(['limitLotteryGamePlayTypeSetId' => $model->id])->all();
        return $this->redirect('index');
    }

    /**
     * Finds the LimitLotteryByGamePlayType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LimitLotteryByGamePlayType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LimitLotteryByGamePlayTypeSet::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
