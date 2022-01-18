<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "lottery_game_chit_answer".
 *
 * @property string $three_top
 * @property string $two_under
 * @property string $three_top2_1
 * @property string $three_top2_2
 * @property string $three_under2_1
 * @property string $three_under2_2
 * @property string $four_dt
 * @property string $firstResult
 *
 */
class ThaiSharedAnswerGameForm extends Model
{
    public $three_top;
    public $firstResult;
    public $two_under;
    public $three_top2_1;
    public $three_top2_2;
    public $three_under2_1;
    public $three_under2_2;
    public $four_dt;
    public $result;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['three_top', 'two_under'], 'required'],
            [['three_top', 'two_under'], 'number'],
            [['three_top2_1', 'three_top2_2', 'three_under2_1', 'three_under2_2', 'firstResult'], 'required', 'on' => 'lotteryGame'],
            [['three_top2_1', 'three_top2_2', 'three_under2_1', 'three_under2_2'], 'is3NumbersOnly', 'on' => 'lotteryGame'],
            [['three_top2_1', 'three_top2_2', 'three_under2_1', 'three_under2_2'], 'number', 'on' => 'lotteryGame'],
            [['firstResult'], 'is6NumbersOnly', 'on' => 'lotteryGame'],
            [['four_dt'], 'required', 'on' => 'lottery-lao-set'],
            [['four_dt'], 'number', 'on' => 'lottery-lao-set'],
            [['three_top'], 'is3NumbersOnly'],
            [['two_under'], 'is2NumbersOnly'],
            [['result'], 'string', 'max' => 255],
            [['four_dt'], 'is4NumbersOnly', 'on' => 'lottery-lao-set'],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['lotteryGame'] = ['three_top2_1', 'three_top2_2', 'three_under2_1', 'three_under2_2', 'two_under', 'firstResult'];//Scenario Values Only Accepted
        $scenarios['lottery-lao-set'] = ['four_dt'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'three_top' => 'สามตัวบน',
            'two_under' => 'สองตัวล่าง',
            'three_top2_1' => 'สามตัวหน้าหมุน 2 ครั้ง ครั้งที่ 1',
            'three_top2_2' => 'สามตัวหน้าหมุน 2 ครั้ง ครั้งที่ 2',
            'three_under2_1' => 'สามตัวล่างหมุน 2 ครั้ง ครั้งที่ 1',
            'three_under2_2' => 'สามตัวล่างหมุน 2 ครั้ง ครั้งที่ 2',
            'four_dt' => 'เลขชุด',
            'firstResult' => 'เลข 6 หลัก',
            'result' => 'เลข 6 หลัก',
        ];
    }

    public function is3NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{3}$/', $this->$attribute)) {
            $this->addError($attribute, 'must contain exactly 3 digits.');
        }
    }

    public function is2NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{2}$/', $this->$attribute)) {
            $this->addError($attribute, 'must contain exactly 2 digits.');
        }
    }

    public function is4NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{4}$/', $this->$attribute)) {
            $this->addError($attribute, 'must contain exactly 2 digits.');
        }
    }

    public function is6NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{6}$/', $this->$attribute)) {
            $this->addError($attribute, 'must contain exactly 6 digits.');
        }
    }
}
