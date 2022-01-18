<?php

namespace common\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "limit_lottery_by_game_play_type_set".
 *
 * @property int $id
 * @property int $playTypeId
 * @property string $title
 * @property string $number
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $status
 *
 * @property LimitLotteryByGamePlayType[] $limitLotteryByGamePlayTypes
 */
class LimitLotteryByGamePlayTypeSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%limit_lottery_by_game_play_type_set}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['playTypeId', 'title'], 'required'],
            [['playTypeId', 'createdBy', 'updatedBy', 'status'], 'integer'],
            [['playTypeId', 'title', 'number'], 'unique', 'targetAttribute' => ['playTypeId', 'title', 'number']],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title', 'number'], 'string', 'max' => 255],
            [['playTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => PlayType::className(), 'targetAttribute' => ['playTypeId' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'playTypeId' => 'เกม - ประเภท',
            'title' => 'หวยหุ้น',
            'number' => 'เลข',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdAt' => 'วันที่สร้าง',
            'updatedAt' => 'วันที่แก้ไข',
            'status' => 'สถานะ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLimitLotteryByGamePlayTypes()
    {
        return $this->hasMany(LimitLotteryByGamePlayType::className(), ['limitLotteryGamePlayTypeSetId' => 'id']);
    }

    public function getTitles()
    {
        return [
            'หวยรัฐบาลไทย' => 'หวยรัฐบาลไทย',
            'หวยรัฐบาลไทย แบบมีส่วนลด' => 'หวยรัฐบาลไทย แบบมีส่วนลด',
            'หวยลาว' => 'หวยลาว',
            'หวยลาว แบบมีส่วนลด' => 'หวยลาว แบบมีส่วนลด',
            'เวียดนาม/ฮานอย แบบมีส่วนลด' => 'เวียดนาม/ฮานอย แบบมีส่วนลด',
        ];
    }

    public function getPlayType()
    {
        return $this->hasOne(PlayType::className(), ['id' => 'playTypeId']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'createdBy']);
    }
}
