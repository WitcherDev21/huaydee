<?php
/* @var $dataUser */
use common\libs\Constants;
use yii\bootstrap\ActiveForm;
?>
<div style="padding: 20px;">
    <div class="row">
        <div class="post-credit-transection-form">
            <?php
            $form = ActiveForm::begin([
                'id' => 'form-withdraw',
                'action' => Yii::$app->urlManager->createUrl(['members/savewithdraw']),
            ]); 
            ?>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($model, 'amount')->textInput(['class'=>'form-control'])->label('ระบุจำนวนเงินที่ต้องการถอน'); ?>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <?php
                        $model->status = Constants::action_credit_withdraw_admin;
                        echo $form->field($model, 'status')->textInput(['class'=>'form-control'])->inline()->radioList([
                            Constants::action_credit_withdraw_admin => Constants::$action_credit[Constants::action_credit_withdraw_admin],
                            Constants::action_credit_withdraw_admin_special => Constants::$action_credit[Constants::action_credit_withdraw_admin_special],
                        ]); ?>
                    </div>
                </div>             
                <div class="col-lg-12 col-md-12">
                    <?php echo $form->field($model, 'remark')->textarea(['class'=>'form-control','rows'=>4])->label('ระบุหมายเหตุ'); ?>
                </div>
                <?php echo $form->field($model, 'poster_id')->hiddenInput(['class'=>'form-control','value'=>$dataUser->id])->label(false); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>