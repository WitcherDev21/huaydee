<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\libs\Constants;


class YeekeeSearch extends Yeekee
{
	public $three_top;
	public $two_under;
	public $date_at_search;
	
    public function rules()
    {
        return [
        	[['three_top','two_under','date_at_search'],'safe']
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

    public function searchManageResult($params)
    {
        $running = Running::find()->where(['game_id' => Constants::YEEKEE])->orderBy(['running' => SORT_DESC])->one();

        if (isset($params['YeekeeSearch']['date_at_search'])) {
            $query = $this->find()->where([
                'date_at' => $params['YeekeeSearch']['date_at_search']
            ]);
        }else {
            $query = $this->find()->where([
                'group' => $running->running
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 88
            ],
            'sort' => [
                'defaultOrder' => [
                    'round' => SORT_ASC,
                ]
            ],
        ]);

        return $dataProvider;
    }

    
    public function getOrderId(){
        return strtotime($this->create_at) + $this->id;
    }
    /**
     * 
     * @param string $type  three_top, three_under, two_top, two_under 
     */
    public function getResults($type = ''){
    	$lenght = strlen($this->result);
    	$result = substr(''.$this->result,$lenght-5,$lenght);
    
    	//สามตัวบน : สามตัว นับจากขวา  xx???
    	if($type == 'three_top'){    		   		
    		$result = substr($result, 2, 3);
    		
    	//สามตัว โต๊ด : สามตัว ขวาสลับกันได้ xx???
    	}else if($type == 'three_tod'){
    		$result = substr($result, 2, 3);
    		$arr_num = str_split($result);    		
    		$n = 0;
    		$arr_swap_num = [];
    		for($i = 0; $i< count($arr_num); $i ++){
    			$tmp = $arr_num[$i];
    			for($j = 0; $j < count($arr_num); $j ++){
    				if($i!=$j){
    					$tmp .= ''.$arr_num[$j];
    				}
    			}
    			if(!in_array($tmp, $arr_swap_num)){
    				$arr_swap_num[$n++] = $tmp;
    			}

    			$tmp = $arr_num[$i];
    			for($j = (count($arr_num) - 1); $j >= 0; $j --){
    				if($i!=$j){
    					$tmp .= ''.$arr_num[$j];
    				}
    			}
    			if(!in_array($tmp, $arr_swap_num)){
    				$arr_swap_num[$n++] = $tmp;
    			}
    		}
    		return $arr_swap_num;
    	//สองตัวบน : สองตัว นับจากขวา xxx??
    	}else if($type == 'two_top'){
    		$result = substr($result, 3, 2);
    		
    	//สองตัวล่าง : สองตัว นับจากซ้าย ??xxx
    	}else if($type == 'two_under'){
    		$result = substr($result, 0, 2);
    		
    	//วิ่งบน : มีหนึ่งใน สามตัวขวา
    	}else if($type == 'run_top'){
    		$result = substr($result, 2, 3);
    		$result = str_split($result);
    		
    	//วิ่งล่าง : มีหนึ่งใน สองตัวซ้าย
    	}else if($type == 'run_under'){
    		$result = substr($result, 0, 2);
    		$result = str_split($result);
    		
    	//อื่นๆ 
    	}else if($type == 'other'){    	
    		$result = substr(''.$this->result,0,$lenght-5);
    	}
    	
    	return $result;
    }
    
  	public static function getResultsPay($yeekeeChiteAll,$resultYeekee){
  		$result = [];
  		$income = 0;
  		$outlay = 0;
  		$sumPlayType = [];
  		$final_result = [];
  		//หาเลข สามตัวหลัง
  		
  		foreach($yeekeeChiteAll as $yeekeeChit){
  			$detailInChit = $yeekeeChit->yeekeeChitDetails;
  			foreach ($detailInChit as $detailSearch){
  				$income += $detailSearch->amount;
				$pay = YeekeeChitDetailSearch::getWinCreditStatic($resultYeekee,$detailSearch->play_type_code, $detailSearch->number,$detailSearch->amount);
  				$outlay += $pay; 
  			}
  		
  		}
  		$result = ['if_result_number'=>$resultYeekee,'outlay'=>$outlay];
  		return $result;
  		
  	}
    public static function getResultsIsLowCost($yeekeeChitAll){
    	$result = [];
    	$income = 0;
    	//$outlay = 0;
    	$sumPlayType = [];
    	$final_result = [];
    	$compare_lower = 0;
    	//หาเลข สามตัวหลัง
		
		$arrIndex = [];
		$numRand = [];
		for($i=99000;$i<=99999;$i++){			
			do{
				$index = rand(0,999);
			}while(in_array($index,$arrIndex));
			$arrIndex[] = $index;
			$numRand[$index] = $i;
		}
		
    	for($i=0;$i<count($numRand);$i++){
    		$outlay = 0;
    		$resultYeekee = $numRand[$i];    		
    		
	    	foreach($yeekeeChitAll as $yeekeeChit){
	    		$detailInChit = $yeekeeChit->yeekeeChitDetails;
	    		foreach ($detailInChit as $detailSearch){
	    			$income += $detailSearch->amount;
	    			
	    			if(in_array($detailSearch->play_type_code, ['two_top','run_top','three_top'])){
	    				$pay = YeekeeChitDetailSearch::getWinCreditStatic($resultYeekee,$detailSearch->play_type_code, $detailSearch->number,$detailSearch->amount);
	    				$outlay += $pay;
	    			}
	    		}
	    	
	    	}
	    	if($compare_lower == 0){
	    		$compare_lower = $outlay;
	    		$result = ['if_result_number'=>$resultYeekee,'outlay'=>$outlay];
	    	}
	    	if($outlay<$compare_lower){
	    		$compare_lower = $outlay;
	    		$result = ['if_result_number'=>$resultYeekee,'outlay'=>$outlay];
	    	}
	    	
    	}

    	
    	$final_result['three_right'] = substr(''.$result['if_result_number'],2,3);
    	//หาเลข 2 ตัวหน้า
    	$result = [];
    	$compare_lower = 0;
    	for($i=100000;$i<199000;$i+=1000){
    		$outlay = 0;
    		$resultYeekee = $i;
    		
    		foreach($yeekeeChitAll as $yeekeeChit){
    			$detailInChit = $yeekeeChit->yeekeeChitDetails;
    			foreach ($detailInChit as $detailSearch){
    				$income += $detailSearch->amount;
    	
    				if(in_array($detailSearch->play_type_code, ['two_under','run_under','three_tod'])){
    					$pay = YeekeeChitDetailSearch::getWinCreditStatic($resultYeekee,$detailSearch->play_type_code, $detailSearch->number,$detailSearch->amount);
    					$outlay += $pay;
    				}
    			}
    	
    		}
    		if($compare_lower == 0){
    			$compare_lower = $outlay;
    			$result = ['if_result_number'=>$resultYeekee,'outlay'=>$outlay];
    		}
    		if($outlay<$compare_lower){
    			$compare_lower = $outlay;
    			$result = ['if_result_number'=>$resultYeekee,'outlay'=>$outlay];
    		}
    	
    	}
    	$final_result['two_left'] = substr(''.$result['if_result_number'],1,2);
    	
    	$numberIsGodd = $final_result['two_left'].''.$final_result['three_right'];
    	
    	return $numberIsGodd;
    }
    
}
