<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "number_memo".
 *
 * @property int $id
 * @property string $title
 * @property string $json_value
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $user_id
 * @property int $gameId
 *
 * @property User $user
 */
class NumberMemo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%number_memo}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'json_value', 'create_at', 'create_by', 'gameId'], 'required'],
            [['json_value'], 'string'],
            [['create_at', 'update_at'], 'safe'],
            [['create_by', 'update_by', 'user_id', 'gameId'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['gameId'], 'exist', 'skipOnError' => true, 'targetClass' => Games::className(), 'targetAttribute' => ['gameId' => 'id']],
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
            'json_value' => 'Json Value',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'user_id' => 'User ID',
            'gameId' => 'Game ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getGame()
    {
        return $this->hasOne(Games::className(), ['id' => 'gameId']);
    }
}
