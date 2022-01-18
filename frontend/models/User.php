<?php
namespace frontend\models;

use dektrium\user\models\User as BaseUser;
use Yii;

class User extends BaseUser
{
	public $tel;
	
	public function rules()
	{
		$rules = array_merge(parent::rules, [
				[['tel'], 'required'],
								
		]);
		
		return $rules;
	}
	
	
}