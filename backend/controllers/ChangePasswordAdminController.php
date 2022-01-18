<?php

namespace backend\controllers;

use common\models\User;
use common\libs\Constants;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Created by PhpStorm.
 * User: topte
 * Date: 11/5/2018
 * Time: 9:01 PM
 */

class ChangePasswordAdminController extends Controller
{
	public function actionChangePassword($id, $pass)
    {
		$adminChange = User::find()->where(['id'=> $id])->one();
		$adminChange->setPassword($pass);
		$adminChange->generateAuthKey();
		if($adminChange->save()){
			return ['message' => 'Change Password Success'];
		}
	}

}