<?php
/* @var $credit string */

use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsVar('credit_global', $credit, \yii\web\View::POS_HEAD);
?>
<nav id="sidebar">
    <div id="dismiss">
        <i class="fas fa-backspace fa-flip-horizontal"></i>
    </div>
    <div class="sidebar-header">
        <div class="align-center text-center">
            <img src="<?= Yii::getAlias('@web/version6/images/user-128.png') ?>" alt="" class="rounded-circle"
                 width="110" height="100"
                 onerror="this.src='<?= Yii::getAlias('@web/version6/images/user-128.png') ?>'"><br>
            <span class="username"><?= Yii::$app->user->identity->username ?></span><br>
            <i class="fas fa-coins"></i>
            <span class="badge badge-pill badge-danger sidebar thb" data-id="credit_balance"><?= $credit ?></span>
        </div>
    </div>
    <ul class="list-unstyled components">
        <li>
            <a href="<?= Url::to(['info/user']) ?>">
                <i class="fas fa-user-cog"></i>
                ตั้งค่าบัญชีผู้ใช้</a>
        </li>
        <li>
            <a href="<?= Url::to(['setting/bank']) ?>">
                <i class="fas fa-money-check"></i>
                บัญชีธนาคาร</a>
        </li>
        <li>
            <a href="<?= Url::to(['news/how-to']) ?>">
                <i class="fas fa-info-circle"></i>
                วิธีใช้งาน </a>
        </li>
        <li>
            <a href="#" data-toggle="modal" data-target="#contactbox">
                <i class="fas fa-handshake"></i>
                ศูนย์ช่วยเหลือ</a>
        </li>
        <li>
            <div class="mobile-view">
                <div class="dropdown bootstrap-select form-control">
                    <select id="lang-mobile" data-show-content="true" class="selectpicker form-control" data-style="btn-block" data-container="body" style="background:transparents" onchange="javascript:window.location.href='/lgsw/lang/'+this.value;" tabindex="-98">
                        <option data-content="<span class='flag-icon flag-icon-th'></span> ไทย" selected="selected" value="thai"></option>
                    </select>
                </div>
            </div>
        </li>
    </ul>

    <ul class="list-unstyled CTAs">
        <li>
            <?= Html::a('<i class="fas fa-sign-out-alt"></i> ออกจากระบบ', ['user/security/logout'], ['data' => ['method' => 'post'], 'class' => 'logout']) ?>
        </li>
    </ul>
</nav>