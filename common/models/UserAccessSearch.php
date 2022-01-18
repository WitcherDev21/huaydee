<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\libs\Constants;


class UserAccessSearch extends UserAccess
{
    public $username;
    public $user_status;
    public $authRole;
	
    public function rules()
    {
        return [
        	[['username','user_status','authRole', 'ip_address'],'safe']
        ];
    }

    public function search($params)
    {
    	
    	$query = $this->find()
            ->joinWith(['user', 'user.authRole']);
    	
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'pagination' => [
						'pageSize' => 20
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'id' => SORT_DESC 
						] 
				],
    	]);
        
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'ip_address', $this->ip_address]);
        $query->andFilterWhere(['like', 'user.username', $this->username]);
        $query->andFilterWhere(['user_status'=>$this->user_status]);
        $query->andFilterWhere([User::tableName() . '.auth_roles_id' => $this->authRole]);
    	
    	return $dataProvider;
    }
    
    public function save_access()
    {
        $model = new UserAccess();
        $model->user_id = \Yii::$app->user->identity->id;
        $model->ip_address = \Yii::$app->request->userIP;
        $model->access_at = date('Y-m-d H:i:s');
        $model->save();
    }
    
}
