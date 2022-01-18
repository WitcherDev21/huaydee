<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\libs\Constants;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * This is the model class for table "bank".
 *
 * @property int $id
 * @property string $title
 * @property string $icon
 * @property string $code
 * @property string $color
 * @property int $status
 */
class BankSearch extends Bank
{
    
    public $icon_add;
    public $icon_edit;
    
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['title', 'icon', 'code', 'color'], 'string', 'max' => 255],
            [['title'], 'required', 'message' => "กรุณากรอก{attribute}"],
            [['icon_add'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['icon_edit'], 'file', 'extensions' => 'png, jpg'],
            
        ];
    }   
    
    public function attributeLabels()
    {
        return [
            'title' => 'ชื่อธนาคาร',
            'icon_add' => 'รูปธนาคาร',
            'icon_edit' => 'แก้ไขรูปธนาคาร',
        ];
    }
    
    public function search($params)
    {
        $query = $this->find()->where(['status'=>Constants::status_active]);
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
        return $dataProvider;
    }
    
    public function uploadadd()
    {
        if (!empty($this->icon_add[0]->baseName)) {
            $name = date('Ymd_His').'.'.$this->icon_add[0]->extension;
            $this->icon_add[0]->saveAs('../../frontend/web/bank/' . $name);
            return $name;
        }
    }
    
    public function uploadedit()
    {
        if (!empty($this->icon_edit[0]->baseName)) {
            $name = date('Ymd_His').'.'.$this->icon_edit[0]->extension;
            $this->icon_edit[0]->saveAs('../../frontend/web/bank/' . $name);
            return $name;
        }
    }
    
    public function _save($data)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            
            $date = date('Y-m-d H:i:s');
            if(!empty($data['BankSearch']['id'])){
                
                $model = Bank::find()->where(['id'=>$data['BankSearch']['id']])->one();
            }else{
                $model = new Bank();
                $model->status = Constants::status_active;
            }
            $model->title = $data['BankSearch']['title'];
            if(!empty($data['file_name'])){
                $model->icon = $data['file_name'];
            }
            if($model->save()){
                $transaction->commit();
                return true;
            }else{
                return false;
            }
            
        } catch(Exception $e) {
            $transaction->rollBack();
            return 'error';
        }
    }
    
    
    
    
    
    
    public static function getBanks(){
    	//return	ArrayHelper::map(Bank::find()->all(),'id','title');
    	
    	$arrBank = [];
    	foreach(Bank::find()->all() as $b){
    		$arrBank[] = ['id'=>$b->id,'title'=>$b->title,'icon'=>$b->icon,'color'=>$b->color];
    	}
    	return $arrBank;
    }
    public static function getBanks2(){
    	//return	ArrayHelper::map(Bank::find()->all(),'id','title');
    	 
    	$arrBank = [];
    	foreach(Bank::find()->all() as $b){
    		$arrBank[$b->id] = $b->title;
    	}
    	return $arrBank;
    }
    
    public function _getList()
    {
        $BankList = Bank::find()->select('id, title as name')->where(['status'=>Constants::status_active])->asArray()->all();
        return ArrayHelper::map($BankList,'id','name');
    }
}
