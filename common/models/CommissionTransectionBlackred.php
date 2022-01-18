<?php

namespace common\models;

use common\libs\Constants;
use Yii;

/**
 * This is the model class for table "commission_transection".
 *
 * @property int $id
 * @property int $action_id
 * @property int $operator_id
 * @property int $reciver_id
 * @property string $amount
 * @property string $balance
 * @property string $remark
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $reason_action_id
 */
class CommissionTransectionBlackred extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'commission_transection_blackred';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_id', 'operator_id', 'reciver_id', 'create_at', 'create_by'], 'required'],
            [['action_id', 'operator_id', 'reciver_id', 'create_by', 'update_by', 'reason_action_id'], 'integer'],
            [['amount', 'balance'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_id' => 'Action ID',
            'operator_id' => 'Operator ID',
            'reciver_id' => 'Reciver ID',
            'amount' => 'Amount',
            'balance' => 'Balance',
            'remark' => 'Remark',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'reason_action_id' => 'Reason Action ID',
        ];
    }

    public static function getIncomeInDay($date, $reciver_id){
        if(empty($reciver_id)){
            return 0;
        }

        $time_active_day = strtotime($date);
        $start_active_day = date('Y-m-d 00:00:00',$time_active_day);
        $end_active_day = date('Y-m-d 23:59:59',$time_active_day);

        $query = CommissionTransectionBlackred::find()
            ->where(['between','create_at',$start_active_day,$end_active_day])
            ->andWhere(['reciver_id'=> $reciver_id])
            ->andWhere(['action_id'=> Constants::action_commission_top_up]);

        $result = 0;
        foreach($query->all() as $model){
            $result += $model->amount;
        }
        return $result;
    }
}
