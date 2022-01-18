<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
/* @var $this yii\web\View */
/* @var $model common\models\LimitLotteryNumberGame */
/* @var $form yii\widgets\ActiveForm */
/* @var $playTypes array */
/* @var $maxLengths array s*/

$js = <<<EOT
$('body').on('beforeSubmit', 'form#lottery_lao_set_limit_number', function (e) {
    e.preventDefault();
    var multiple_length = $('.multiple-input-list__item').length;
    var validate = true;
    for (var key = 0; key < multiple_length; key++) {
        
        var limit_number = $('#limitlotterynumbergame-limits-'+key+'-number').val();
        if (limit_number !== 'all') {
            if (!$.isNumeric(limit_number)) {
                validate = false;
            }
            if (limit_number.length != 4) {
               validate = false;
            }
        }
    }
    if (!validate) {
       swal({
        title: 'Warning!',
        text: 'กรุณากรอกข้อมูลให้ถูกต้อง',
        type: 'warning',
        confirmButtonText: 'close'
       });
       return false;
    }
    return true;
});
$('body').on('beforeSubmit', 'form#thai_shared_limit_number', function (e) {
    e.preventDefault();
    var multiple_length = $('.multiple-input-list__item').length;
    var validate = true;
    for (var key = 0; key < multiple_length; key++) {
        
        var play_type = $('#limitlotterynumbergame-limits-'+key+'-playtypeid').val();
        var number_form = $('#limitlotterynumbergame-limits-'+key+'-numberfrom').val();
        var number_to = $('#limitlotterynumbergame-limits-'+key+'-to').val();
        if (play_type == 9 || play_type == 10 || play_type == 19 || play_type == 20 || play_type == 25 || play_type == 26 || 
        play_type == 77 || play_type == 78 || play_type == 83 || play_type == 84 || play_type == 89 || play_type == 90 || 
        play_type == 95 || play_type == 96 || play_type == 101 || play_type == 102  || play_type == 107 || play_type == 108) {
            var number_length = 3;
        }else if(play_type == 11 || play_type == 12 || play_type == 21 || play_type == 22 || play_type == 79 || 
        play_type == 80 || play_type == 85 || play_type == 86 || play_type == 91 || play_type == 92 || play_type == 97 ||
         play_type == 98 || play_type == 103 || play_type == 104 || play_type == 109 || play_type == 110) {
            var number_length = 2;
        }else{
            var number_length = 1;
        }
        if (number_form.length != number_length) {
           console.log(key);
           validate = false;
        }
        if (number_to != '' && number_form.length != number_length) {
            console.log(key);
            validate = false;
        }
    }
    if (!validate) {
       swal({
        title: 'Warning!',
        text: 'กรุณากรอกข้อมูลให้ถูกต้อง',
        type: 'warning',
        confirmButtonText: 'close'
       });
       return false;
    }
    return true;
});
function changeValue(dropdown) {
    var length = dropdown.options[dropdown.selectedIndex].getAttribute('data-length');
    number = document.getElementById('number');
    numberTo = document.getElementById('numberTo');
    if (length) {
        number.maxLength = length;
        numberTo.maxLength = length;
    }
}
EOT;
$this->registerJs($js, \yii\web\View::POS_LOAD);
?>
<div class="row">

    <?php if ($thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' || $thaiSharedGame->title === 'หวยเวียดนามชุด') { ?>
        <?php $form = ActiveForm::begin(['id' => 'lottery_lao_set_limit_number']);?>
        <?= $form->field($model, 'limits')->widget(MultipleInput::className(), [
            'max' => 10,
            'columns' => [
                [
                    'name'  => 'number',
                    'title' => 'number',
                ],
                [
                    'name'  => 'totalSetNumber',
                    'title' => 'Total Set Number',
                ],
            ],
        ])->label(false);
        ?>
    <?php } else { ?>
        <?php $form = ActiveForm::begin(['id' => 'thai_shared_limit_number']);?>
        <?= $form->field($model, 'limits')->widget(MultipleInput::className(), [
            'max' => 10,
            'columns' => [
                [
                    'name'  => 'playTypeId',
                    'type'  => 'dropDownList',
                    'title' => 'PlayType',
                    'items' => $playTypes
                ],
                [
                    'name'  => 'numberFrom',
                    'title' => 'number From',
                ],
                [
                    'name'  => 'numberTo',
                    'title' => 'number To',
                ],
                [
                    'name'  => 'jackPotPerUnit',
                    'title' => 'Jack Pot Per Unit'
                ]
            ]
        ])->label(false);
        ?>

    <?php } ?>
    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancel', ['index', 'thaiSharedGameId' => $thaiSharedGame->id], ['class'=>'btn btn-danger']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
