<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yeekee_chit_detail".
 *
 * @property int $id
 * @property int $yeekee_chit_id
 * @property string $number
 * @property string $play_type_code three_tod, three_top, run_top, run_under, two_top, two_under
 * @property int $amount
 * @property int $flag_result win, no_win
 * @property double $win_credit ถ้าชนะได้เงินเท่าไหร่
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $vipId
 *
 * @property YeekeeChit $yeekeeChit
 * @property PlayType $playTypeCode
 */
class YeekeeChitDetail extends \yii\db\ActiveRecord
{
    public $win;
    public $nowin;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yeekee_chit_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['yeekee_chit_id', 'number', 'play_type_code', 'create_at', 'create_by'], 'required'],
            [['yeekee_chit_id', 'amount', 'flag_result', 'create_by', 'update_by'], 'integer'],
            [['win_credit'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['number'], 'string', 'max' => 3],
            [['play_type_code'], 'string', 'max' => 10],
            [['yeekee_chit_id'], 'exist', 'skipOnError' => true, 'targetClass' => YeekeeChit::className(), 'targetAttribute' => ['yeekee_chit_id' => 'id']],
            [['play_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => PlayType::className(), 'targetAttribute' => ['play_type_code' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'yeekee_chit_id' => 'Yeekee Chit ID',
            'number' => 'Number',
            'play_type_code' => 'Play Type Code',
            'amount' => 'Amount',
            'flag_result' => 'Flag Result',
            'win_credit' => 'Win Credit',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekeeChit()
    {
        return $this->hasOne(YeekeeChit::className(), ['id' => 'yeekee_chit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayTypeCode()
    {
        return $this->hasOne(PlayType::className(), ['code' => 'play_type_code']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
}
