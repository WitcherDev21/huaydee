<?php

namespace common\models;

use yii\data\ActiveDataProvider;


class YeekeeChitDetailSearch extends YeekeeChitDetail
{

    public function rules()
    {
        return [

        ];
    }

    public function search($params)
    {
        $user_id = \Yii::$app->user->identity;

        $query = $this->find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC
                ]
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }


        return $dataProvider;
    }

    public function getWinCredit()
    {

        $resutl = 0;
        $is_win = $this->checkWin();
        if ($is_win) {
            $paid = $this->playTypeCode->jackpot_per_unit;
            $creditTransactionAmount = YeekeeChit::find()->where([
                'user_id' => $this->create_by,
            ])->andWhere(['status' => 9])->sum('total_amount');
            $resutl = $this->amount * $paid;
        }

        return $resutl;
    }

    public function checkWin()
    {
        $type = $this->play_type_code;
        $number = $this->number;

        $yeekee = YeekeeSearch::findOne(['id' => $this->yeekeeChit->yeekee->id]);
        $result = $yeekee->getResults($type);

        $is_win = false;
        if (is_array($result)) {
            if (in_array($number, $result)) {
                $is_win = true;
            }
        } else if ($result === $number) {
            $is_win = true;
        }
        return $is_win;
    }

    /**
     *
     * @param number $result เลขผลลัพธ์
     * @param unknown $type ประเภทการซื้อ three_top
     * @param unknown $number เลขที่ซื้อ
     * @param number $amount ซื้อเท่าไหร่
     */
    public function getWinCreditStatic($result_post_num, $type, $number, $amount)
    {
        $win_credit = 0;
        $yeekeechitdetail = new YeekeeChitDetailSearch();
        $number_result = self::getResults($result_post_num, $type);
        if (is_array($number_result) && in_array($number, $number_result)) {
            $yeekeechitdetail->play_type_code = $type;
            $paid = $yeekeechitdetail->playTypeCode->jackpot_per_unit;
            $win_credit = $amount * $paid;
        } elseif ($number_result == $number) {
            $yeekeechitdetail->play_type_code = $type;
            $paid = $yeekeechitdetail->playTypeCode->jackpot_per_unit;
            $win_credit = $amount * $paid;
        }
        return $win_credit;
    }

    public static function getCreditIfWin($type, $amount)
    {
        $yeekeechitdetail = new YeekeeChitDetailSearch();
        $yeekeechitdetail->play_type_code = $type;
        $paid = $yeekeechitdetail->playTypeCode->jackpot_per_unit;
        $win_credit = $amount * $paid;
        return $win_credit;
    }


    public function getResults($number, $type)
    {
        $lenght = strlen($number);
        $result = substr(''.$number,$lenght-5,$lenght);

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
}
