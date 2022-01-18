<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "play_type_gourp".
 *
 * @property int $id
 * @property string $title
 * @property int $number_length
 * @property int $number_range
 *
 * @property PlayType[] $playTypes
 */
class PlayTypeGourp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%play_type_gourp}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'number_length', 'number_range'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'number_length' => 'Number Length',
            'number_range' => 'Number Range',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayTypes()
    {
        return $this->hasMany(PlayType::className(), ['group_id' => 'id']);
    }

    public function getLastId()
    {
         $playTypeGroup = PlayTypeGourp::find()->select('id')->orderBy('id DESC')->limit(1)->one();
         return $playTypeGroup->id + 1;
    }
}
