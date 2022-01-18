<?php

namespace common\models;

use Yii;

class PlayTypeForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $three_top;
    public $three_tod;
    public $two_top;
    public $two_under;
    public $run_top;
    public $run_under;
    public $black;
    public $red;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under'], 'required'],
            [['black', 'red'], 'required' , 'on' => 'black-red'],
            [['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under'], 'number'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['black-red'] = ['black', 'red'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'three_top' => 'สามตัวบน',
            'three_tod' => 'สามตัวโต้ด',
            'two_top' => 'สองตัวบน',
            'two_under' => 'สองตัวล่าง',
            'run_top' => 'วิ่งบน',
            'run_under' => 'วิ่งล่าง',
            'black' => 'ดำ',
            'red' => 'แดง',
        ];
    }
}
