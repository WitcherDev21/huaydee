<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\libs\Constants;


class YeekeeChitSearch extends YeekeeChit
{
	public $date_at;
   	public $round;
   	public $from;
   	public $page;
   	public $group_by;
   	const CURRENT = 'curent';
   	const HISTORY = 'history';
   	
    public function rules()
    {
        return [
            [['user_id', 'yeekee_id', 'create_by', 'update_by', 'status'], 'integer'],
            [['total_amount'], 'number'],
            [['create_at', 'update_at','from','page'], 'safe'],
        	//yeekee
        	[['date_at'],'safe']
        ];
    }

    public function search($params)
    {
    	$user_id = \Yii::$app->user->identity->id;
    	
    	$query = $this->find();
    	$query->joinWith(['yeekee']);
    	
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'pagination' => [
						'pageSize' => 20
				],
				'sort' => [ 
						'defaultOrder' => [ 
								'create_at' => SORT_DESC 
						] 
				],
    	]);
    	
    	$this->load($params);
    	if (!$this->validate()) {
    		return $dataProvider;
    	}
        
        
        if(!empty($this->group_by)){
            $query->groupBy($this->group_by);
        }
		
//     	if(!empty($this->create_at)){
//     		$query->andFilterWhere([
//     				'between','yeekee.create_at',date("Y-m-d 00:00:00",strtotime($this->create_at)),date("Y-m-d 23:59:59",strtotime($this->create_at))
//     		]);
//     	}else {
//     		$query->andFilterWhere([
//     				'<','yeekee.create_at',date("Y-m-d 00:00:00",time())
//     		]);
//     	}
    	if(!empty($this->from)){
    		if($this->from == 'frontend'){
    			$query->andFilterWhere(['user_id'=>$user_id]);
    		}
    	}else{
    	    if(!empty($this->date_at)){
    	        $query->andFilterWhere(['=','yeekee.date_at',$this->date_at]);
    	    }
    	}
    	if($this->page == self::CURRENT){
    		$query->andFilterWhere([
    				'=','yeekee.date_at',date("Y-m-d",strtotime($this->date_at))
    		]);
    	}else if($this->page == self::HISTORY){
    	    if ($this->date_at) {
                $query->andFilterWhere([
                    '=', 'yeekee.date_at', date("Y-m-d", strtotime($this->date_at))
                ]);
            }
    	}

        
    	if(!empty($this->round)){
    		$query->andFilterWhere(['yeekee.round' => $this->round]);
    	}
    	if(!empty($this->user_id)){
    		$query->andFilterWhere(['user_id' => $this->user_id]);
    	}
    	return $dataProvider;
    }
    
    public function getOrderId(){
    	return strtotime($this->create_at) + $this->id;
    }

    public function getOrderCommissionId(){
        return $this->yeekee_id;
    }
    
    public function getAgentProfile($id)
    {
        return User::find()->where(['id'=>$id])->one();
    }
   
    
    public function getCanReChit(){
    	
    	$now = date('Y-m-d H:i:s',time());
		$endTime = strtotime("-3 days", strtotime($now));
    	//$endTime = strtotime("+2 minutes", strtotime($now));
    	//$endTime = strtotime($now);
    	if(strtotime($this->yeekee->finish_at) > $endTime && $this->status == Constants::status_playing || $this->status == Constants::status_finish_show_result){
    		return true;
    	}else{
    		return false;
    	}
    }
    public static function getCreditCanPay($playData = [],$user_id, $reason = 0){
    	$payAmount = 0;
    	$is_pass = false; //ขอให้มีการซื้อมาจริงๆ
    	foreach($playData as $type => $data2){
    	    $playType = PlayType::find()->where(['code' => $type, 'game_id' => Constants::YEEKEE])->one();
    	    $maximumPlay = $playType->maximum_play;
    	    $minimumPlay = $playType->minimum_play;
    		$numSet = json_decode($data2);
    		foreach($numSet as $number=>$amount){
    		    if ($amount > $maximumPlay) {
    		        $amount = $maximumPlay;
                }elseif ($amount < $minimumPlay) {
    		        $amount = $minimumPlay;
                }
    			$payAmount += $amount;
    			$is_pass = true;
    		}
    	}
    	$creditBalance = CreditSearch::find()->select(['balance'])->where(['user_id'=>$user_id])->one();
    	if(empty($creditBalance)){
    	    if($reason == 1){
    	        return ['result'=>false,'reason'=>'ไม่มีข้อมูลเครดิต '];
    	    }
    		return false;
    	}
    	//หายอดที่จะขอถอนที่ยังไม่อนุมัติ
    	$sumWaittingPostWithDraw = PostCreditTransectionSearch::find()
    	->where(['poster_id'=>$user_id,'action_id'=>Constants::action_credit_withdraw,'status'=>Constants::status_waitting])
    	->sum('amount');
    	$realCredit = ($creditBalance->balance - $sumWaittingPostWithDraw);
    	//จ่ายมากกว่ามี
    	if(($payAmount>$realCredit) && $is_pass){
    	    if($reason == 1){
    	        return ['result'=>false,'reason'=>'จำนวนเครดิตของคุณไม่เพียงพอ หรืออาจมีการแจ้งถอนที่รออนุมัติอยู่'];
    	    }
    	    return false;
    	}else {
    	    if($reason == 1){
    	        return ['result'=>true,'reason'=>'success '];
    	    }
    	    return true;
    	}
    }
    public function getIsWin(){
    	$models = $this->yeekeeChitDetails;
    	$result = 0;
    	foreach($models as $model){
    		if($model->flag_result == 1){
    			$result = 1;
    		}
    	}
    	return $result;
    }
    
    public function getTotalWinCredit(){
    	$models = $this->yeekeeChitDetails;
    	$total_win_credit = 0;
    	foreach($models as $model){
    		$total_win_credit += $model->win_credit;
    	}
    	return $total_win_credit;
    }
    
    public function getAgentYeekeeChit($user_id)
    {
        $total = 0;
        $UserId = User::find()->select(['id'])->where(['agent'=>$user_id])->asArray()->all();
        if(!empty($UserId)){
            $arrId = array();
            foreach ($UserId as $id){
                $arrId[] = $id['id'];
            }
            $yeekeeChitTotal = YeekeeChit::find()->where(['user_id' => $arrId])->andWhere(['<>', 'status', Constants::status_cancel])->sum('total_amount');
            $thaiSharedGameChit = ThaiSharedGameChit::find()->select('SUM(CASE WHEN totalDiscount > 0 THEN totalDiscount ELSE totalAmount END) AS totalAmount')->where(['userId' => $arrId])->andWhere(['<>', 'status', Constants::status_cancel])->one();
            $total = $yeekeeChitTotal + $thaiSharedGameChit->totalAmount;
        }
        return $total;
    }
    
    public function getAgentYeekeeChitAll($user_id)
    {
        $totalCommissionTopup = CommissionTransection::find()->where([
            'action_id' => Constants::action_commission_top_up,
            'reason_action_id' => Constants::reason_credit_top_up,
            'reciver_id' => $user_id
        ])->sum('amount');
        $totalCommissionCancel = CommissionTransection::find()->where([
            'action_id' => Constants::action_commission_top_up,
            'reason_action_id' => Constants::reason_credit_cancel,
            'reciver_id' => $user_id
        ])->sum('amount');

        //totle income
        $totalIncome = $totalCommissionTopup - $totalCommissionCancel;
        return $totalIncome;
    }
}
