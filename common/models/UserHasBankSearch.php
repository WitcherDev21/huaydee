<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\libs\Constants;

/**
 * This is the model class for table "user_has_bank".
 *
 * @property int $id
 * @property int $user_id
 * @property int $bank_id
 * @property string $bank_account_name
 * @property string $bank_account_no
 * @property int $status
 * @property int $version
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 *
 * @property Bank $bank
 * @property User $user
 * @property User $createBy
 * @property User $updateBy
 */
class UserHasBankSearch extends UserHasBank
{
 
    public $filter_text;
    public $filter_bank_id;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'bank_id', 'status', 'version', 'create_by', 'update_by'], 'integer'],
            [['bank_id', 'bank_account_name', 'bank_account_no', 'status', 'version', 'create_at'], 'required'
                , 'message' => 'กรุณากรอก{attribute}'],
            [['bank_id'], 'required', 'message' => 'กรุณาเลือก{attribute}'],
            [['description'], 'string'],
            [['create_at', 'update_at', 'filter_text', 'filter_bank_id'], 'safe'],
            [['bank_account_name'], 'string', 'max' => 100],
            [['bank_account_no'], 'string', 'max' => 30],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['update_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['update_by' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bank_id' => 'ธนาคาร',
            'bank_account_name' => 'ชื่อบัญชี',
            'bank_account_no' => 'เลขบัญชี',
            'description' => 'รายละเอียด',
        ];
    }
    
   public static function getBankAccountSystem(){
   		$query = UserHasBank::find();
   		$query->joinWith(['user']);
   		$query->where(['user.username' => 'system', 'user_has_bank.status' => Constants::status_active]);
   		
   		$arrBank = [];
   		foreach($query->all() as $b){
   			$arrBank[] = [
   			    'bank_id' => $b->bank->id,
                'user_has_bank_id' => $b->id,
                'version' => $b->version,
                'title' => $b->bank->title,
                'icon' => $b->bank->icon,
                'color' => $b->bank->color,
                'bank_account_name' => $b->bank_account_name,
                'bank_account_no' => $b->bank_account_no,
   					
   			];
   		}
   		return $arrBank;
   }

   public static function getBankAccountUser($userId) {
   	$userHasBank = UserHasBank::find()->where([
   	    'user_id' => $userId,
        'status' => Constants::status_active
    ])->all();
   	return $userHasBank;
   }

   public static function getCurrentVersion($id){
	$model = UserHasBank::find()->select(['version'])->where(['id'=>$id])->one();
   	if(!empty($model)){
   		return $model->version;
   	}else{
   		return false;
   	}
   }
   
    public function search($params)
    {
        $query = UserHasBankSearch::find()
            ->joinWith(['bank'])
            ->where(['user_has_bank.status'=>[Constants::status_active,Constants::status_withhold]])
            ->andWhere(['user_has_bank.user_id'=>Constants::user_system_id]);


//        \yii\helpers\VarDumper::dump($query,100,1);
//        exit;
        
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => [
						'pageSize' => 20
				],
        ]);
        
        $this->load($params);
        
        if(!empty($this->filter_text)){
            $query->andFilterWhere([
                'or',
                ['like', 'user_has_bank.bank_account_name', $this->filter_text],
                ['like', 'user_has_bank.bank_account_no', $this->filter_text],
            ]);
        }
        if(!empty($this->filter_bank_id)){
            $query->andWhere(['bank_id'=>$this->filter_bank_id]);
        }
        
//        if(!empty($this->create_at)){
//	        $query->andFilterWhere([
//	        		'between','create_at',date("Y-m-d 00:00:00",strtotime($this->create_at)),date("Y-m-d 23:59:59",strtotime($this->create_at))
//	        ]);
//        }

        //$query->andFilterWhere(['like', 'remark', $this->remark]);
       
        $query->orderBy(['bank.id'=>SORT_ASC]);
        
        
        return $dataProvider;
    }

    public function searchWaiting($params)
    {
        $query = UserHasBankSearch::find()
            ->joinWith(['bank'])
            ->joinWith(['user'])
            ->where(['user_has_bank.status' => Constants::status_waitting])
            ->orderBy(User::tableName().'.create_at ASC');


//        \yii\helpers\VarDumper::dump($query,100,1);
//        exit;


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        $this->load($params);

        if(!empty($this->filter_text)){
            $query->andFilterWhere([
                'or',
                ['like', 'user_has_bank.bank_account_name', $this->filter_text],
                ['like', 'user_has_bank.bank_account_no', $this->filter_text],
            ]);
        }
        if(!empty($this->filter_bank_id)){
            $query->andWhere(['bank_id'=>$this->filter_bank_id]);
        }

        $query->orderBy(['bank.id'=>SORT_ASC]);


        return $dataProvider;
    }

    public function getOrderId(){
        return strtotime($this->create_at) + $this->id;
    }
}
