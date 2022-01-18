<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\libs\Constants;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-sm-6 col-md-6 col-xs-12 col-sm-offset-6 col-md-offset-6">
        <div class="post-credit-transection-search">

            <br>
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'options' => ['class' => 'form-horizontal']//'data-pjax' => true ,
            ]);
            ?>
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 control-label">รายละเอียด</label>
                        <div class="col-md-6  col-sm-6">
                            <?php
                            if ($tab == '1' || $tab == '2') {
                                echo Html::activeDropDownList($searchModel, 'filter_detail', [
                                    '' => 'ทั้งหมด',
                                    Constants::action_credit_top_up => Constants::$action_credit[Constants::action_credit_top_up],
                                    Constants::action_credit_withdraw => Constants::$action_credit[Constants::action_credit_withdraw],
                                    Constants::action_credit_top_up_admin => Constants::$action_credit[Constants::action_credit_top_up_admin],
                                    Constants::action_credit_withdraw_admin => Constants::$action_credit[Constants::action_credit_withdraw_admin],
                                    Constants::action_credit_master_top_up => Constants::$action_credit[Constants::action_credit_master_top_up],
                                    Constants::action_credit_master_withdraw => Constants::$action_credit[Constants::action_credit_master_withdraw]
                                ], ['class' => 'form-control']);
                            } else {
                                echo Html::activeDropDownList($searchModel, 'filter_detail', [
                                    '' => 'ทั้งหมด',
                                    Constants::action_credit_top_up_admin => Constants::$action_credit[Constants::action_credit_top_up_admin],
                                    Constants::action_credit_withdraw_admin => Constants::$action_credit[Constants::action_credit_withdraw_admin]
                                ], ['class' => 'form-control']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 control-label">ผู้กระทำ</label>
                        <div class="col-md-6  col-sm-6">
                            <?php
                            echo Html::activeInput('text', $searchModel, 'filter_operator', ['class' => 'form-control']);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="hidden" name="tab" value="<?php echo $tab; ?>">
                    <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
                </div>
            </div>
            <?php
            $uri = Yii::$app->controller->getRoute();
            if ($uri == 'post-credit-member/list-current') {
                echo Html::activeHiddenInput($searchModel, 'create_at');
            } ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
