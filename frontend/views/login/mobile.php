<?php


use yii\widgets\ActiveForm;

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=7"/>
    <script language="javascript" type="text/javascript"
            src="<?= Yii::getAlias('@web/theme-login/Script/Common.js?v=1') ?>"></script>
    <script language="javascript" type="text/javascript"
            src="<?= Yii::getAlias('@web/theme-login/Script/General.js') ?>"></script>
    <link href="<?= Yii::getAlias('@web/theme-login/Style/general.css') ?>" rel="stylesheet" type="text/css"
          media="all"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>
    <title>
    RUAYDEE96.COM
    </title>
    <link href="<?= Yii::getAlias('@web/theme-login/css/ss26092018.css?ver=5') ?>" rel="stylesheet"/>

    <!--JS-->
    <script src="<?= Yii::getAlias('@web/theme-login/js/jquery.js') ?>"></script>
    <script src="<?= Yii::getAlias('@web/theme-login/js/jquery-migrate-1.2.1.min.js') ?>"></script>
    <script src='<?= Yii::getAlias('@web/theme-login/js/device.min.js') ?>'></script>
    <script type="text/javascript" src="<?= Yii::getAlias('@web/theme-login/js/modernizr.custom.79639.js') ?>"></script>

    <style>
        #btnLogin {
            background: #155995;
            background: -moz-linear-gradient(top, #155995 0%, #213b7e 100%);
            background: -webkit-gradient(left top, left bottom, color-stop(0%, #155995), color-stop(100%, #213b7e));
            background: -webkit-linear-gradient(top, #155995 0%, #213b7e 100%);
            background: -o-linear-gradient(top, #155995 0%, #213b7e 100%);
            background: -ms-linear-gradient(top, #155995 0%, #213b7e 100%);
            background: linear-gradient(to bottom, #155995 0%, #213b7e 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#155995', endColorstr='#213b7e', GradientType=0);
            font-size: 20px;
            font-weight: bold;
            color: #ebca5a;
            text-shadow: 2px 2px #0000008c;
            box-shadow: inset 1px 1px #ffffff6b;
        }

        .help-block {
            color: red;
        }
    </style>
</head>
<body>
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]) ?>
<div class="container">
    <div class="header" style="background: url(<?= Yii::getAlias('@web/theme-login/pic/user3_4.png') ?>);">
        <div class="logo">
            <img src="<?= Yii::getAlias('@web/theme-login/pic/logo.png?ver=2') ?>"/>
        </div>
        <div class="hr">
            <img src="<?= Yii::getAlias('@web/theme-login/pic/hr.png?ver=2') ?>"/>
        </div>

    </div>
    <div class="viewopt">
        <div class="version">
            <a href="#"> <img
                        src="<?= Yii::getAlias('@web/theme-login/pic/desktop1.png?ver=2') ?>"/><br/>
                <span>Desktop Version</span></a>
        </div>
        <div class="version">
            <a href="#"><img
                        src="<?= Yii::getAlias('@web/theme-login/pic/mobile1.png?ver=2') ?>"/><br/>
                <span>Mobile Version</span></a>
        </div>
    </div>
    <div class="user" style="background:url(<?= Yii::getAlias('@web/theme-login/pic/user3.png') ?>);">
        <div class="user-container">
            <?= $form->field($model, 'login',
                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'cPass', 'tabindex' => '1']]
            )->input('text', ['required' => true])->textInput(['placeholder' => 'Login ID', 'style' => 'width:100%'])->label(false);
            ?>
            <?= $form->field(
                $model,
                'password',
                ['inputOptions' => ['class' => 'cLogin', 'tabindex' => '2']])
                ->passwordInput(['placeholder' => 'Password', 'style' => 'width:100%'])
                ->label(false) ?>
            <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '3'])->label(false) ?>
            <button type="submit" id="btnLogin" name="login">Login</button>
            <div class="link t-center"><a
                        href="#"
                        class="t-center">สมัครสมาชิก</a></div>
        </div>

        <div class="images">
            <img class="w100" src="<?= Yii::getAlias('@web/theme-login/pic/1000/bpay1.jpg?ver=2') ?>"/>
        </div>


        <div class="images">
            <img class="w100" src="<?= Yii::getAlias('@web/theme-login/pic/1000/bplay.jpg?ver=2') ?>"/>
        </div>

    </div>
    <div class="footer">
        </br>
        <div class="copyright">
            <p>© 2021 RUAYDEE96.COM - All Rights Reserved.</p><br/><br/><br/><br/><br/>
        </div>
    </div>
    <div class="footer-nav">
        <ul>
            <li><a href="<?= \yii\helpers\Url::to(['user/login']) ?>"><img
                            src="<?= Yii::getAlias('@web/theme-login/pic/nav1_1.png?ver=2') ?>"/></a>
            </li>
            <li><a href="#"><img
                            src="<?= Yii::getAlias('@web/theme-login/pic/nav2_2.png?ver=2') ?>"/></a>
            </li>
            <li><a href="#"><img
                            src="<?= Yii::getAlias('@web/theme-login/pic/nav3_3.png?ver=2') ?>"/></a>
            </li>
            <li><a href="#"><img
                            src="<?= Yii::getAlias('@web/theme-login/pic/nav4_4.png?ver=2') ?>"/></a></li>
            <li><a href="#" target="_blank"><img
                            src="<?= Yii::getAlias('@web/theme-login/pic/nav5_5.png?ver=2') ?>"/></a></li>
        </ul>
    </div>
</div>
<?php ActiveForm::end(); ?>
</body>
</html>