<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "play_type".
 *
 * @property int $id
 * @property string $code
 * @property string $title
 * @property string $description
 * @property int $group_id
 * @property int $game_id
 * @property double $jackpot_per_unit
 * @property int $minimum_play
 * @property int $maximum_play
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property string $sort
 * @property double $limitByUser
 *
 * @property PlayTypeGourp $group
 * @property Games $game
 */
class PlayType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%play_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'title', 'jackpot_per_unit', 'minimum_play', 'maximum_play'], 'required'],
            [['group_id', 'game_id', 'minimum_play', 'maximum_play', 'create_by', 'update_by'], 'integer'],
            [['jackpot_per_unit', 'sort', 'limitByUser'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['code'], 'string', 'max' => 10],
            [['title', 'description'], 'string', 'max' => 255],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayTypeGourp::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Games::className(), 'targetAttribute' => ['game_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'title' => 'Title',
            'description' => 'Description',
            'group_id' => 'Group ID',
            'game_id' => 'Game ID',
            'jackpot_per_unit' => 'Jackpot Per Unit',
            'minimum_play' => 'Minimum Play',
            'maximum_play' => 'Maximum Play',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'limitByUser' => 'Limit By User',
            'sort' => 'Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(PlayTypeGourp::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Games::className(), ['id' => 'game_id']);
    }

    public function getUser()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'create_by']);
    }

    public function getCode()
    {
        return [
            'three_top' => 'three_top',
            'three_tod' => 'three_tod',
            'three_top2' => 'three_top2',
            'three_und2' => 'three_under2',
            'two_top' => 'two_top',
            'two_under' => 'two_under',
            'run_top' => 'run_top',
            'run_under' => 'run_under',
            'black' => 'black',
            'red' => 'red',
            'four_dt' => 'four_direct',
            'four_tod' => 'four_tod',
            'two_ft' => 'two_front',
            'two_bk' => 'two_back',
            'three_ft' => 'three_front',
        ];
    }

    public function getConvertPlayTypeCode($code)
    {
        if ($code === 'three_top') {
            $playTypeCode = 'teng_bon_3';
        } elseif ($code === 'three_tod') {
            $playTypeCode = 'tode_3';
        } elseif ($code === 'two_top') {
            $playTypeCode = 'teng_bon_2';
        } elseif ($code === 'two_under') {
            $playTypeCode = 'teng_lang_2';
        } elseif ($code === 'run_top') {
            $playTypeCode = 'teng_bon_1';
        } elseif ($code === 'run_under') {
            $playTypeCode = 'teng_lang_1';
        } elseif ($code === 'three_top2') {
            $playTypeCode = 'teng_lang_nha_3';
        } elseif ($code === 'three_und2') {
            $playTypeCode = 'teng_lang_3';
        }
        return $playTypeCode;
    }

    public function getReConvertPlayTypeCode($code)
    {
        if ($code === 'teng_bon_3') {
            $playTypeCode = 'three_top';
        } elseif ($code === 'tode_3') {
            $playTypeCode = 'three_tod';
        } elseif ($code === 'teng_bon_2') {
            $playTypeCode = 'two_top';
        } elseif ($code === 'teng_lang_2') {
            $playTypeCode = 'two_under';
        } elseif ($code === 'teng_bon_1') {
            $playTypeCode = 'run_top';
        } elseif ($code === 'teng_lang_1') {
            $playTypeCode = 'run_under';
        } elseif ($code === 'teng_lang_nha_3') {
            $playTypeCode = 'three_top2';
        } elseif ($code === 'teng_lang_3') {
            $playTypeCode = 'three_und2';
        }
        return $playTypeCode;
    }

    public function getfindThreeTod($number)
    {
        $arr_num = str_split($number);
        $n = 0;
        $arr_swap_num = [];
        for ($i = 0; $i < count($arr_num); $i++) {
            $tmp = $arr_num[$i];
            for ($j = 0; $j < count($arr_num); $j++) {
                if ($i != $j) {
                    $tmp .= '' . $arr_num[$j];
                }
            }
            if (!in_array($tmp, $arr_swap_num)) {
                $arr_swap_num[$n++] = $tmp;
            }

            $tmp = $arr_num[$i];
            for ($j = (count($arr_num) - 1); $j >= 0; $j--) {
                if ($i != $j) {
                    $tmp .= '' . $arr_num[$j];
                }
            }
            if (!in_array($tmp, $arr_swap_num)) {
                $arr_swap_num[$n++] = $tmp;
            }
        }
        return $arr_swap_num;
    }
}
