<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yeekee_post".
 *
 * @property int $id
 * @property double $order
 * @property int $post_num
 * @property string $post_name username, system display
 * @property int $yeekee_id
 * @property int $is_bot
 * @property string $username
 * @property string $create_at
 * @property int $create_by
 * @property string $update_at
 * @property int $update_by
 *
 * @property Yeekee $yeekee
 * @property User $createBy
 */
class YeekeePost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yeekee_post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order', 'post_num', 'post_name', 'yeekee_id', 'is_bot', 'username', 'create_by'], 'required'],
            [['order'], 'number'],
            [['post_num', 'yeekee_id', 'is_bot', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['post_name', 'username'], 'string', 'max' => 20],
            [['yeekee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yeekee::className(), 'targetAttribute' => ['yeekee_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order' => 'Order',
            'post_num' => 'Post Num',
            'post_name' => 'Post Name',
            'yeekee_id' => 'Yeekee ID',
            'is_bot' => 'Is Bot',
            'username' => 'Username',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYeekee()
    {
        return $this->hasOne(Yeekee::className(), ['id' => 'yeekee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
}
