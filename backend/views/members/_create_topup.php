<?php
/* @var $dataUser */

use common\libs\Constants;
use yii\bootstrap\ActiveForm;
use common\models\UserHasBankSearch;
?>
<div style="padding: 20px;">
    <div class="row">
        <div class="post-credit-transection-form">
            <?php
            $form = ActiveForm::begin([
                'id' => 'form-topup',
                'action' => Yii::$app->urlManager->createUrl(['members/savetopup']),
            ]); 
            ?>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <div class="form-group">
                            <label class="control-label">User Name</label>
                            <br>
                            <?php
                            echo $dataUser->username;
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <div class="form-group">
                            <label class="control-label">ธนาคาร</label>
                            <br>
                            <?php
                            $BankAccount = UserHasBankSearch::getBankAccountUser($dataUser->username);
                            if(!empty($BankAccount) && !empty($BankAccount[0])){
                                $bank = '<img src="'. str_replace('backend', 'frontend', Yii::getAlias('@web')).'/bank/'.$BankAccount[0]['icon'].'" class="bank_icon" style="background-color: '.$BankAccount[0]['color'].';width:20px;">';
                                $bank .= '<stong> ' . ucwords($BankAccount[0]['title']) .' : '.substr_replace($BankAccount[0]['bank_account_no'],'***',3,6).' ('.$BankAccount[0]['bank_account_name'].')'. '</stong>';
                                echo $bank;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($model, 'amount')->textInput(['class'=>'form-control'])->label('ระบุจำนวนเงินที่ต้องการฝาก'); ?>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <?php
                    $model->status = Constants::action_credit_top_up_admin;
                    echo $form->field($model, 'status')->textInput(['class'=>'form-control'])->inline()->radioList([
                        Constants::action_credit_top_up_admin => Constants::$action_credit[Constants::action_credit_top_up_admin],
                        Constants::action_credit_top_up_admin_special => Constants::$action_credit[Constants::action_credit_top_up_admin_special],
                    ]); ?>
                </div>
                <div class="col-lg-12 col-md-12">
                    <?php echo $form->field($model, 'remark')->textarea(['class'=>'form-control','rows'=>4])->label('ระบุหมายเหตุ'); ?>
                </div>
                <?php echo $form->field($model, 'user_id')->hiddenInput(['class'=>'form-control','value'=>$dataUser->id])->label(false); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>



