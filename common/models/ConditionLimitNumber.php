<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "condition_limit_number".
 *
 * @property int $id
 * @property string $limit
 * @property int $playTypeId
 * @property int $gameId
 *
 * @property Games $game
 * @property PlayType $playType
 */
class ConditionLimitNumber extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'condition_limit_number';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['limit', 'playTypeId', 'gameId'], 'required'],
            [['playTypeId', 'gameId'], 'integer'],
            [['limit'], 'string', 'max' => 255],
            [['playTypeId'], 'unique', 'message' => Yii::t('app', 'Play Type used')],
            [['gameId'], 'exist', 'skipOnError' => true, 'targetClass' => Games::className(), 'targetAttribute' => ['gameId' => 'id']],
            [['playTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => PlayType::className(), 'targetAttribute' => ['playTypeId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'limit' => 'Limit',
            'playTypeId' => 'ประเภทหวย',
            'gameId' => 'Game ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Games::className(), ['id' => 'gameId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayType()
    {
        return $this->hasOne(PlayType::className(), ['id' => 'playTypeId']);
    }
}
