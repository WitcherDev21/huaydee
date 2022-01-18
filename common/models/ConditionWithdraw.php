<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "condition_withdraw".
 *
 * @property int $id
 * @property double $percent
 * @property int $status
 * @property string $createdAt
 * @property string $updatedAt
 */
class ConditionWithdraw extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%condition_withdraw}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percent'], 'required'],
            [['percent'], 'validatePercentActive', 'on' => 'create'],
            [['percent'], 'number', 'max' => '100'],
            [['status'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = array_merge($scenarios['default'], ['percent']);
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percent' => 'Percent',
            'status' => 'Status',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    public function validatePercentActive($attribute)
    {
        $countConditionWithdraw = ConditionWithdraw::find()->where(['status' => 1])->count();
        if ($countConditionWithdraw) {
            $this->addError($attribute, 'ไม่สามารถสร้างเงื่อนไขการถอนได้เนื่องจากมี่เงื่อนไขการถอนแล้ว');
        }
    }
}
