<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\UserSearch;

/**
 * Site controller
 */
class RecommendController extends Controller
{
    
    /**
     * {@inheritdoc}
     */
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->searchRecommend(Yii::$app->request->get());
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionBlackred()
    {
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->searchRecommendBlackred(Yii::$app->request->get());
        return $this->render('blackred', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }

    
}
