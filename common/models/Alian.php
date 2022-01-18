<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alian".
 *
 * @property int $id
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $status
 * @property string $alian_name
 * @property int $use_count
 */
class Alian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_at', 'update_at'], 'safe'],
            [['create_by', 'update_by', 'status', 'use_count'], 'integer'],
            [['alian_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
            'status' => 'Status',
            'alian_name' => 'Alian Name',
            'use_count' => 'Use Count',
        ];
    }
}
