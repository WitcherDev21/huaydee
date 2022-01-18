<?php

namespace backend\component;

use yii\base\Widget;
use yii\helpers\Url;
use common\libs\Constants;
use common\models\AuthRoles;

class Sidebar extends Widget
{
    public function run()
    {
        $identity = \Yii::$app->user->getIdentity();
        $arrMenu = [];

        if (!empty($identity)) {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        }
        // default
        foreach (Constants::$menu_backend as $menu) {
            $can = false;
            if (!empty($arrRoles)) {
                foreach ($arrRoles as $val) {
                    if ($menu['authen'] == $val) {
                        $can = true;
                        break;
                    }
                }
            }
            if ($can) {
                $arrMenu [] = $menu;
            }
        }

        echo $this->render('sidebar', [
            'arrMenu' => $arrMenu
        ]);
    }
}

