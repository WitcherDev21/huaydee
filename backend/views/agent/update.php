<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div class="widget-box">
	<div class="widget-title bg_lg">
		<span class="icon"><i class="icon-thumbs-up"></i></span>
		<h5>รายการเอเยนต์</h5>
	</div>

        <br><br>
            
    <?php
    $form = ActiveForm::begin([
        'id' => 'form-users',
        'action' => Yii::$app->urlManager->createUrl(['agent/save/'.$dataUser->id]),
    ]); 
    ?>
        <div class="row ">
            <div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3">
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'username')->textInput(['maxlength'=>'30', 'class'=>'form-control', 'value'=>$dataUser->username, 'disabled'=>true ]); ?>
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
                            <?php echo Html::submitButton('บันทึก', ['class' => 'btn btn-primary', 'name' => 'btnSave', 'id' => 'btnSave']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

        <br><br>
            
</div>

