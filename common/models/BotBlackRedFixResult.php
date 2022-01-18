<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bot_black_red_fix_result".
 *
 * @property int $id
 * @property int $play_type_code
 * @property int $round
 * @property string $date
 * @property string $createdAt
 */
class BotBlackRedFixResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bot_black_red_fix_result}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['play_type_code', 'round', 'date'], 'required'],
            [['play_type_code', 'round'], 'integer'],
            [['createdAt'], 'safe'],
            ['play_type_code', 'integer', 'min' => 1, 'max' => 2],
            ['round', 'integer', 'min' => 1, 'max' => 654],
            [['round', 'date'], 'unique', 'targetAttribute' => ['round', 'date']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'play_type_code' => 'Play Type Code',
            'round' => 'Round',
            'createdAt' => 'Created At',
        ];
    }
}
