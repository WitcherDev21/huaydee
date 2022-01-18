<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yeekee".
 *
 * @property int $id
 * @property int $round
 * @property int $group group
 * @property string $date_at รอบวันที่
 * @property string $start_at
 * @property string $finish_at
 * @property int $status
 * @property int $result
 * @property int $yeekee_post_sum
 * @property int $user_id_1
 * @property int $user_id_16
 * @property int $yeekee_post_id_1
 * @property int $yeekee_post_id_16
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 * @property int $isOpenBot
 *
 * @property User $createBy
 * @property User $updateBy
 * @property User $userId1
 * @property User $userId16
 * @property YeekeePost $yeekeePostId1
 * @property YeekeePost $yeekeePostId16
 * @property YeekeeChit[] $yeekeeChits
 */
class Yeekee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yeekee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['round', 'group', 'status', 'result', 'yeekee_post_sum', 'user_id_1', 'user_id_16', 'yeekee_post_id_1', 'yeekee_post_id_16', 'create_by', 'update_by', 'isOpenBot'], 'integer'],
            [['date_at', 'status', 'create_at', 'create_by'], 'required'],
            [['date_at', 'start_at', 'finish_at', 'create_at', 'update_at'], 'safe'],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['update_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['update_by' => 'id']],
            [['user_id_1'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id_1' => 'id']],
            [['user_id_16'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id_16' => 'id']],
            [['yeekee_post_id_1'], 'exist', 'skipOnError' => true, 'targetClass' => YeekeePost::className(), 'targetAttribute' => ['yeekee_post_id_1' => 'id']],
            [['yeekee_post_id_16'], 'exist', 'skipOnError' => true, 'targetClass' => YeekeePost::className(), 'targetAttribute' => ['yeekee_post_id_16' => 'id']],
            [['result'], 'is7NumbersOnly', 'on' => 'answer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'round' =>  Yii::t('app', 'Round'),
            'group' =>  Yii::t('app', 'Group'),
            'date_at' =>  Yii::t('app', 'Date At'),
            'start_at' =>  Yii::t('app', 'Start At'),
            'finish_at' =>  Yii::t('app', 'Finish At'),
            'status' =>  Yii::t('app', 'Status'),
            'result' =>  Yii::t('app', 'Result'),
            'yeekee_post_sum' =>  Yii::t('app', 'Yeekee Post Sum'),
            'user_id_1' =>  Yii::t('app', 'User Id 1'),
            'user_id_16' =>  Yii::t('app', 'User Id 16'),
            'yeekee_post_id_1' =>  Yii::t('app', 'Yeekee Post Id 1'),
            'yeekee_post_id_16' =>  Yii::t('app', 'Yeekee Post Id 16'),
            'create_at' =>  Yii::t('app', 'Create At'),
            'create_by' =>  Yii::t('app', 'Create By'),
            'update_at' =>  Yii::t('app', 'Update At'),
            'update_by' =>  Yii::t('app', 'Update By'),
            'isOpenBot' => Yii::t('app', 'Open Bot'),
        ];
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['answer'] = ['result'];

        return $scenarios;
    }

    public function is7NumbersOnly($attribute)
    {
        if($this->$attribute !== '') {
            if (!preg_match('/^[0-9]{7,}+$/', $this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'must contain exactly 7 digits.'));
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'update_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserId1()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id_1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserId16()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id_16']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekeePostId1()
    {
        return $this->hasOne(YeekeePost::className(), ['id' => 'yeekee_post_id_1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekeePostId16()
    {
        return $this->hasOne(YeekeePost::className(), ['id' => 'yeekee_post_id_16']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekeeChits()
    {
        return $this->hasMany(YeekeeChit::className(), ['yeekee_id' => 'id']);
    }
}
