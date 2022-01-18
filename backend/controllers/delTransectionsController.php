<?php
namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Site controller
 */
class delTransectionsController extends Controller
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
        ];
    }
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionMasterrefill()
    {
        return $this->render('master-refill');
    }
    
    public function actionMemberrefill()
    {
    	return $this->render('member-refill');
    }
    
    public function actionAccountrefill()
    {
    	return $this->render('account-refill');
    }
   
}
