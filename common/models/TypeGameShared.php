<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "type_game_shared".
 *
 * @property int $id
 * @property string $title
 * @property int $status
 * @property string $createdAt
 */
class TypeGameShared extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%type_game_shared}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status'], 'integer'],
            [['createdAt'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'createdAt' => 'Created At',
        ];
    }
}
