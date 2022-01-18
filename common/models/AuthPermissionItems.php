<?php

namespace common\models;

use Yii;
use common\libs\Constants;

/**
 * This is the model class for table "auth_permission_items".
 *
 * @property int $auth_permission_id
 * @property int $auth_items_id
 * @property int $is_active
 */
class AuthPermissionItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_permission_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_permission_id', 'auth_items_id', 'is_active'], 'required'],
            [['auth_permission_id', 'auth_items_id', 'is_active'], 'integer'],
            [['auth_permission_id', 'auth_items_id', 'is_active'], 'unique', 'targetAttribute' => ['auth_permission_id', 'auth_items_id', 'is_active']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'auth_permission_id' => 'Auth Permission ID',
            'auth_items_id' => 'Auth Items ID',
            'is_active' => 'Is Active',
        ];
    }
    
    public function getAuth_items()
    {
      return $this->hasOne(AuthItems::className(), ['id' => 'auth_items_id'])
            ->andOnCondition(['auth_items.is_active' => Constants::status_active]);
    }
    
}
