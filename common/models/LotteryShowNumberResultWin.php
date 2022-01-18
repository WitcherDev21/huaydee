<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lottery_show_number_result_win".
 *
 * @property int $id
 * @property int $thaiSharedGameId
 * @property string $number
 */
class LotteryShowNumberResultWin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lottery_show_number_result_win';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thaiSharedGameId', 'number'], 'required'],
            [['thaiSharedGameId'], 'integer'],
            [['number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thaiSharedGameId' => 'Thai Shared Game ID',
            'number' => 'Number',
        ];
    }
}
