<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="widget-box">
    <div class="widget-title bg_lg">
            <span class="icon"><span class="glyphicon glyphicon-user"></span></span>
            <h5>แก้ไขข้อมูลส่วนตัว</h5>
    </div>
    <div class="widget-content ">
        
        <?php if(count(Yii::$app->session->getAllFlashes()) > 0){ ?>
            <div style="padding-top: 20px;">
            <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message){
                echo yii\bootstrap\Alert::widget([
                    'options' => [
                        'class' => 'alert-'.$message['type'],
                    ],
                    'body' => $message['message'],
                ]);
            } ?>
            </div>
        <?php } ?>
        <?php
        $form = ActiveForm::begin([
            'id' => 'form-user',
            'action' => Yii::$app->urlManager->createUrl(['setting/save-account']),
        ]); 
        ?>
            <div class="row ">
                <div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3">
                    <div class="col-lg-12 col-md-12" style="padding: 0;">
                        <div class="col-lg-8 col-md-8">
                            <?php echo $form->field($modelUsers, 'username')->textInput(['maxlength'=>'30', 'class'=>'form-control', 'readonly'=>'readonly', 'value'=>$dataUser->username]); ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" style="padding: 0;">
                        <div class="col-lg-8 col-md-8">
                            <?php echo $form->field($modelUsers, 'change_password')->passwordInput(['maxlength'=>'30', 'class'=>'form-control']); ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" style="padding: 0;">
                        <div class="col-lg-8 col-md-8">
                            <?php echo $form->field($modelUsers, 'change_confirm_password')->passwordInput(['maxlength'=>'30', 'class'=>'form-control']); ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" style="padding: 0;">
                        <div class="col-lg-8 col-md-8">
                            <?php echo $form->field($modelUsers, 'email')->textInput(['class'=>'form-control', 'value'=>$dataUser->email]); ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" style="padding: 0;">
                        <div class="col-lg-8 col-md-8">
                            <?php echo $form->field($modelUsers, 'tel')->textInput(['class'=>'form-control', 'value'=>$dataUser->tel]); ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" style="padding: 0;">
                        <div class="col-lg-8 col-md-8">
                            <div class="pull-right">
                                <?php echo $form->field($modelUsers, 'id')->hiddenInput(['class'=>'form-control', 'value'=>$dataUser->id])->label(false); ?>
                                <?php echo Html::submitButton('บันทึก', ['class' => 'btn btn-primary', 'name' => 'btnSaveUpdateAccount', 'id' => 'btnSaveUpdateAccount']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
