<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><span class="glyphicon glyphicon-user"></span></span>
        <h5>แก้ไขข้อมูลสมาชิก</h5>
    </div>
    <div class="widget-content ">
        <?php
        $form = ActiveForm::begin([
            'id' => 'form-user',
            'action' => Yii::$app->urlManager->createUrl(['members/saveupdate/' . $dataUser->id]),
        ]);
        ?>
        <div class="row ">
            <div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3">
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'username')->textInput(['maxlength' => '30', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $dataUser->username]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'change_password')->passwordInput(['maxlength' => '30', 'class' => 'form-control']); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'change_confirm_password')->passwordInput(['maxlength' => '30', 'class' => 'form-control']); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'email')->textInput(['class' => 'form-control', 'value' => $dataUser->email]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'tel')->textInput(['class' => 'form-control', 'value' => $dataUser->tel]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php
                        $modelUsers->bank_id = (!empty($dataUser->userHasBanks[0]) ? $dataUser->userHasBanks[0]->bank_id : '');
                        ?>
                        <?php
                        if (!empty($dataUser->userHasBanks[0])) {
                            echo $form->field($modelUsers, 'user_has_bank_id[]')->hiddenInput(['value' => $dataUser->userHasBanks[0]->id])->label(false);
                            echo $form->field($modelUsers, 'bank_id[]')->dropDownList($BankList, [
                                'prompt' => 'Select...',
                                'class' => 'form-control',
                                'options' => [
                                    $dataUser->userHasBanks[0]->bank_id => ['selected' => true]
                                ]
                            ]);
                        } else {
                            echo $form->field($modelUsers, 'user_has_bank_id[]')->hiddenInput(['value' => 0])->label(false);
                            echo $form->field($modelUsers, 'bank_id[]')->dropDownList($BankList, [
                                'prompt' => 'Select...',
                                'class' => 'form-control',
                            ]);
                        } ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'bank_account_name[]')->textInput(['class' => 'form-control', 'value' => (!empty($dataUser->userHasBanks[0]) ? $dataUser->userHasBanks[0]->bank_account_name : '')]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'bank_account_no[]')->textInput(['class' => 'form-control', 'value' => (!empty($dataUser->userHasBanks[0]) ? $dataUser->userHasBanks[0]->bank_account_no : '')]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php
                        $modelUsers->bank_id = (!empty($dataUser->userHasBanks[1]) ? $dataUser->userHasBanks[1]->bank_id : '');
                        ?>
                        <?php
                        if (!empty($dataUser->userHasBanks[1])) {
                            echo $form->field($modelUsers, 'user_has_bank_id[]')->hiddenInput(['value' => $dataUser->userHasBanks[1]->id])->label(false);
                            echo $form->field($modelUsers, 'bank_id[]')->dropDownList($BankList, [
                                'prompt' => 'Select...',
                                'class' => 'form-control',
                                'options' => [
                                    $dataUser->userHasBanks[1]->bank_id => ['selected' => true]
                                ]
                            ]);
                        } else {
                            echo $form->field($modelUsers, 'user_has_bank_id[]')->hiddenInput(['value' => 0])->label(false);
                            echo $form->field($modelUsers, 'bank_id[]')->dropDownList($BankList, [
                                'prompt' => 'Select...',
                                'class' => 'form-control',
                            ]);
                        } ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'bank_account_name[]')->textInput(['class' => 'form-control', 'value' => (!empty($dataUser->userHasBanks[1]) ? $dataUser->userHasBanks[1]->bank_account_name : '')]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'bank_account_no[]')->textInput(['class' => 'form-control', 'value' => (!empty($dataUser->userHasBanks[1]) ? $dataUser->userHasBanks[1]->bank_account_no : '')]); ?>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="pull-right">
                        <?php echo Html::submitButton('บันทึก', ['class' => 'btn btn-primary', 'name' => 'btnSave', 'id' => 'btnSave']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
