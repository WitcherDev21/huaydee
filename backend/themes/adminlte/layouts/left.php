<?php

use backend\component\CoreFunctions;
use common\libs\Constants;
use common\models\AuthRoles;
use yii\helpers\Url;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <?php
        $identity = \Yii::$app->user->getIdentity();
        $uri = Yii::$app->controller->getRoute ();
        $arrMenu = [];

        if (!empty($identity)) {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
        }
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
        } ?>
        <ul class="sidebar-menu tree" data-widget="tree">
        <?php
        foreach ( $arrMenu as $menu ) {
            $ch = in_array ( $uri, $menu ['group'] );
            $active = '';
            $select = '';
            if ($ch) {
                $active = 'active';
                $select = 'selected';
            }
            ?>

            <li class="<?= $active?>">
                <a href="<?= Url::toRoute([$menu['uri']]);?>">
                    <i class="<?= $menu['icon']?>"></i>
                    <span><?= $menu['title']?> </span>
                    <?php echo (!empty($menu['call_function']) ? CoreFunctions::_call($menu['call_function']) : ''); ?>
                </a>
            </li>

            <?php
        }
        ?>
            </ul>
    </section>

</aside>
