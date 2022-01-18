<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedGame */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'gameId')->dropDownList($games, ['prompt' => 'กรุณาเลือกเกมหวยรัฐ']); ?>

<?= $form->field($model, 'title')->textInput([]) ?>

<?= $form->field($model, 'startDate')->widget(DateTimePicker::classname(), [
    'options' => ['placeholder' => 'Select Start Datetime'],
    'pluginOptions' => [
        'autoclose' => true
    ]
]); ?>


<?= $form->field($model, 'endDate')->widget(DateTimePicker::classname(), [
    'options' => ['placeholder' => 'Select End Datetime'],
    'pluginOptions' => [
        'autoclose' => true
    ]
]); ?>

<?= $form->field($model, 'description')->widget(TinyMce::className(), [
    'options' => ['rows' => 12],
    'clientOptions' => [
        'plugins' => [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools codesample toc noneditable",
        ],
        'toolbar' => "undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    ]
]); ?>




<?= $form->field($model, 'status')->radioList([
    '1' => Yii::t('app', 'Open'),
    '0' => Yii::t('app', 'Close'),
]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

<?php ActiveForm::end(); ?>