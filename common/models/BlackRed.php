<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "black_red".
 *
 * @property int $id
 * @property int $round
 * @property int $group group
 * @property string $date_at รอบวันที่
 * @property string $start_at
 * @property string $finish_at
 * @property int $status
 * @property int $result
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 */
class BlackRed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uuiu_black_red';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['round', 'group', 'status', 'result', 'create_by', 'update_by'], 'integer'],
            [['date_at', 'status', 'create_at', 'create_by'], 'required'],
            [['date_at', 'start_at', 'finish_at', 'create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'round' => 'Round',
            'group' => 'Group',
            'date_at' => 'Date At',
            'start_at' => 'Start At',
            'finish_at' => 'Finish At',
            'status' => 'Status',
            'result' => 'Result',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }
    
    public static function getCreditCanPay($amount = 0,$user_id){
    	$payAmount = $amount;
    	$is_pass = false; //ขอให้มีการซื้อมาจริงๆ
    	
    	$creditBalance = CreditSearch::find()->select(['balance'])->where(['user_id'=>$user_id])->one();
    	if(empty($creditBalance)){
    		return false;
    	}
    	//จ่ายมากกว่ามี
    	if($payAmount>$creditBalance->balance){
    		return false;
    	}else {
    		return true;
    	}
    }

    public function getOrderId()
    {
        return strtotime($this->create_at) + $this->id;
    }
}
