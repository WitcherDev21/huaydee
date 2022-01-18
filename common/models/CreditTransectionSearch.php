<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CreditTransection;
use common\libs\Constants;

/**
 * CreditTransectionSearch represents the model behind the search form of `common\models\CreditTransection`.
 */
class CreditTransectionSearch extends CreditTransection
{
    public $from;
    public $filter_no;
    public $filter_detail;
    public $filter_operator;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['remark'], 'string', 'max' => 255],
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
        $query = self::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => [
        			'pageSize' => 20
        	],
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
        
        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'action_id' => $this->action_id,
            'reason_action_id' => $this->reason_action_id,
            'operator_id' => $this->operator_id,
            'reciver_id' => $this->reciver_id,
            'amount' => $this->amount,
            'balance' => $this->balance,

            'create_by' => $this->create_by,
            'update_at' => $this->update_at,
            'update_by' => $this->update_by,
        ]);
        
        
        
        if(!empty($this->filter_detail)){

            $query->andFilterWhere([
                'id' => $this->id,
                'action_id' => $this->action_id,
                'reason_action_id' => $this->reason_action_id,
                'operator_id' => $this->operator_id,
                'reciver_id' => $this->reciver_id,
                'amount' => $this->amount,
                'balance' => $this->balance,

                'create_by' => $this->create_by,
                'update_at' => $this->update_at,
                'update_by' => $this->update_by,
            ]);
        }
        
        if($this->from == 'frontend'){
            $query->andFilterWhere([
                'reciver_id' => $this->reciver_id,
            ]);
            if(!empty($this->create_at)){
                $query->andFilterWhere([
                    'between','create_at',date("Y-m-d 00:00:00",strtotime($this->create_at)),date("Y-m-d 23:59:59",strtotime($this->create_at))
                ]);
            }else {
               	$query->andFilterWhere([
               			'<','create_at',date("Y-m-d 00:00:00",time())
               	]);
            }
        }
        
        if(!empty($this->create_at)){
        	$query->andFilterWhere([
        			'between','create_at',date("Y-m-d 00:00:00",strtotime($this->create_at)),date("Y-m-d 23:59:59",strtotime($this->create_at))
        	]);
        }
        if ($this->game) {
            $query->andFilterWhere(['like', 'remark', $this->game]);
        }

        $query->andFilterWhere(['like', 'remark', $this->remark]);
        $query->orderBy(['id'=>SORT_DESC]);
        return $dataProvider;
    }
    
    public function searchCreditMaster()
    {
        $query = self::find();
        $query->andFilterWhere([
            'action_id' => [Constants::action_credit_master_top_up,Constants::action_credit_master_withdraw],
        ]);
        $query->orderBy(['id'=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => [
                    'pageSize' => 20
        	],
        ]);
        return $dataProvider;
    }
    
    public static function getCanWithdrawInfo($user_id){
        if(empty($user_id)){
            return false;
        }   
        $result = [
            'can_withdraw'=>self::getCanWithdraw($user_id,1),
            'saving_amount'=>self::getSavingAmount($user_id),
            'bet_amount'=>self::getBetAmount($user_id),
            'post_withdraw_count'=>PostCreditTransectionSearch::getPostWithdrawInToday($user_id)
        ];
        return $result;
    }
    
    //ดูว่า สามารถ ถอนเครดิตได้มั้ย
    public static function getCanWithdraw($user_id, $reason = 0,$amount = 0)
    {
        if(empty($user_id)){
            return ['result'=>false,'reason'=>'เกิดข้อผิดพลาด user ไม่มีในระบบ'];
        }
        $countPostCredit = PostCreditTransectionSearch::getPostWithdrawInToday($user_id);
        //เครดิต เหลือน้อยกว่า ที่จะแจ้งถอน
        $currentCredit = CreditSearch::getCredit($user_id);
        if($currentCredit <= 0 ){
            if($reason == 1){
                return ['reason' => 'ยอดเงินเครดิตของท่านไม่เพียงพอ', 'credit' => true];
            }
        }
        
        //ตรวจสอบบ่อยๆ
        if($amount > 0){
            if($reason == 1){
                return ['reason' => 'เครดิต ที่จะแจ้งถอนมากกว่าเครดิตที่คุณมี', 'credit' => true];
            }
            if($amount > $currentCredit){
                return ['reason' => 'ยอดเงินเครดิตของท่านไม่เพียงพอ', 'credit' => true];
            }
       }

        //เงินเครดิตไม่เพียงพอเนื่องจากคุณได้ทำรายการถอนเงิน ไปแล้วก่อนหน้านี้ จำนวน xxxx บาท
        $hasPostActive = PostCreditTransectionSearch::getHasWithdrawPostActive($user_id);
        if($hasPostActive > 0){
            if($reason == 1){
                $amountPostActive = PostCreditTransectionSearch::getAmountWithdrawPostActive($user_id);
                return ['result'=>false,'reason' => 'ท่านยังมีรายการที่ยังไม่ได้รับอนุมัติอยู่ กรุณารอสักครู่ หรือติดต่อได้โดยตรงที่ admin ค่ะ'];
            }
        }
        
        if(!($countPostCredit >= 0 && $countPostCredit < Constants::maximum_post_credit_transection)){
            if($reason == 1){
                return ['result'=>false,'reason'=>'การแจ้งถอนในแต่ละวันจะไม่เกิน '.Constants::maximum_post_credit_transection.' ครั้ง'];
            }
        }
        $postCreditTransectionCount = PostCreditTransection::find()->where([
            'status' => 6,
            'action_id' => [Constants::action_credit_top_up, Constants::action_commission_withdraw],
            'create_by' => $user_id
        ])->count();
        if ($postCreditTransectionCount) {
            if($reason == 1) {
                return ['result' => false, 'reason' => 'ท่านยังมีรายการที่ยังไม่ได้รับอนุมัติอยู่ กรุณารอสักครู่ หรือติดต่อได้โดยตรงที่ admin ค่ะ'];
            }
        }
        $conditionWithdrawUser = ConditionWithdrawUser::find()->where(['userId' => $user_id])->one();
        if ($conditionWithdrawUser->totalConditionWithDraw > self::getBetAmount($user_id)) {
            $conditionWithdraw = ConditionWithdraw::find()->where(['status' => 1])->one();
            return ['result' => false, 'reason' => 'ท่านจะถอนเงินได้เมื่อมียอดเล่นขั้นต่ำ '.$conditionWithdraw->percent.'% ทุกยอดฝาก'];
        }
        //ดึงเงินฝากทั้งหมด ที่มาจากการเติมเงิน
        $savingAmount = self::getSavingAmount($user_id);
        $model = Commission::find()->select(['balance'])->where(['user_id' => $user_id])->one();
        $balance = 0;
        if (!empty($model)) {
            $balance = $model->balance;
        }
        return ['result'=>true,'reason'=>'success'];
    }
    //ดึงเงินฝากทั้งหมด ที่มาจากการเติมเงิน
    public static function getSavingAmount($user_id){
        if(empty($user_id)){
            return false;
        }
        $savingAmount = CreditTransectionSearch::find()->where([
            'action_id'=>Constants::action_credit_top_up,
            'reason_action_id'=>Constants::reason_credit_top_up,
            'reciver_id'=>$user_id
        ])->sum('amount');
        return $savingAmount;
    }
    //ดึงเงินการแทงทั้งหมด
    public static function getBetAmount($userId)
    {
        if(empty($userId)){
            return false;
        }
        $thaiSharedGameChitTotal = ThaiSharedGameChit::find()->where([
            'userId' => $userId,
            'status' => Constants::status_finish_show_result
        ])->sum('totalAmount');
        $yeeKeeChitTotal = YeekeeChit::find()->where([
            'user_id' => $userId,
            'status' => Constants::status_finish_show_result
        ])->sum('total_amount');
        $blackredChitTotal = BlackredChit::find()->where([
            'user_id' => $userId,
            'status' => Constants::status_finish_show_result
        ])->sum('total_amount');
        $betAmount = $thaiSharedGameChitTotal + $yeeKeeChitTotal + $blackredChitTotal;
        return $betAmount;
    }

    public function getOrderId(){
        return strtotime($this->create_at) + $this->id;
    }
    
    public function getCreditMasterBalance(){
        $query = self::find()
            ->where([
                'action_id'=> [
                    Constants::action_credit_top_up,
                    Constants::action_credit_withdraw,
                    Constants::action_credit_top_up_admin,
                    Constants::action_credit_withdraw_admin,
                    Constants::action_credit_master_top_up,
                    Constants::action_credit_master_withdraw,
                ],
                'reason_action_id'=> [
                    Constants::reason_credit_top_up,
                    Constants::reason_credit_withdraw,
                    Constants::reason_credit_withdraw_promotion,
                    Constants::reason_credit_top_up_promotion,
                ]
            ])
            ->orderBy(['id'=>SORT_DESC])->one();
        return (!empty($query) ? $query->credit_master_balance : 0);
    }
    
    public function checkCreditMasterBalance($action, $reason, $amount = 0)
    {
        $return = array();
        $CreditMasterBalance = 0;
        if(in_array($action, [Constants::action_credit_top_up, Constants::action_credit_withdraw, Constants::action_credit_top_up_admin, Constants::action_credit_withdraw_admin, Constants::action_commission_withdraw_direct])
            && in_array($reason, [Constants::reason_credit_top_up, Constants::reason_credit_withdraw, Constants::reason_credit_withdraw_promotion, Constants::reason_credit_top_up_promotion, Constants::reason_commission_withdraw_direct])){
            $modelCreditTransection = new CreditTransectionSearch();
            $CreditMaster = $modelCreditTransection->getCreditMasterBalance();
            if(in_array($action,[Constants::action_credit_top_up, Constants::action_credit_top_up_admin])){
                $CreditMasterBalance = $CreditMaster - $amount;
            }else{
                $CreditMasterBalance = $CreditMaster + $amount;
            }
            $return = ['amount'=>$CreditMasterBalance];
        }
        return $return;
    }
}
