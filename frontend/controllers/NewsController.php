<?php

namespace frontend\controllers;

use common\libs\Constants;
use common\models\News;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    /**
     * {@inheritdoc}
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
        $news = News::find()->where([
            'zone' => ['rule', 'res', 'recommend'],
            'status' => Constants::status_active,
        ])->all();
        return $this->render('index', [
            'news' => $news,
        ]);
    }

    public function actionDetail($id)
    {
        $news = $this->findModel($id);
        return $this->render('detail', [
            'news' => $news,
        ]);
    }

    public function actionHowTo()
    {
        return $this->render('how-to');
    }

    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}