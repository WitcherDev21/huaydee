<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\libs\Constants;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "auth_roles".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property string $created_date
 * @property int $updated_by
 * @property string $updated_date
 * @property int $is_active
 */
class AuthRoles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'is_active'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['created_date', 'updated_date'], 'safe'],
            [['name'], 'required' , 'message' => "กรุณากรอก {attribute}"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Role Name',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'updated_by' => 'Updated By',
            'updated_date' => 'Updated Date',
            'is_active' => 'Is Active',
        ];
    }
    
    
    public function search($params)
    {
        
        $query = AuthRoles::find();
        $query->andFilterWhere(['is_active'=>Constants::status_active]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
     
        $this->load($params);
        
//        $query->andFilterWhere(['feeder_id' => $this->filter_feeder_id]);
//        if(!empty($this->filter_master_type)){
//            if($this->filter_master_type == 'vendor'){
//                $query->andWhere("clients.vendor_group_code <> ''");
//            }elseif($this->filter_master_type == 'customer'){
//                $query->andWhere("clients.customer_group_code <> ''");
//            }
//        }
//        $query->andFilterWhere(['like', 'feeder_code', $this->filter_group_code]);
//        $query->andFilterWhere(['like', 'feeder_code', $this->filter_feeder_code]);
//        $query->andFilterWhere(['like', 'clients.name', $this->filter_name]);
//        $query->andFilterWhere(['clients.status' => $this->filter_status]);
        
        
        return $dataProvider;
        
    }
    
    
    
    public function _save($data)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $date = date('Y-m-d H:i:s');
            if(!empty($data['AuthRoles']['id'])){
                $model = AuthRoles::find()->where(['id'=>$data['AuthRoles']['id']])->one();
                $model->updated_by = Yii::$app->user->identity->id;
                $model->updated_date = $date;
                
            }else{
                $model = new AuthRoles();
                $model->created_by = Yii::$app->user->identity->id;
                $model->created_date = $date;
            }
            $model->name = $data['AuthRoles']['name'];
            if($model->save()){
                $model->id;
                if(!empty($data['AuthRoles']['id'])){
                    AuthPermissionParent::updateAll(['is_active'=>Constants::status_inactive,'updated_by'=>Yii::$app->user->identity->id,'updated_date'=>$date],
                        'auth_rule_id="'.$data['AuthRoles']['id'].'" AND is_active='.Constants::status_active);
                }
                if(!empty($data['main_per'])){
                    foreach ($data['main_per'] as $keyMain => $main){
                        $modelParent = new AuthPermissionParent();
                        $modelParent->auth_rule_id = $model->id;
                        $modelParent->auth_permission_id = $keyMain;
                        $modelParent->created_by = Yii::$app->user->identity->id;
                        $modelParent->created_date = $date;
                        if($modelParent->save()){
                            if(!empty($data['items_per']) && !empty($data['items_per'][$keyMain])){
                                foreach ($data['items_per'][$keyMain] AS $keyItem => $item){
                                    $modelChild = new AuthPermissionChild();
                                    $modelChild->auth_permission_parent_id = $modelParent->id;
                                    $modelChild->auth_items_id = $keyItem;
                                    if(!$modelChild->save()){
                                        return false;
                                    }
                                }
                            }
                        }else{
                            return false;
                        }
                    }
                }
                $transaction->commit();
                return true;
            }else{
                return false;
            }
            
        } catch(Exception $e) {
            $transaction->rollBack();
            return 'error';
        }
    }
    
    
    public function _getRoles($auth_roles_id)
    {
        
            $AuthPermission = AuthPermissionParent::find()
                ->select('auth_permission_id,auth_permission.route_controller_name')
//                ->joinWith(['auth_permission','auth_permission_child','auth_permission_child.auth_items'])
                ->joinWith(['auth_permission'])
                ->where(['auth_permission_parent.auth_rule_id'=>$auth_roles_id,'auth_permission_parent.is_active'=>Constants::status_active])
                ->orderBy(['auth_permission_parent.id'=>SORT_ASC])
                ->asArray()
                ->all();
            $data = array();
            if(!empty($AuthPermission)){
                foreach ($AuthPermission as $val){
                    $data[] = $val['route_controller_name'];
                }
            }
            
            return $data;
    }
    
    public function _getList()
    {
        $AuthRolesList = AuthRoles::find()->select('id, name')->where(['is_active' => Constants::status_active])->asArray()->all();
        return ArrayHelper::map($AuthRolesList,'id','name');
    }
    
}
