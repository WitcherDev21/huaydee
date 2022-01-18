<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "setup_level_playtype".
 *
 * @property int $id
 * @property string $codePlayType
 * @property int $minimumPlay
 * @property int $maximumPlay
 * @property double $jackPotPerUnit
 * @property int $gameId
 * @property int $createdBy
 * @property string $createdAt
 * @property int $updatedBy
 * @property string $updatedAt
 */
class SetupLevelPlaytype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%setup_level_playtype}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codePlayType', 'minimumPlay', 'maximumPlay', 'gameId'], 'required'],
            [['minimumPlay', 'maximumPlay', 'createdBy', 'updatedBy'], 'integer'],
            [['jackPotPerUnit'], 'number'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['codePlayType', 'gameId'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codePlayType' => 'Code Play Type',
            'minimumPlay' => 'Minimum Play',
            'maximumPlay' => 'Maximum Play',
            'jackPotPerUnit' => 'Jack Pot Per Unit',
            'gameId' => 'Game ID',
            'createdBy' => 'Created By',
            'createdAt' => 'Created At',
            'updatedBy' => 'Updated By',
            'updatedAt' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
