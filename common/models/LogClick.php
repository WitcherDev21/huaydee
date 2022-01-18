<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log_click".
 *
 * @property int $id
 * @property string $from_ip
 * @property int $from_user_id
 * @property string $create_at
 * @property string $from_source link, banner
 *
 * @property User $fromUser
 */
class LogClick extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_click}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_user_id'], 'integer'],
            [['create_at'], 'safe'],
            [['from_ip', 'from_source'], 'string', 'max' => 255],
            [['from_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['from_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_ip' => 'From Ip',
            'from_user_id' => 'From User ID',
            'create_at' => 'Create At',
            'from_source' => 'From Source',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        return $this->hasOne(User::className(), ['id' => 'from_user_id']);
    }
}
