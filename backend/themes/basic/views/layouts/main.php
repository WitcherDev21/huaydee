<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\AuthRoles;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            margin-top: -20px;
        }
        .navbar-inverse .brand, .navbar-inverse .nav > li > a {
            color: #999;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<div id="spinner" class="spinner"></div>
<?php
$flag_mes = 1;
$uri = Yii::$app->controller->getRoute();
$modelCreditTransection = new common\models\CreditTransectionSearch();
?>
<div id="header">
    <h1><a href="index.php">logo.png</a></h1>
</div>
<div id="user-nav" class="navbar navbar-inverse">
    <ul class="nav">
        <li class="dropdown" id="profile-messages"><a title="" href="#" data-toggle="dropdown"
                                                      data-target="#profile-messages" class="dropdown-toggle"><i
                        class="icon icon-user"></i> <span class="text">ยินดีต้อนรับ ผู้ใช้งาน</span><b
                        class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo Yii::$app->urlManager->createUrl(['setting/user-account']); ?>"><i
                                class="icon-user"></i> บัญชีผู้ใช้</a></li>
                <li class="divider"></li>
                <li class="divider"></li>
                <li><a href="logout_web.php"><i class="icon-key"></i> ออกจากระบบ</a></li>
            </ul>
        </li>
        <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages"
                                                   class="dropdown-toggle"><i class="icon icon-envelope"></i> <span
                        class="text">ข้อความ</span> <span class="label label-important">0</span> <b
                        class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a class="sAdd" title="" href="new_messages.php"><i class="icon-plus"></i> ข้อความใหม่</a></li>
                <li class="divider"></li>
                <li><a class="sInbox" title="" href="inbox_messages.php"><i class="icon-envelope"></i> กล่องข้อความ</a>
                </li>
                <li class="divider"></li>
                <li class="divider"></li>
            </ul>
        </li>
        <?php
        $arrRoles = [];
        $identity = \Yii::$app->user->getIdentity();
        if (!empty($identity)) {
            $modelAuthRoles = new AuthRoles();
            $arrRoles = $modelAuthRoles->_getRoles($identity->auth_roles_id);
            $arrRoles = empty($arrRoles) ? [] : $arrRoles;
        }
        if (in_array('setting', $arrRoles)) {
            ?>
            <li class="<?= $uri == 'setting/index' ? 'active' : '' ?>"><a title=""
                                                                          href="<?= Url::to(['setting/index']) ?>"><i
                            class="icon icon-cog"></i> <span class="text">การตั้งค่า</span></a></li>
            <?php
        }
        ?>
        <li class=""><?= Html::a('<i class="icon icon-share-alt"></i> <span class="text">ออกจากระบบ</span>', ['user/security/logout'], ['data' => ['method' => 'post']]) ?></li>

    </ul>
</div>
<?= \backend\component\Sidebar::widget() ?>
<div id="content">
    <div class="container-fluid">
        <div class="row">
            <h3 style="margin-top: 25px; margin-left: 15px; margin-right: 15px; color: #337ab7; font-weight: bold;"
                class="pull-right CreditMasterBalance">Credit
                : <?php echo number_format($modelCreditTransection->getCreditMasterBalance(), 2); ?></h3>
        </div>
        <div class="row-fluid">
            <?= $content ?>
        </div>
    </div>
</div>
<div class="row-fluid">
</div>
<div id="sound">
</div>

<?php yii\bootstrap\Modal::begin([
    'headerOptions' => ['class' => 'modal-header-primary'],
    'id' => 'modal-test',
    'header' => '<h4 class="modal-title"></h4>',
    'footer' => '<button class="btn btn-primary"> Save</button> <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>',
]); ?>
<?php yii\bootstrap\Modal::end(); ?>



<?php
$urlGetrealtime = Yii::$app->urlManager->createUrl(['template/getrealtime']);
$fileName = Url::to(['sound/gruzjvik.mp3']);
$script = <<<SCRIPT
$(document).ready(function(){
        
    function getCreditMaster_PostCreditMember(){
        $.ajax({
            url: '$urlGetrealtime',
            dataType: 'json',
            type: 'POST',
            data: {param:['CreditMaster','NewCreditTransectionMember', 'userCountNoActive']},
            success: function (data) {
                if(typeof data['CreditMaster'] !== "undefined"){
                    $('h3.CreditMasterBalance').html(data['CreditMaster']);
                }
                if(typeof data['NewCreditTransectionMember'] !== "undefined"){
                    if (data['NewCreditTransectionMember'] > 0) {
                        playSound();
                    }
                    $('span.AmountNewCreditTransectionMember').html(data['NewCreditTransectionMember']);
                }
                if(typeof data['userCountNoActive'] !== "undefined"){
                    if (data['userCountNoActive'] > 0) {
                        playSound();
                    }
                    $('span.userCountNoActive').html(data['userCountNoActive']);
                }
            }
        });
    }
    

    setInterval(function(){
        getCreditMaster_PostCreditMember(); // this will run after every 5 seconds
    }, 5000);
       
     function playSound(){
        var filename = '$fileName';
        document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '" type="audio/mpeg" /><source src="' + filename + '" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename + '" /></audio>';
     }    
        
        
        
});
SCRIPT;
$this->registerJs($script);
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
