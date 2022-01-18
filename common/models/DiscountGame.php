<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "discount_game".
 *
 * @property int $id
 * @property int $playTypeId
 * @property double $discount
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $status
 * @property string $title
 *
 * @property Games $game
 * @property PlayType $playType
 */
class DiscountGame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%discount_game}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['playTypeId', 'discount', 'createdBy', 'updatedBy', 'status', 'title'], 'required'],
            [['playTypeId', 'createdBy', 'updatedBy', 'status'], 'integer'],
            [['discount'], 'number'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['playTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => PlayType::className(), 'targetAttribute' => ['playTypeId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['createdBy', 'updatedBy']
                ]
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'playTypeId' => 'เกม - ประเภท',
            'discount' => 'ส่วนลด',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'status' => 'Status',
            'title' => 'หวยหุ้น',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayType()
    {
        return $this->hasOne(PlayType::className(), ['id' => 'playTypeId']);
    }
}
