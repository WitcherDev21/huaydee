<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PostCreditTransection;

/**
 * PostCreditTransectionSearch represents the model behind the search form of `common\models\PostCreditTransection`.
 */
class PostCreditTransectionTopup extends PostCreditTransection
{
    /**
     * {@inheritdoc}
     */
    public $tranfer_hr;
    public $tranfer_min;
    public $user_id;
	
    public function rules()
    {
        return [
        	[['user_has_bank_id', 'tranfer_hr', 'tranfer_min','amount'], 'required', 'message' => "กรุณากรอก{attribute}"],
            [['amount'], 'number', 'message' => 'กรุณากรอกจำนวนเงินเป็นตัวเลข'],
            [['user_has_bank_id','tranfer_hr','tranfer_min', 'user_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'ระบุจำนวนเงินที่ต้องการโอน',
            'remark' => 'หมายเหตุ',
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
}
