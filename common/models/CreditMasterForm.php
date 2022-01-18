<?php

namespace common\models;

use Yii;

class CreditMasterForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $income;
    public $outcome;
        
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['income', 'outcome'], 'number', 'message' => "กรุณากรอก{attribute}เป็นตัวเลข"],
            [['income', 'outcome'], 'required', 'message' => "กรุณากรอก{attribute}"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'income' => 'เติมเงินเข้าระบบ',
            'outcome' => 'ถอนเงินออกจากระบบ',
        ];
    }
}
