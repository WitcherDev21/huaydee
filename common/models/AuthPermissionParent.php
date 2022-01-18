<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_permission_parent".
 *
 * @property int $id
 * @property int $auth_rule_id
 * @property int $auth_permission_id
 * @property int $is_active
 * @property int $created_by
 * @property string $created_date
 * @property int $updated_by
 * @property string $updated_date
 */
class AuthPermissionParent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_permission_parent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_rule_id', 'auth_permission_id'], 'required'],
            [['auth_rule_id', 'auth_permission_id', 'is_active', 'created_by', 'updated_by'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_rule_id' => 'Auth Rule ID',
            'auth_permission_id' => 'Auth Permission ID',
            'is_active' => 'Is Active',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'updated_by' => 'Updated By',
            'updated_date' => 'Updated Date',
        ];
    }
    
    public function getAuth_permission()
    {
      return $this->hasOne(AuthPermission::className(), ['id' => 'auth_permission_id']);
    }
    
    
    public function getAuth_permission_child()
    {
      return $this->hasMany(AuthPermissionChild::className(), ['auth_permission_parent_id' => 'id']);
    }
    
    
}
