<?php
/* 
 * Core Business function implement here by pongsak 2015-02-26
 */

namespace backend\component;
 
use common\models\User;
use Yii;
use yii\base\Component;
use common\libs\Constants;
use common\models\PostCreditTransection;

class CoreFunctions extends Component{
    
    public static function _call($func)
    {
        if($func=='AmountNewCreditTransectionAgent'){
            $amount = PostCreditTransection::find()
                    ->joinWith('poster')
                    ->where(['status'=>Constants::status_waitting,'user.auth_roles_id'=>Constants::auth_roles_agent])
                    ->count();
            return '<span class="badge AmountNewCreditTransectionAgent" style="background-color: #f74d4d;">'.($amount > 0 ? $amount : '').'</span>';
        } elseif ($func=='AmountNewCreditTransectionMember'){
            $amount = PostCreditTransection::find()
                    ->joinWith('poster')
                    ->where(['status'=>Constants::status_waitting,'user.auth_roles_id'=>Constants::auth_roles_member])
                    ->count();
            return '<span class="badge AmountNewCreditTransectionMember" style="background-color: #f74d4d;">'.($amount > 0 ? $amount : '').'</span>';
        } elseif ($func == 'userCountNoActive') {
            $amount = User::find()->joinWith('userHasBank')->where(['status' => Constants::status_waitting])->count();
            return '<span class="badge userCountNoActive" style="background-color: #f74d4d;">'.($amount > 0 ? $amount : '').'</span>';
        }
        
    }
    
    
}