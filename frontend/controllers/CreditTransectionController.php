<?php

namespace frontend\controllers;

use Yii;
use common\models\CreditTransection;
use common\models\CreditTransectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CreditTransectionController implements the CRUD actions for CreditTransection model.
 */
class CreditTransectionController extends Controller
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
     * Lists all CreditTransection models.
     * @return mixed
     */
    public function actionListCurrent()
    {
    	$userId = \Yii::$app->user->id;
    	$date = date('Y-m-d');
    	$creditTransections = CreditTransectionSearch::find()->where([
    	    'DATE(create_at)' => date('Y-m-d'),
            'reciver_id' => $userId
        ])->andWhere([
            'between',
            'create_at',
            date("Y-m-d 00:00:00", strtotime($date)),
            date("Y-m-d 23:59:59", strtotime($date))
        ])->orderBy('id DESC')->all();

    	$creditTransectionHistorys = CreditTransectionSearch::find()->where([
            '<','create_at',date("Y-m-d 00:00:00",time())
        ])->andWhere(['reciver_id' => $userId])->orderBy('id DESC')->all();

    	return $this->render('_list', [
    	    'creditTransections' => $creditTransections,
            'creditTransectionHistorys' => $creditTransectionHistorys,
    	]);
    }
}
