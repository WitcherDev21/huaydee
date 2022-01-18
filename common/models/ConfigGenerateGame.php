<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "config_generate_game".
 *
 * @property int $id
 * @property int $game_id
 * @property int $amount_of_round
 * @property string $start_time
 * @property string $finish_time
 * @property int $sec_per_round
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 */
class ConfigGenerateGame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_generate_game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['game_id', 'amount_of_round', 'sec_per_round', 'create_by', 'update_by'], 'integer'],
            [['start_time', 'finish_time', 'create_at', 'update_at'], 'safe'],
            [['create_by'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'amount_of_round' => 'Amount Of Round',
            'start_time' => 'Start Time',
            'finish_time' => 'Finish Time',
            'sec_per_round' => 'Sec Per Round',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }
}
