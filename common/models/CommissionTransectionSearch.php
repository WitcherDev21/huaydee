<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\libs\Constants;


class CommissionTransectionSearch extends CommissionTransection
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CreditTransection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => [
        			'pageSize' => 20
        	],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->orderBy(['create_at'=>SORT_DESC]);
        return $dataProvider;
    }
    
    public static function getIncomeInDay($date,$reciver_id)
    {
        if(empty($reciver_id)){
            return 0;
        }
        
        $time_active_day = strtotime($date);
        $start_active_day = date('Y-m-d 00:00:00',$time_active_day);
        $end_active_day = date('Y-m-d 23:59:59',$time_active_day);
        
        $query = CommissionTransectionSearch::find()
        ->where(['between','create_at',$start_active_day,$end_active_day])
        ->andWhere(['reciver_id'=>$reciver_id])
        ->andWhere(['action_id'=>Constants::action_commission_top_up]);
        
        $result = 0;
        foreach($query->all() as $model){
            $result += $model->amount;   
        }
        return $result;
    }

    public static function getIncomeBlackredInDay($date,$reciver_id)
    {
        if(empty($reciver_id)){
            return 0;
        }

        $time_active_day = strtotime($date);
        $start_active_day = date('Y-m-d 00:00:00',$time_active_day);
        $end_active_day = date('Y-m-d 23:59:59',$time_active_day);

        $query = CommissionTransectionBlackred::find()
            ->where(['between','create_at',$start_active_day,$end_active_day])
            ->andWhere(['reciver_id'=>$reciver_id])
            ->andWhere(['action_id'=>Constants::action_commission_top_up]);

        $result = 0;
        foreach($query->all() as $model){
            $result += $model->amount;
        }
        return $result;
    }

    public function getOrderId(){
        return strtotime($this->create_at) + $this->id;
    }
}
