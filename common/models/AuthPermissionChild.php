<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_permission_child".
 *
 * @property int $id
 * @property int $auth_permission_parent_id
 * @property int $auth_items_id
 */
class AuthPermissionChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_permission_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_permission_parent_id', 'auth_items_id'], 'required'],
            [['auth_permission_parent_id', 'auth_items_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_permission_parent_id' => 'Auth Permission Parent ID',
            'auth_items_id' => 'Auth Items ID',
        ];
    }
    
    public function getAuth_items()
    {
      return $this->hasOne(AuthItems::className(), ['id' => 'auth_items_id']);
    }
    
}
