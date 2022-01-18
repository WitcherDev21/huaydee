<?php
/* @var $credit string */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="sticky-top">
    <div class="topnavbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="<?= Url::to(['site/home'])?>" title="covidlotto">
                        <div class="memberlogo">
                            <div class="logomember">
                                <a href="<?= Url::to(['site/home']) ?>">
                                    <img src="<?= Yii::getAlias('@web/version6/images/demolotto.png') ?>"
                                         style="height: 45px; position: relative; top: -10px; left: -10px;"
                                         alt="FIFALOTTO.COM" title="FIFALOTTO" id="logofull"/>
                                </a>
                                <a href="<?= Url::to(['site/home']) ?>">
                                    <img src="<?= Yii::getAlias('@web/version6/images/icon-lottovip-64.jpg') ?>"
                                         alt="FIFALOTTO.COM" title="FIFALOTTO" id="logosymbol"/>
                                </a>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="d-flex flex-row justify-content-between align-items-center">
                    <div id="menu-pc" class="d-flex justify-content-between align-items-center pc-view">
                        <a href="<?= Url::to(['site/home']) ?>" data-toggle="tooltip"
                           data-placement="top"
                           title="หน้าแรก" data-id="lotto">
                            <i class="sn-icon sn-icon--home2"></i>
                        </a>
                        <a href="<?= Url::to(['post-credit-transection/create-topup']) ?>" data-toggle="tooltip"
                           data-placement="top"
                           title="เติมเงิน" data-id="refill">
                            <i class="fas fa-wallet"></i>
                        </a>
                        <a href="<?= Url::to(['lotto/report']) ?>" data-toggle="tooltip"
                           data-placement="top"
                           title="ผลรางวัล" data-id="award">
                            <i class="fas fa-award"></i>
                        </a>

                        <div class="linemenu-x"></div>
                    </div>

                    <div id="re-credit">
                        <span class="badge badge-pill badge-light thb" data-id="credit_balance"><?= $credit ?></span>
                    </div>
                    <a href="#" data-toggle="modal" data-target="#contactbox">
                        <div class="btn-line"></div>
                    </a>

                    <div id="sidebarCollapse" class="mobile-view">
                        <img src="<?= Yii::getAlias('@web/version6/images/user-128.png') ?>"
                             onerror="this.src='<?= Yii::getAlias('@web/version6/images/user-128.png') ?>'"
                             alt=""
                             class="rounded-circle ml-1" width="28" height="28" title="บัญชีผู้ใช้">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>

                    <div class="dropdown pc-view ml-1">
                        <div class="dropdown-toggle" id="menu-profile" data-toggle="dropdown"
                             aria-haspopup="true"
                             aria-expanded="true">
                            <img src="<?= Yii::getAlias('@web/version6/images/user-128.png') ?>"
                                 onerror="this.src='<?= Yii::getAlias('@web/version6/images/user-128.png') ?>'"
                                 alt=""
                                 class="rounded-circle" width="40" height="35"> <span
                                    id="username"><?= Yii::$app->user->identity->username ?></span>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="menu-profile"
                             id="menu-profile-dropdown">
                            <a class="dropdown-item"
                               href="<?= Url::to(['info/user']) ?>">
                                <i class="fas fa-user-cog"></i> ตั้งค่าบัญชีผู้ใช้ </a>
                            <a class="dropdown-item" href="<?= Url::to(['setting/bank']) ?>">
                                <i class="fas fa-money-check"></i> บัญชีธนาคาร </a>
                            <a class="dropdown-item" href="<?= Url::to(['news/how-to']) ?>">
                                <i class="fas fa-info-circle"></i> วิธีใช้งาน </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#contactbox">
                                <i class="fas fa-handshake"></i> ศูนย์ช่วยเหลือ </a>
                            <div class="dropdown-divider"></div>
                            <?= Html::a('<i class="fas fa-sign-out-alt"></i> ออกจากระบบ', ['user/security/logout'], ['data' => ['method' => 'post'], 'class' => 'dropdown-item']) ?>
                        </div>
                    </div>
                    <div class="pc-view">
                        <div class="dropdown bootstrap-select form-control ml-2">
                            <select id="lang-pc" data-show-content="true" class="selectpicker form-control ml-2" data-style="btn-sm" data-container="body" style="background:transparents" tabindex="-98">
                                <option data-content="<span class='flag-icon flag-icon-th'></span> ไทย" selected="selected" value="thai"></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="navbar">
    <div class="container">
        <div class="d-flex flex-row justify-content-between w-100">
            <div class="notice-bar flex-fill">
                <div class="txt-notice">
                    <ul id="marquee1" class="marquee">
                        <li>&nbsp;ยินดีต้อนรับทุกท่านเข้าสู่เว็บ RUAYDEE96.COM เว็บหวยออนไลน์ที่มาแรงที่สุดในตอนนี้</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>