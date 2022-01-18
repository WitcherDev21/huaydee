<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\libs\Constants;
?>

<div class="widget-box">
	<div class="widget-title bg_lg">
		<span class="icon"><i class="icon-thumbs-up"></i></span>
		<h5>รายการผู้ช่วย</h5>
	</div>

        <br><br>
            
    <?php
    $form = ActiveForm::begin([
        'id' => 'form-users',
        'action' => Yii::$app->urlManager->createUrl(['assistant/save/'.$dataUser->id]),
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
                <?php
                $identity = \Yii::$app->user->getIdentity();
                if($identity->auth_roles_id == Constants::auth_roles_super_admin || $identity->auth_roles_id == Constants::auth_roles_admin){
                    $modelUsers->user_roles_id = $dataUser->auth_roles_id;
                ?>
                    
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'user_roles_id')->dropDownList($RolesList, ['class'=>'form-control']); ?>
                    </div>
                </div>
                
                <?php
                }
                ?>
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

