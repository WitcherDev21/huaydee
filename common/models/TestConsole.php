<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "test_console".
 *
 * @property int $id
 * @property string $value
 * @property string $update_at
 */
class TestConsole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_console';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['update_at'], 'safe'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'update_at' => 'Update At',
        ];
    }
}
