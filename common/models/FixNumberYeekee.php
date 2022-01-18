<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "fix_number_yeekee".
 *
 * @property int $id
 * @property string $number
 * @property int $yeekeeId
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Yeekee $yeekee
 */
class FixNumberYeekee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%fix_number_yeekee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'yeekeeId'], 'required'],
            [['number'], 'string'],
            [['number'], 'is5NumbersOnly'],
            [['yeekeeId', 'createdBy', 'updatedBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['yeekeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Yeekee::className(), 'targetAttribute' => ['yeekeeId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'yeekeeId' => 'Yeekee ID',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekee()
    {
        return $this->hasOne(Yeekee::className(), ['id' => 'yeekeeId']);
    }

    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'createdBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updatedBy']);
    }

    public function is5NumbersOnly($attribute)
    {
        if($this->$attribute !== '') {
            if (strlen($this->$attribute) !== 5) {
                $this->addError($attribute, Yii::t('app', 'must contain exactly 5 digits.'));
            }
        }
    }
}
