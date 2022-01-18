<?php

namespace backend\controllers;

use common\libs\Constants;
use common\models\AuthRoles;
use Yii;
use common\models\SetupLevelPlaytype;
use common\models\SetupLevelPlaytypeSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SetupLevelPlaytypeController implements the CRUD actions for SetupLevelPlaytype model.
 */
class SetupLevelPlaytypeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $identity = \Yii::$app->user->getIdentity();
        if(empty($identity)){
            $this->layout = false;
            $this->redirect(Yii::$app->urlManager->createUrl(['user/login']));
        }else{
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            if(!in_array('setting', $arrRoles)){
                $this->layout = false;
                $this->redirect(Yii::$app->urlManager->createUrl(['user/logout']));
            }
        }
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
     * Lists all SetupLevelPlaytype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $gameId = $request->get('gameId', 0);
        if (!isset(Constants::SETUP_LEVEL_PLAYTYPE_GAMEID[$gameId])) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = [];
        foreach (Constants::SETUP_LEVEL_PLAYTYPE_CODE[$gameId] as $playTypeCode) {
            $setupLevelPlayTypes = SetupLevelPlaytype::find()->where([
                'gameId' => implode(',', Constants::SETUP_LEVEL_PLAYTYPE_GAMEID[$gameId]),
                'codePlayType' => $playTypeCode
            ])->asArray()->all();
            foreach ($setupLevelPlayTypes as $setupLevelPlayType) {
                $model[$playTypeCode]['minimumPlay'][] = $setupLevelPlayType['minimumPlay'];
                $model[$playTypeCode]['maximumPlay'][] = $setupLevelPlayType['maximumPlay'];
                $model[$playTypeCode]['jackPotPerUnit'][] = $setupLevelPlayType['jackPotPerUnit'];
            }
        }
        if ($request->isPost) {
            SetupLevelPlaytype::deleteAll(['gameId' => implode(',', Constants::SETUP_LEVEL_PLAYTYPE_GAMEID[$gameId])]);
            foreach ($request->post('minimumPlay') as $code => $minimumPlays) {
                foreach ($minimumPlays as $key => $minimumPlay) {
                    $model = new SetupLevelPlaytype();
                    $model->codePlayType = $code;
                    $model->minimumPlay = empty($minimumPlay) ? 0 : (int)$minimumPlay;
                    $model->maximumPlay = empty($request->post('maximumPlay')[$code][$key]) ? 0 : (int)$request->post('maximumPlay')[$code][$key];
                    $model->jackPotPerUnit = empty($request->post('jackPotPerUnit')[$code][$key]) ? null : (float)$request->post('jackPotPerUnit')[$code][$key];
                    $model->gameId = implode(',', Constants::SETUP_LEVEL_PLAYTYPE_GAMEID[$gameId]);
                    if (!$model->save()) {
                        var_dump($model->getErrors());
                        exit;
                    }
                }
            }
            return $this->redirect(['index']);
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
