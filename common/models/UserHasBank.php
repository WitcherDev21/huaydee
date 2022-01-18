<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "user_has_bank".
 *
 * @property int $id
 * @property int $user_id
 * @property int $bank_id
 * @property string $bank_account_name
 * @property string $bank_account_no
 * @property string $description
 * @property int $status
 * @property int $version
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 *
 * @property PostCreditTransection[] $postCreditTransections
 * @property Bank $bank
 * @property User $user
 * @property User $createBy
 * @property User $updateBy
 */
class UserHasBank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_has_bank}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'bank_id', 'status', 'version', 'create_by', 'update_by'], 'integer'],
            [['description'], 'string'],
            [['user_id', 'bank_id', 'status', 'version'], 'required'],
            [['create_at', 'update_at'], 'safe'],
            [['bank_account_name'], 'string', 'max' => 100],
            [['bank_account_no'], 'string', 'max' => 30],
            ['bank_account_no', 'required', 'when' => function($model) {
                return $model->bank_id !== '';
            }],
            ['bank_account_no', 'validateDuplicateAccountNo'],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['update_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['update_by' => 'id']],
        ];
    }

    public function scenarios()
    {
        $sn = parent::scenarios();
        $sn['bank_block'] = ['update_at', 'update_by', 'status'];
        return $sn;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
                'updatedAtAttribute' => 'update_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute'=>'create_by',
                'updatedByAttribute' => 'update_by',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'bank_id' => 'Bank ID',
            'bank_account_name' => 'Bank Account Name',
            'bank_account_no' => 'Bank Account No',
            'description' => 'Description',
            'status' => 'Status',
            'version' => 'Version',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCreditTransections()
    {
        return $this->hasMany(PostCreditTransection::className(), ['user_has_bank_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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

    public function validateDuplicateAccountNo($attribute)
    {
        if (!$this->getIsNewRecord()) {
            $isDuplicateAccountNo = UserHasBank::find()->where(['bank_account_no' => $this->bank_account_no])->andWhere(['<>', 'id', $this->id])->count();
        }else{
            $isDuplicateAccountNo = UserHasBank::find()->where(['bank_account_no' => $this->bank_account_no])->count();
        }
        if ($isDuplicateAccountNo) {
            $this->addError($attribute, 'เลขที่บัญชีซ้ำกันกรุณาแก้ไขและอับเดตข้อมูลใหม่อีกครั้ง');
        }
        return ;
    }
}
