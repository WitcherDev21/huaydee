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
        'id' => 'form-play-type',
        'action' => Yii::$app->urlManager->createUrl(['setting/play_type']),
    ]);
    ?>
        <div class="row ">
            <div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3">
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelGameBenefit, 'post_order_1')->textInput(['class'=>'form-control','value'=>(!empty($dataGameBenefit['post_order_1']) ? $dataGameBenefit['post_order_1'] : '')]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <?php echo $form->field($modelGameBenefit, 'post_order_16')->textInput(['class'=>'form-control','value'=>(!empty($dataGameBenefit['post_order_16']) ? $dataGameBenefit['post_order_16'] : '')]); ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-8 col-md-8">
                        <div class="pull-right">
                            <?php echo Html::submitButton('บันทึก', ['class' => 'btn btn-primary', 'name' => 'btnSavePlayType', 'id' => 'btnSavePlayType']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>




