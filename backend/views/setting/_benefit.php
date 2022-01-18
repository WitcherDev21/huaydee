<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\libs\Constants;
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
        'id' => 'form-benefit',
        'action' => Yii::$app->urlManager->createUrl(['setting/benefit']),
    ]);
    
    ?>
        <div class="row ">
            <div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3">
                <div class="col-lg-12 col-md-12 page-titel">
                    <h3>จับยี่กี่</h3>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelSettingBenefitForm, 'yeekee_value')->textInput(['class'=>'form-control','value'=>(!empty($yeekee->value) ? $yeekee->value : '')]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelSettingBenefitForm, 'yeekee_status')->dropDownList(Constants::$setting_benefit, ['class'=>'form-control', 'options'=>(isset($yeekee->status) ? [$yeekee->status => ['selected ' => true]] : '')]); ?>
                    </div>
                </div>
<!--                <div class="col-lg-12 col-md-12 page-titel">
                    <h3>ดำ-แดง</h3>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php // echo $form->field($modelSettingBenefitForm, 'blackred_value')->textInput(['class'=>'form-control','value'=>(!empty($blackred->value) ? $blackred->value : '')]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php // echo $form->field($modelSettingBenefitForm, 'blackred_status')->dropDownList(Constants::$setting_benefit, ['class'=>'form-control', 'options'=>(isset($blackred->status) ? [$blackred->status => ['selected ' => true]] : '')]); ?>
                    </div>
                </div>-->
                
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <div class="pull-right">
                            <?php echo Html::submitButton('บันทึก', ['class' => 'btn btn-primary', 'name' => 'btnSaveBenefit', 'id' => 'btnSaveBenefit']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>




