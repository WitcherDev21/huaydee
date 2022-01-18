<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
?>

<?php
$form = ActiveForm::begin([
    'id' => 'form-accountrefill',
    'action' => Yii::$app->urlManager->createUrl(['transections/accountrefill/save']),
]); 
?>
    <div class="row ">
        <div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3">
            <div class="col-lg-12 col-md-12" style="padding: 0;">
                <div class="col-lg-8 col-md-8">
                    <?php
                    if(!empty($dataUserHasBank)){
                        $modelUserHasBankSearch->bank_id = $dataUserHasBank->bank_id;
                    }
                    ?>
                    <?php echo $form->field($modelUserHasBankSearch, 'bank_id')->dropDownList($BankList, ['prompt' => 'เลือกธนาคาร', 'class'=>'form-control']); ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12" style="padding: 0;">
                <div class="col-lg-8 col-md-8">
                    <?php echo $form->field($modelUserHasBankSearch, 'bank_account_name')->textInput(['class'=>'form-control','value'=>(!empty($dataUserHasBank) ? $dataUserHasBank->bank_account_name : '')]); ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12" style="padding: 0;">
                <div class="col-lg-8 col-md-8">
                    <?php echo $form->field($modelUserHasBankSearch, 'bank_account_no')->textInput(['class'=>'form-control','value'=>(!empty($dataUserHasBank) ? $dataUserHasBank->bank_account_no : '')]); ?>
                </div>
            </div>
            
            <div class="col-lg-12 col-md-12" style="padding: 0;">
                <div class="col-lg-8 col-md-8">
                    <?php echo $form->field($modelUserHasBankSearch, 'description')->textarea(['class'=>'form-control', 'rows'=>4,'value'=>(!empty($dataUserHasBank) ? $dataUserHasBank->description : '')]); ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12" style="padding: 0;">
                <div class="col-lg-8 col-md-8">
                    <div class="pull-right">
                        <?php echo $form->field($modelUserHasBankSearch, 'id')->hiddenInput(['value'=>(!empty($dataUserHasBank) ? $dataUserHasBank->id : '')])->label(false); ?>
                        <?php echo Html::submitButton('บันทึก', ['class' => 'btn btn-primary', 'name' => 'btnSave', 'id' => 'btnSave']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>