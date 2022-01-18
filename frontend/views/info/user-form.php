<?php

/* @var $userForm \common\models\UsersForm */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="bar-back my-2 radius-15 border">
    <a href="<?= Url::to(['site/home']) ?>" class="text-blue-2"><i class="fas fa-chevron-left"></i> ย้อนกลับ</a>
</div>
<div class="main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px); margin-bottom: 70px">
    <div
        class="bgwhitealpha text-secondary shadow-sm rounded my-2 radius-15 border p-2 py-3 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
        <div class="lotto-title">
            <h4><i class="fas fa-user-cog"></i> <b>ตั้งค่าบัญชีผู้ใช้</b></h4>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'form-user',
        'options' => ['class' => 'form-horizontal'],
        'action' => Url::toRoute(['info/user']),
        'method' => 'post'
    ]) ?>
    <input type="hidden" name="csrf_token" value="d2dbce259dede63e1f4cd88b1cffbc6b">
    <input type="hidden" name="updateprofile" value="1">
    <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget mb-5">
        <div class="row profilesetting">
            <div class="col-12">
                <h6><i class="fas fa-user-circle"></i> จัดการโปรไฟล์</h6>
                <div class="form-group text-center">
                    <label for="image" class="button-upload-file">
                        <div class="preview">
                            <img id="photo" src="<?= Yii::getAlias('@web/version6/images/user-128.png') ?>" width="80"
                                onerror="this.src='<?= Yii::getAlias('@web/version6/images/user-128.png') ?>'">
                        </div>
                    </label>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12">
                <b>ชื่อผู้ใช้งาน</b>
                <small>username</small>
                <input type="text" class="form-control" value="<?= $user->username ?>" readonly="">
            </div>
            <div class="col-12 col-sm-12 col-md-4">
                <b>อีเมล์</b>
                <input type="text" class="form-control" value="<?= $user->email ?>" readonly="">
            </div>
            <div class="col-12 col-sm-12 col-md-4">
                <b>เบอร์โทรศัพท์</b>
                <?php echo $form->field($userForm, 'tel')->textInput([
                    'maxlength' => '10',
                    'class' => 'form-control',
                    'value' => $user->tel,
                ])->label(false); ?>
            </div>
            <div class="w-100 m-3 border-bottom"></div>
            <div class="col-12">
                <h6><i class="fas fa-user-circle"></i>แก้ไขรหัสผ่าน</h6>
            </div>
            <div class="col-12" id="newpass">
                <b>รหัสผ่านใหม่</b>
                <?php echo $form->field($userForm, 'change_password')->passwordInput([
                    'maxlength' => '30',
                    'class' => 'form-control bg-gray-light'
                ])->label(false); ?>
            </div>
            <div class="col-12" id="renewpass">
                <b>ยืนยันรหัสผ่านใหม่</b>
                <?php echo $form->field($userForm, 'change_confirm_password')->passwordInput([
                    'maxlength' => '30',
                    'class' => 'form-control bg-gray-light'
                ])->label(false); ?>
            </div>
            <div class="w-100 m-3 border-bottom"></div>
            <div class="col-12">
                <button class="btn btn-block bg-blue border-0 text-white" type="submit" id="profilesubmit">
                    <i class="fas fa-save"></i> บันทึก
                </button>
            </div>
        </div>

    </div>
    <?php ActiveForm::end() ?>
</div>