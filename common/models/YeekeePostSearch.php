<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\libs\Constants;


class YeekeePostSearch extends Yeekee
{
	
    public function rules()
    {
        return [
        	
        ];
    }

    public function search($params)
    {
    	$user_id = \Yii::$app->user->identity;
    	
    	$query = $this->find();
    	
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'pagination' => [
						'pageSize' => 25 
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'create_at' => SORT_DESC 
						] 
				],
    	]);
    	
    	return $dataProvider;
    }
    
    public function getOrderId(){
    	return strtotime($this->create_at) + $this->id;
    }
   
}
