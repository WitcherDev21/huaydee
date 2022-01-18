<?php
/* @var $arrBank array*/
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
<div id="fb-root"></div>
<div id="app" style="padding-bottom: 50px;">
    <div class="fixed-bg"></div>
    <div class="navbar">
        <div class="container">
            <div class="d-flex flex-row justify-content-between w-100">
                <div class="notice-bar flex-fill">
                    <div class="txt-notice">
                        <ul id="marquee1" class="marquee">
                            <li class="marquee-showing" style="top: 0px; left: 0px;">
                                &nbsp;ยินดีต้อนรับทุกท่านเข้าสู่เว็บ
                                RUAYDEE96.COM เว็บหวยออนไลน์ที่มาแรงที่สุดในตอนนี้
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="topnavbar text-white px-3 py-2">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="indexlogo">
                        <div class="logoindex text-center">
                            <a href="#">
                                <img src="<?= Yii::getAlias('@web/version6/images/demolotto.png') ?>"
                                    class="position-relative" style="top:-10px" alt="RUAYDEE96" title="RUAYDEE96"></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 text-center text-sm-center text-md-right">
                    <h3 class="mt-1 font-weight-light">สมัครสมาชิก</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <section id="contentbox" class="h-100 mb-5">
            <?php
            $form = ActiveForm::begin ( [
                'options' => [
                    'class' => 'form-horizontal'
                ],
                'method' => 'post',
            ] );
            ?>
            <input type="hidden" name="csrf_token" value="3cc334234e97acc14d26d67905461751">
            <input type="hidden" name="register1" value="1">

            <div class="bg-white border shadow-sm rounded p-3 pt-4 h-200 mt-3 mb-2" id="form-register">
                <div class="row">
                    <div class="col-4 col-sm-4 col-md-2 d-flex align-items-center justify-content-md-end">
                        <h6 class="text-dark"><i class="fas fa-mobile-alt"></i> เบอร์โทรศัพท์</h6>
                    </div>
                    <div class="col-8 col-sm-8 col-md-4">
                        <?= $form->field($model, 'tel')->widget(\yii\widgets\MaskedInput::className(), [
                                'mask' => '9999999999', 'options' => [
                                     'style' => 'width: 98%',
                                    'class' => 'form-control'
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 text-right text-sm-right text-md-left">
                        <small class="text-secondary">กรุณาใส่หมายเลขโทรศัพท์เพื่อยืนยันตัวตน</small>
                    </div>
                    <div class="col-4 col-sm-4 col-md-2 d-flex align-items-center justify-content-md-end">
                        <h6 class="text-dark"> username</h6>
                    </div>
                    <div class="col-8 col-sm-12 col-md-4" style="padding-top: 15px;">
                        <?= $form->field($model, 'username',['options'=>[]])->label(false)?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 text-right text-sm-right text-md-left">
                    </div>
                    <div class="col-4 col-sm-4 col-md-2 d-flex align-items-center justify-content-md-end">
                        <h6 class="text-dark"> รหัสผ่าน</h6>
                    </div>
                    <div class="col-8 col-sm-12 col-md-4" style="padding-top: 15px;">
                        <?= $form->field($model, 'password',['options'=>[]])->passwordInput()->label(false)?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 text-right text-sm-right text-md-left">
                    </div>
                    <div class="col-4 col-sm-4 col-md-2 d-flex align-items-center justify-content-md-end">
                        <h6 class="text-dark"> ยืนยันรหัสผ่าน</h6>
                    </div>
                    <div class="col-8 col-sm-12 col-md-4" style="padding-top: 15px;">
                        <?= $form->field($model, 'password_repeat',['options'=>[]])->passwordInput()->label(false)?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 text-right text-sm-right text-md-left">
                    </div>
                    <div class="col-3 col-sm-4 col-md-2 d-flex align-items-center justify-content-md-end">
                        <h6 class="text-dark"> ธนาคาร</h6>
                    </div>
                    <div class="col-9 col-sm-12 col-md-4">

                        <?= $form->field($model, 'bank_id')
                                ->radioList(
                                    $arrBank,
                                    [
                                        'item' => function($index, $label, $name, $checked, $value) {
                                            $id = $label['id'];
                                            $title = $label['title'];
                                            $icon = $label['icon'];
                                            $color = $label['color'];

                                            $return = '<div class="radio">';
                                            $return .= '<label>';
                                            $return .= '<input type="radio" name="' . $name . '" value="' . $id . '" tabindex="3">';
                                            //$return .= '<i></i>';
                                            $return .= '<img src="'.Yii::getAlias('@web').'/bank/'.$icon.'" class="bank_icon mx-1" style="background-color: '.$color.';">';
                                            $return .= '<span> ' . ucwords($title) . '</span>';
                                            $return .= '</label>';
                                            $return .= '</div>';
                                            return $return;
                                        }
                                    ]
                                )
                                ->label(false);
                            ?> 
                        </div>

                    <div class="col-12 col-sm-12 col-md-6 text-right text-sm-right text-md-left">
                        </div>
                    <div class="col-4 col-sm-4 col-md-2 d-flex align-items-center justify-content-md-end">
                        <h6 class="text-dark"> ชื่อบัญชี</h6>
                    </div>
                    <div class="col-8 col-sm-12 col-md-4" style="padding-top: 15px;">
                        <?= $form->field($model, 'bank_account_name', ['options'=>[]])->textInput()->input('bank_account_name', ['placeholder' => "กรุณาระบุชื่อบัญชีให้ตรงกับชื่อบัญชีธนาคาร"])->label(false)?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 text-right text-sm-right text-md-left">
                    </div>
                    <div class="col-4 col-sm-4 col-md-2 d-flex align-items-center justify-content-md-end">
                        <h6 class="text-dark"> เลขที่บัญชี.</h6>
                    </div>
                    <div class="col-8 col-sm-12 col-md-4" style="padding-top: 15px;">
                        <?= $form->field($model, 'bank_account_no',['options'=>[]])->label(false)?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button onclick="location.href='<?= Url::to(['user/login'])?>';"
                        class="btn silver-btn btn-block py-3">
                        <i class="fas fa-chevron-left"></i> มีไอดีแล้วกดที่นี่
                    </button>
                </div>
                <div class="col">
                    <button type="submit" class="btn golden-btn btn-block py-3">&nbsp;&nbsp;&nbsp;สมัครสมาชิก
                        <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </section>
    </div>
    <div class="modal fade" id="ModalRate" tabindex="-1" role="dialog" aria-labelledby="ModalRate" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius:10px;">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">อัตราการจ่าย</h5>
                    <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="/play-huay/frontend/web/version6/images/lotto1-1.jpg"
                        style="max-width: 100%;max-height: 100%;height: inherit !important;"><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('.telinput').focus();">
                        สมัครกดที่นี่
                    </button>
                </div>
            </div>
        </div>
    </div>
    <footer id="footer-member" class="page-footer font-small bg-danger pt-1 mt-5 fixed-bottom pc-view">

        <div class="footer-copyright text-center  py-3">© 2021 Copyright -
            <a href="#" class=""> RUAYDEE96.COM</a>
        </div>

    </footer>
</div>