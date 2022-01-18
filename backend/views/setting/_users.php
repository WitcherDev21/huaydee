<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

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
        'id' => 'form-users',
        'action' => Yii::$app->urlManager->createUrl(['setting/users']),
    ]); 
    ?>
        <div class="row ">
            <div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3">
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'username')->textInput(['maxlength'=>'30', 'class'=>'form-control']); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'password')->passwordInput(['maxlength'=>'30', 'class'=>'form-control']); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'confirm_password')->passwordInput(['maxlength'=>'30', 'class'=>'form-control']); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'email')->textInput(['class'=>'form-control']); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'tel')->textInput(['class'=>'form-control']); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelUsers, 'user_roles_id')->dropDownList($RolesList, ['prompt' => 'Select...', 'class'=>'form-control']); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <div class="pull-right">
                            <?php echo Html::submitButton('บันทึก', ['class' => 'btn btn-primary', 'name' => 'btnSaveUsers', 'id' => 'btnSaveUsers']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>




