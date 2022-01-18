<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\libs\Constants;

/**
 * PostCreditTransectionSearch represents the model behind the search form of `common\models\PostCreditTransection`.
 */
class PostCreditTransectionSearch extends PostCreditTransection
{
    /**
     * {@inheritdoc}
     */
	public $q;
	public $fillter_q;
	public $date;
	public $fillter_date;
	public $bank_id;
	
	public $from;
        public $auth_roles_id;
        
    public function rules()
    {
        return [
            [['id', 'poster_id', 'action_id', 'user_has_bank_id', 'user_has_bank_version', 'status', 'create_by', 'update_by', 'is_auto'], 'integer'],
            [['amount'], 'number'],
            [['remark', 'create_at', 'update_at',
            		'q','fillter_q',
            		'date','fillter_date',
            		'from',
            'bank_id'], 'safe'],
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
    	$user_id = \Yii::$app->user->identity->id;
        $query = PostCreditTransection::find()
                ->joinWith(['poster', 'userHasBankUser']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => [
        	    'pageSize' => 20
            ],
        ]);
        if(!empty($this->from)){
            if($this->from == 'frontend')
                $query->andFilterWhere(['post_credit_transection.create_by'=>$user_id]);
        }
        
        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'action_id' => $this->action_id
        ]);
        $query->andFilterWhere([
            PostCreditTransectionSearch::tableName().'.status' => $this->status
        ]);
        if(!empty($this->create_at)){
	        $query->andFilterWhere([
	        		'between','post_credit_transection.create_at',date("Y-m-d 00:00:00",strtotime($this->create_at)),date("Y-m-d 23:59:59",strtotime($this->create_at))
	        ]);
        }else {
            if(!empty($this->from)){
                if($this->from == 'frontend'){
                    $query->andFilterWhere([
                        '<','post_credit_transection.create_at',date("Y-m-d 00:00:00",time())
                    ]);
                }
            }
        }

        if(!empty($this->auth_roles_id)){
	        $query->andFilterWhere(['user.auth_roles_id'=>$this->auth_roles_id]);
        }

        if(!empty($this->status)){
	        $query->andFilterWhere(['post_credit_transection.status'=>$this->status]);
        }

        if(!empty($this->is_auto)){
            $query->andFilterWhere(['post_credit_transection.is_auto'=>$this->is_auto]);
        }

        if(!empty($params)){
            
            
            
//            'PostCreditTransectionSearch' => [
//        'action_id' => ''
//    ]
            if(!empty($this->q) && !empty($this->fillter_q)){
                if($this->fillter_q == '1'){
                    $query->andFilterWhere(['user.username' => $this->q]);
                }elseif($this->fillter_q == '2'){
                    $query->andFilterWhere(['like','user_has_bank.bank_account_name',$this->q]);
                }elseif($this->fillter_q == '3'){
                    $query->andFilterWhere(['like','user_has_bank.bank_account_no',$this->q]);
                }elseif($this->fillter_q == '4'){
                    $query->andFilterWhere(['post_credit_transection.amount'=>$this->q]);
                }
            }elseif(!empty($this->q)){
                $query->andFilterWhere(['or',
                ['user.username' => $this->q],
                ['like','user_has_bank.bank_account_name',$this->q],
                ['like','user_has_bank.bank_account_no',$this->q],
                ['post_credit_transection.amount'=>$this->q]]);
            }
            if(!empty($this->bank_id)){
                $query->andWhere(['user_has_bank.bank_id'=>$this->bank_id]);
            }
            if(!empty($this->date)){
	        $query->andFilterWhere([
	        		'between','post_credit_transection.create_at',date("Y-m-d 00:00:00",strtotime($this->date)),date("Y-m-d 23:59:59",strtotime($this->date))
	        ]);
            }
        }
        //$query->andFilterWhere(['like', 'remark', $this->remark]);
       
		$query->orderBy(['post_credit_transection.create_at'=>SORT_DESC]);
        return $dataProvider;
    }
    public static function getBankAccount($user_has_bank_id){
   /*
    	$data = UserHasBankLog::find()->where([
    			'version' => $version,
    			'user_has_bank_id' => $user_has_bank_id])
    	->one();
    */
        $data = UserHasBank::find()->where([
            'id' => $user_has_bank_id])
        ->one();
    	return $data;    	
    }
    //จำนวนแจ้ง ถอนในวันนี้
    public static function getPostWithdrawInToday($user_id = ''){
        if(empty($user_id)){
            return -1;
        }        
        $countPostCredit = PostCreditTransectionSearch::find()
            ->where(['poster_id'=>$user_id])
            ->andWhere(['between','create_at',date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')])
            ->andWhere(['action_id'=>Constants::action_credit_withdraw])                
            ->count('*');
        
        return $countPostCredit;          
    }
    public function getOrderId(){
        return strtotime($this->create_at) + $this->id;
    }
    public static function getHasWithdrawPostActive($user_id){
        if(empty($user_id)){
            return false;
        }    
        $result = PostCreditTransectionSearch::find()
        ->where(['poster_id'=>$user_id,'action_id'=>Constants::action_credit_withdraw,'status'=>Constants::status_waitting])
        ->count('*');
        
        return $result;
    }
    public static function getAmountWithdrawPostActive($user_id){
        if(empty($user_id)){
            return false;
        }
        $result = PostCreditTransectionSearch::find()
        ->where(['poster_id'=>$user_id,'action_id'=>Constants::action_credit_withdraw,'status'=>Constants::status_waitting])
        ->sum('amount');
        
        return $result;
    }
}
