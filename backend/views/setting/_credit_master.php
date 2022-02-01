<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\libs\Constants;

$js = <<<EOT
$("body").on("submit", "form", function() {
    $(this).submit(function() {
        return false;
    });
    return true;
});
EOT;
$this->registerJs($js);
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
        'id' => 'form-credit-master-in',
        'action' => Yii::$app->urlManager->createUrl(['setting/credit-master']),
    ]);
    ?>
        <div class="row ">
            <div class="col-lg-12 col-md-12">
                <h2>Credit Master</h2>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-9 col-md-9">
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelCreditMasterIn, 'income')->textInput(['class'=>'form-control']); ?>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div style="padding-top: 24px;">
                            <?php echo Html::submitButton('เติม', ['class' => 'btn btn-primary', 'name' => 'btnSaveIn', 'id' => 'btnSaveIn']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
        
    <?php
    $form = ActiveForm::begin([
        'id' => 'form-credit-master-out',
        'action' => Yii::$app->urlManager->createUrl(['setting/credit-master']),
    ]);
    ?>
        <div class="row ">
            <div class="col-lg-9 col-md-9">
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelCreditMasterOut, 'outcome')->textInput(['class'=>'form-control']); ?>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div style="padding-top: 24px;">
                            <?php echo Html::submitButton('ถอน', ['class' => 'btn btn-primary', 'name' => 'btnSaveOut', 'id' => 'btnSaveOut']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

    <?php Pjax::begin(['id' => 'countries-credit-master', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>

        <div class="table-responsive">
            <?php echo GridView::widget([
                'dataProvider' => $dataCreditmaster,
                'tableOptions' => ['style' => 'font-size: 12px;','class' => 'table table-bordered table-striped table-hover'],
                'columns' => [
                    [
                        'label' => 'เลขที่ทำรายการ',
                        'headerOptions' => ['class' => 'text-center'],
                        'value' => function ($model, $key, $index, $column) {
//                            return $model->id;
                            return $model->getOrderId();
                        }
                    ],
                    [
                        'label' => 'รายละเอียด',
                        'format'=>'raw',
                        'attribute' => 'poster_id',
                        'value' => function ($model, $key, $index, $column) {				
                            $color = Constants::$reason_credit_color[$model->reason_action_id];
                            $text = (in_array($model->action_id, [Constants::action_credit_master_top_up,Constants::action_credit_master_withdraw]) ? Constants::$action_credit[$model->action_id] : Constants::$reason_credit[$model->reason_action_id]);
                            return '<label" style="padding:4px; color:#ffffff; background:'.$color.';">'.$text.'</label>';
                        }
                    ],	
                    [
                        'label' => 'ผู้กระทำ',
                        'format' => 'html',
                        'value' => function ($model, $key, $index, $column) {
                            return $model->operator->username ?? null;
                        }
                    ],
                    [ 		
                        'label' => 'เครดิต',
                        'format'=>'html',
                        'value' => function($model, $key, $index, $column){
                            if(in_array($model->action_id,[Constants::action_credit_withdraw,Constants::action_credit_withdraw_admin,Constants::action_credit_master_withdraw])){
                                    return '<div style="color:#ff0000"> - '.number_format($model->amount,2).'</div>';
                            }else if(in_array($model->action_id,[Constants::action_credit_top_up,Constants::action_credit_top_up_admin,Constants::action_credit_master_top_up])){
                                    return '<div style="color:#5eba7d"> '.number_format($model->amount,2).'</div>';
                            }
                        }
                    ],
                    [
                        'label' => 'เครดิตคงเหลือ',
                        'value' => function($model, $key, $index, $column){
                            return number_format($model->credit_master_balance,2);
                        }
                    ],
                    [
                        'label' => 'วันที่ทำรายการ',
                        'value' => function($model, $key, $index, $column){
                            return date('d/m/Y H:i',strtotime($model->create_at));
                        }
                    ],
                ],
            ]); ?>
        </div>
    <?php Pjax::end() ?>
        
        
        
        
        


