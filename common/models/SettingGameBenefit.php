<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting_game_benefit".
 *
 * @property int $id
 * @property string $code
 * @property int $game_id
 * @property int $status
 * @property string $value
 * @property string $value_type percent, boolean
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 */
class SettingGameBenefit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting_game_benefit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'game_id', 'status', 'value', 'value_type', 'create_at', 'create_by'], 'required'],
            [['game_id', 'status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['code', 'value_type'], 'string', 'max' => 20],
            [['value'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'game_id' => 'Game ID',
            'status' => 'Status',
            'value' => 'Value',
            'value_type' => 'Value Type',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }
}
