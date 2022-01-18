<?php

namespace common\models;

use Yii;
use common\libs\Constants;

/**
 * This is the model class for table "auth_permission".
 *
 * @property int $id
 * @property string $name
 * @property int $auth_route_type_id
 * @property string $route_controller_name
 * @property int $is_active
 */
class AuthPermission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_permission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'auth_route_type_id'], 'required'],
            [['auth_route_type_id', 'is_active'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['route_controller_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'auth_route_type_id' => 'Auth Route Type ID',
            'route_controller_name' => 'Route Controller Name',
            'is_active' => 'Is Active',
        ];
    }
    
    
    public function getAuth_permission_items()
    {
        return $this->hasMany(AuthPermissionItems::className(), ['auth_permission_id' => 'id'])
            ->andOnCondition(['auth_permission_items.is_active' => Constants::status_active]);
    }
    
    
    
    
    
}
